<? if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('phpass-0.1/PasswordHash.php');

define('STATUS_ACTIVATED', '1');
define('STATUS_NOT_ACTIVATED', '0');

/**
 * Tank_auth
 *
 * Authentication library for Code Igniter.
 *
 * @package		Tank_auth
 * @author		Ilya Konyukhov (http://konyukhov.com/soft/)
 * @version		1.0.9
 * @based on	DX Auth by Dexcell (http://dexcell.shinsengumiteam.com/dx_auth)
 * @license		MIT License Copyright (c) 2008 Erick Hartanto
 */
class Tank_auth
{
	private $error = array();

	function __construct()
	{
		$this->ci =& get_instance();

		$this->ci->load->config('tank_auth', TRUE);
		$this->ci->load->library('session');
		$this->ci->load->model('tank_auth/users');

		// Try to autologin
		$this->autologin();
	}
	
	/**
	 * Login user on the site. Return TRUE if login is successful
	 * (user exists and activated, password is correct), otherwise FALSE.
	 *
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function login($login, $password, $remember = FALSE)
	{
		if ((strlen($login) > 0) AND (strlen($password) > 0)) 
		{
			$get_user_func = 'get_user_by_login';

			if (!is_null($user = $this->ci->users->$get_user_func($login)))		// login ok
			{	
				// Does password match hash in database?
				$hasher = new PasswordHash(
						$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
						$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
				if ($hasher->CheckPassword($password, $user->password)) 	// password ok, success!
				{
					$pref_overrides = json_decode($user->prefs);
					$userdata = $this->initialize_userdata($user->userdata);

					$this->ci->session->set_userdata(array(
						'user_id'	=> $user->id,
						'username'	=> $user->username,
						'avatar_url' => base_url($user->avatar_path),
						'display_name' => $user->display_name,
						'email' => $user->email,
						'role' => $user->role,
						'init' => $user->init,
						'status'	=> ($user->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
						'contact_name' => $user->display_name,
						'contact_email' => $user->email,
						'phone' => '',
						'prefs' => $this->get_prefs($pref_overrides),
						'requests' => $this->ci->requests_model->get_users_session_requests($user->id),
			            'account_profile_made' => $this->ci->tutor_model->is_profile_made($user->id),
			            'userdata' => $userdata
					));

					// This is done in case of missing data to overrite the DB userdata. Useful in places where we get the userdata from DB rather than from session (e.g. Students page)
					$this->set_userdata($userdata);

					$this->ci->session->set_flashdata('logged_in', TRUE);

					if ($user->activated == 0) 		// not activated
					{							
						// Place activation notification and restriction code here
					} 
																	
					if ($remember) 
					{
						$this->create_autologin($user->id);
					}

					$this->ci->users->update_login_info(
							$user->id,
							$this->ci->config->item('login_record_ip', 'tank_auth'),
							$this->ci->config->item('login_record_time', 'tank_auth'));

					return TRUE;		
				} 
				else 	// fail - wrong password
				{														
					$this->error = array('password' => 'auth_incorrect_password');
				}
			} 
			else 	// fail - wrong login
			{															
				$this->error = array('login' => 'auth_incorrect_login');
			}
		}
		return FALSE;
	}

	/**
	 * Logout user from the site
	 *
	 * @return	void
	 */
	function logout()
	{
		if (!$this->ci->session->userdata('user_id'))
			return;

		$this->delete_autologin();

		// See http://codeigniter.com/forums/viewreply/662369/ as the reason for the next line
		$this->ci->session->set_userdata(array(
			'user_id' => '', 
			'username' => '', 
			'status' => '', 
			'email' => '',
			'init' => '',
			'display_name' => '',
			'avatar_url' => '',
			'contact_name' => '',
			'contact_email' => '',
			'role' => '',
			'prefs' => '',
			'requests' => '',
			'user_data' => '',
			'userdata' => ''));
	
		$this->ci->session->unset_userdata(array('status', 'email', 'display_name', 'avatar_url', 'username', 'user_id', 'contact_name', 'contact_email', 'role', 'prefs', 'requests', 'user_data', 'userdata'));

//	$this->ci->session->sess_destroy();
		$this->ci->session->set_flashdata('logged_out', TRUE);
	}

	/**
	 * Check if user logged in.
	 *
	 * @return	bool
	 */
	function is_logged_in()
	{
//		var_dump ($this->ci->session->all_userdata());
		return $this->ci->session->userdata("user_id");

		/*	Will handle check for activation later

			return $this->ci->session->userdata('status') === ($activated ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED);
		*/
	}

	/**
	 * Check if user is activated.
	 *
	 * @return	bool
	 */
	function is_activated()
	{
		return $this->ci->session->userdata('status');
	}
	
	/**
	 * Get user_id
	 *
	 * @return	string
	 */
	function get_user_id()
	{
		return $this->ci->session->userdata('user_id');
	}

	/**
	 * Create new user on the site and return some data about it:
	 * user_id, username, password, email, new_email_key (if any).
	 *
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	array
	 */
	function create_user($email, $password, $data = array())
	{
		if (!$this->ci->users->is_email_available($email)) 
		{
			$this->error = array('email' => 'auth_email_in_use');
		} 
		else 
		{
			// Hash password using phpass
			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
			$hashed_password = $hasher->HashPassword($password);

			$data['password'] = $hashed_password;
			$data['email'] = $email;
			$data['last_ip'] = $this->ci->input->ip_address();

			$data['prefs'] = json_encode($this->get_prefs());

			if (!is_null($res = $this->ci->users->create_user($data, FALSE))) 
			{
				$data['user_id'] = $res['user_id'];
				$data['password'] = $password;
				unset($data['last_ip']);

				return $data;
			}
		}
		return NULL;
	}

	function get_prefs($pref_overrides = NULL)
	{
		$prefs = array(
			'redirect' => 'account',
			'units' => 'km',
			'distance' => DEFAULT_FIND_DISTANCE,
			'sort' => 'distance',
			'readable-location' => '',
			'readable-subject' => ''
		);

		if ($pref_overrides && is_array($pref_overrides))
			$prefs = array_merge($prefs, $pref_overrides);

		return $prefs;
	}

	function set_prefs($prefs)
	{
		$user_id = $this->ci->session->userdata('user_id');

		$this->sanitize_prefs($prefs);

		if (!$prefs)
			return;


		$current_prefs = $this->ci->session->userdata('prefs');
		$prefs = array_merge($current_prefs, $prefs);

		$this->ci->session->set_userdata('prefs', $prefs);

		if ($user_id)
			return $this->ci->users->set_prefs($user_id, $prefs);
	}

	function sanitize_prefs(&$prefs)
	{
		if (isset($prefs['units']) && !in_array($prefs['units'], array('km', 'miles')))
			unset($prefs['units']);

		if (isset($prefs['sort']) && !in_array($prefs['sort'], array('price', 'distance', 'rating', 'new')))
			unset($prefs['sort']);

		if (isset($prefs['distance']) && !(is_numeric($prefs['distance']) && $prefs['distance'] < 1000))
			unset($prefs['distance']);
	}

	/**
	 * Check if email available for registering.
	 * Can be called for instant form validation.
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_email_available($email)
	{
		return ((strlen($email) > 0) AND $this->ci->users->is_email_available($email));
	}

	/**
	 * Change email for activation and return some data about user:
	 * user_id, username, email, new_email_key.
	 * Can be called for not activated users only.
	 *
	 * @param	string
	 * @return	array
	 */
	function change_email($email)
	{
		$user_id = $this->ci->session->userdata('user_id');

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, FALSE))) 
		{
			$data = array(
				'user_id'	=> $user_id,
				'email'		=> $email,
			);
			if (strtolower($user->email) == strtolower($email)) 
			{		
				$data['new_email_key'] = $user->new_email_key;
				return $data;
			} 
			elseif ($this->ci->users->is_email_available($email)) 
			{
				$data['new_email_key'] = md5(rand().microtime());
				$this->ci->users->set_new_email($user_id, $email, $data['new_email_key'], FALSE);
				return $data;
			} 
			else 
			{
				$this->error = array('email' => 'auth_email_in_use');
			}
		}
		return NULL;
	}

	/**
	 * Set new password key for user and return some data about user:
	 * user_id, username, email, new_pass_key.
	 * The password key can be used to verify user when resetting his/her password.
	 *
	 * @param	string
	 * @return	array
	 */
	function forgot_password($login)
	{
		if (strlen($login) > 0) {
			if (!is_null($user = $this->ci->users->get_user_by_login($login))) {

				$data = array(
					'user_id'		=> $user->id,
					'username'		=> $user->username,
					'email'			=> $user->email,
					'new_pass_key'	=> md5(rand().microtime()),
				);

				$this->ci->users->set_password_key($user->id, $data['new_pass_key']);
				return $data;

			} else {
				$this->error = array('login' => 'auth_incorrect_email_or_username');
			}
		}
		return NULL;
	}

	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function can_reset_password($user_id, $new_pass_key, $expires = TRUE)
	{
		if ((strlen($user_id) > 0) AND (strlen($new_pass_key) > 0)) {
			return $this->ci->users->can_reset_password(
				$user_id,
				$new_pass_key,
				$expires,
				$this->ci->config->item('forgot_password_expire', 'tank_auth'));
		}
		return FALSE;
	}

	/**
	 * Replace user password (forgotten) with a new one (set by user)
	 * and return some data about it: user_id, username, new_password, email.
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function reset_password($user_id, $new_pass_key, $new_password)
	{
		if ((strlen($user_id) > 0) AND (strlen($new_pass_key) > 0) AND (strlen($new_password) > 0)) {

			if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) {

				// Hash password using phpass
				$hasher = new PasswordHash(
						$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
						$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
				$hashed_password = $hasher->HashPassword($new_password);

				if ($this->ci->users->reset_password(
						$user_id,
						$hashed_password,
						$new_pass_key,
						$this->ci->config->item('forgot_password_expire', 'tank_auth'))) {	// success

					// Clear all user's autologins
					$this->ci->load->model('tank_auth/user_autologin');
					$this->ci->user_autologin->clear($user->id);

					return array(
						'user_id'		=> $user_id,
						'username'		=> $user->username,
						'email'			=> $user->email,
						'new_password'	=> $new_password,
					);
				}
			}
		}
		return NULL;
	}

	/**
	 * Change user password (only when user is logged in)
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function change_password($old_pass, $new_pass)
	{
		$user_id = $this->ci->session->userdata('user_id');

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) {

			// Check if old password correct
			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
			if ($hasher->CheckPassword($old_pass, $user->password)) {			// success

				// Hash new password using phpass
				$hashed_password = $hasher->HashPassword($new_pass);

				// Replace old password with new one
				$this->ci->users->change_password($user_id, $hashed_password);
				return TRUE;

			} else {															// fail
				$this->error = array('old_password' => 'auth_incorrect_password');
			}
		}
		return FALSE;
	}

	/**
	 * Change user email (only when user is logged in) and return some data about user:
	 * user_id, username, new_email, new_email_key.
	 * The new email cannot be used for login or notification before it is activated.
	 *
	 * @param	string
	 * @param	string
	 * @return	array
	 */
	function set_new_email($new_email, $password)
	{
		$user_id = $this->ci->session->userdata('user_id');

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) {

			// Check if password correct
			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
			if ($hasher->CheckPassword($password, $user->password)) {			// success

				$data = array(
					'user_id'	=> $user_id,
					'username'	=> $user->username,
					'new_email'	=> $new_email,
				);

				if ($user->email == $new_email) {
					$this->error = array('email' => 'auth_current_email');

				} elseif ($user->new_email == $new_email) {		// leave email key as is
					$data['new_email_key'] = $user->new_email_key;
					return $data;

				} elseif ($this->ci->users->is_email_available($new_email)) {
					$data['new_email_key'] = md5(rand().microtime());
					$this->ci->users->set_new_email($user_id, $new_email, $data['new_email_key'], TRUE);
					return $data;

				} else {
					$this->error = array('email' => 'auth_email_in_use');
				}
			} else {															// fail
				$this->error = array('password' => 'auth_incorrect_password');
			}
		}
		return NULL;
	}

	/**
	 * Activate new email, if email activation key is valid.
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function activate_new_email($user_id, $new_email_key)
	{
		if ((strlen($user_id) > 0) AND (strlen($new_email_key) > 0)) {
			return $this->ci->users->activate_new_email(
					$user_id,
					$new_email_key);
		}
		return FALSE;
	}

	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @param	string
	 * @return	bool
	 */
	function delete_user($password)
	{
//		return TRUE;
		$user_id = $this->ci->session->userdata('user_id');

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) 
		{
			// Check if password correct
			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
			if ($hasher->CheckPassword($password, $user->password)
				&& $this->ci->users->delete_user($user_id)) 
			{			// success
				$this->logout();
				$this->ci->session->set_flashdata('account_deleted', TRUE);

				return TRUE;
			}
		}
		return FALSE;
	}

	function user_exists($user_id)
	{
		return $this->ci->users->user_exists($user_id);
	}

	/**
	 * Get error message.
	 * Can be invoked after any failed operation such as login or register.
	 *
	 * @return	string
	 */
	function get_error_message()
	{
		return $this->error;
	}

	/**
	 * Save data for user's autologin
	 *
	 * @param	int
	 * @return	bool
	 */
	private function create_autologin($user_id)
	{
		$this->ci->load->helper('cookie');
		$key = substr(md5(uniqid(rand().get_cookie($this->ci->config->item('sess_cookie_name')))), 0, 16);

		$this->ci->load->model('tank_auth/user_autologin');
		$this->ci->user_autologin->purge($user_id);

		if ($this->ci->user_autologin->set($user_id, md5($key))) {
			set_cookie(array(
					'name' 		=> $this->ci->config->item('autologin_cookie_name', 'tank_auth'),
					'value'		=> serialize(array('user_id' => $user_id, 'key' => $key)),
					'expire'	=> $this->ci->config->item('autologin_cookie_life', 'tank_auth'),
			));
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Clear user's autologin data
	 *
	 * @return	void
	 */
	private function delete_autologin()
	{
		$this->ci->load->helper('cookie');
		if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'tank_auth'), TRUE)) {

			$data = unserialize($cookie);

			$this->ci->load->model('tank_auth/user_autologin');
			$this->ci->user_autologin->delete($data['user_id'], md5($data['key']));

			delete_cookie($this->ci->config->item('autologin_cookie_name', 'tank_auth'));
		}
	}

	/**
	 * Login user automatically if he/she provides correct autologin verification
	 *
	 * @return	void
	 */
	private function autologin()
	{
		if (!$this->is_logged_in() AND !$this->is_logged_in(FALSE)) {			// not logged in (as any user)

			$this->ci->load->helper('cookie');
			if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'tank_auth'), TRUE)) {

				$data = unserialize($cookie);

				if (isset($data['key']) AND isset($data['user_id'])) {

					$this->ci->load->model('tank_auth/user_autologin');
					if (!is_null($user = $this->ci->user_autologin->get($data['user_id'], md5($data['key'])))) {

						var_dump($data, $user);

						// Login user
						$this->ci->session->set_userdata(array(
								'user_id'	=> $user->id,
								'username'	=> $user->username,
								'status'	=> STATUS_ACTIVATED,
								'avatar_url' => base_url($user->avatar_path),
								'display_name' => $user->display_name,
								'email' => $user->email,
						));

						// Renew users cookie to prevent it from expiring
						set_cookie(array(
								'name' 		=> $this->ci->config->item('autologin_cookie_name', 'tank_auth'),
								'value'		=> $cookie,
								'expire'	=> $this->ci->config->item('autologin_cookie_life', 'tank_auth'),
						));

						$this->ci->users->update_login_info(
								$user->id,
								$this->ci->config->item('login_record_ip', 'tank_auth'),
								$this->ci->config->item('login_record_time', 'tank_auth'));
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}

	/**
	 * Check if login attempts exceeded max login attempts (specified in config)
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_max_login_attempts_exceeded($login)
	{
		if ($this->ci->config->item('login_count_attempts', 'tank_auth')) {
			$this->ci->load->model('tank_auth/login_attempts');
			return $this->ci->login_attempts->get_attempts_num($this->ci->input->ip_address(), $login)
					>= $this->ci->config->item('login_max_attempts', 'tank_auth');
		}
		return FALSE;
	}

	/**
	 * Bounce user to home page with arg if not logged in
	 *
	 * @return	void
	 */
	function bounce_if_unlogged()
	{
		if (!$this->is_logged_in())
		{
			$this->ci->session->set_flashdata('previous_page', this_url_with_query());
			$this->ci->session->set_flashdata('need_login', TRUE);
			redirect('');
		}
	}

	function check_password($password, $identifying_var, $by = 'id')
	{

		if ($by == 'id')
			$user = $this->ci->users->get_user_by_id($identifying_var);
		else
			$user = $this->ci->users->get_user_by_login($identifying_var);
		
		$hasher = new PasswordHash(
				$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
				$this->ci->config->item('phpass_hash_portable', 'tank_auth'));

		if ($hasher->CheckPassword($password, $user->password))
		{
			return TRUE;
		}
	}


	function make_password($password)
	{
		// Hash password using phpass
		$hasher = new PasswordHash(
				$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
				$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
		$hashed_password = $hasher->HashPassword($password);

		return $hashed_password;
	}

	/**
	 * Make username from display name
	 *
	 * @return	string
	 */
	function make_username($actual_name, $must_be_unique = TRUE)
	{
//		$this->ci->load->helper('text');
		$this->ci->load->model('tank_auth/users');

		// Make string lower case
		$username = mb_strtolower($actual_name);

		// Strip tags and special chars
	    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "â€”", "â€“", ",", "<", ".", ">", "/", "?");
    	$username = trim(str_replace($strip, "", strip_tags($username)));

    	// Replace spaces with -
    	$username = preg_replace('/\s+/', "-", $username);

    	if ($must_be_unique)
    	{
			// If generated username already taken, append '-' and then a random digits until an available name is found    	
	    	if (!$this->ci->users->is_username_available($username))
	    	{
	    		$username .= '-';
	    		do
	    		{
		    		$username .= rand(0, 9);
	    		} while (!$this->ci->users->is_username_available($username));
	    	}    		
    	}

		return $username;
	}

	function make_random_password() 
	{
	    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	    $pass = '';
	    $alphabet_length = strlen($alphabet) - 1;
	    for ($i = 0; $i < 10; $i++) 
	    {
	        $n = rand(0, $alphabet_length);
	        $pass .= $alphabet[$n];
	    }
	    return $pass; 
	}

	function initialize_userdata($userdata = NULL)
	{
		$userdata = (array) json_decode($userdata);
/*		
		$user_id = $this->ci->session->userdata('user_id');

		$userdata = $this->ci->users->get_userdata($user_id);

		// Make empty array if no userdata
		if (!is_array($userdata))
			$userdata = array();
*/
		$default_userdata = array(
			'favourited_tutor_ids' => array(),
			'notification_settings' => array("tutor_contact","tutor_invite","tutor_accept","tutor_reject","tutor_local_request","tutor_distance_request","tutor_tips",
											 "student_applied","student_tips",
											 "general_email_changed","general_pass_changed"),
			'worked_with_ids' => array(),
			'hidden_past_tutor_ids' => array(),
			'hidden_past_student_ids' => array()
		);

//		var_dump($userdata);

		$userdata = array_replace_recursive($default_userdata, $userdata);

		return $userdata;
	}

	// Only used to get other users' userdata, not the logged in user (for them use session) EXCEPT for userdata that could be edited elsewhere (such as worked-with-ids). Should this function account for both?
	function get_userdata($user_id)
	{
		return $this->ci->users->get_userdata($user_id);
	}

	function set_userdata($userdata, $user_id = NULL)
	{
		if (!$user_id)
		{
			$user_id = $this->ci->session->userdata('user_id');

			// Only update session if we're updating the logged in user's userdata
			$this->ci->session->set_userdata('userdata', $userdata);	
		}


		if ($this->ci->users->set_userdata($userdata, $user_id))
			return TRUE;
	}

	/**
	 * Increase number of attempts for given IP-address and login
	 * (if attempts to login is being counted)
	 *
	 * @param	string
	 * @return	void
	 */
	private function increase_login_attempt($login)
	{
		if ($this->ci->config->item('login_count_attempts', 'tank_auth')) {
			if (!$this->is_max_login_attempts_exceeded($login)) {
				$this->ci->load->model('tank_auth/login_attempts');
				$this->ci->login_attempts->increase_attempt($this->ci->input->ip_address(), $login);
			}
		}
	}

	/**
	 * Clear all attempt records for given IP-address and login
	 * (if attempts to login is being counted)
	 *
	 * @param	string
	 * @return	void
	 */
	private function clear_login_attempts($login)
	{
		if ($this->ci->config->item('login_count_attempts', 'tank_auth')) {
			$this->ci->load->model('tank_auth/login_attempts');
			$this->ci->login_attempts->clear_attempts(
					$this->ci->input->ip_address(),
					$login,
					$this->ci->config->item('login_attempt_expire', 'tank_auth'));
		}
	}

	function attempt_signup($login = TRUE)
	{
		if ($import_account_data = $this->ci->session->userdata('admin-import-account-data'))
		{
			$_POST = $import_account_data;
			$this->ci->session->unset_userdata('admin-import-account-data');
		}

		if ($this->ci->input->post('role') == ROLE_STUDENT)
		{
			$role = ROLE_STUDENT;
		}
		else
		{
			$role = ROLE_TUTOR;
		}

		if ($role == ROLE_STUDENT)
		{
			$this->ci->form_validation->set_rules('display-name', 'your Name', 'trim|strip_tags|required|max_length[80]|xss_clean');	
		}
		else
		{
			$this->ci->form_validation->set_rules('first-name', 'your First Name', 'trim|strip_tags|required|max_length[80]|xss_clean');					
			$this->ci->form_validation->set_rules('last-name', 'your Last Name', 'trim|strip_tags|required|max_length[80]|xss_clean');					
		}

		$this->ci->form_validation->set_rules('email', 'Your Email', 'trim|strip_tags|required|valid_email|max_length[80]|callback_email_unregistered|xss_clean');	
		$this->ci->form_validation->set_rules('new-password', 'New Password', 'trim|strip_tags|required|max_length[80]|xss_clean');	

		if (!$this->ci->form_validation->run())	
		{
			$response = $this->ci->form_validation->invalid_response();
		}
		else
		{			
			$email = $this->ci->input->post('email');
			$password = $this->ci->input->post('new-password');

			$first_name = $this->ci->input->post('first-name');
			$last_name = $this->ci->input->post('last-name');
			$display_name = $this->ci->input->post('display-name');

			if (!$first_name)
				$first_name = '';
			if (!$last_name)
				$last_name = '';
			
			if (!$display_name)		// If Tutor Signup
			{
				$display_name = $first_name.' '.$last_name;
			}

			$userdata = array(
				'username' => $this->make_username($display_name),
				'role' => $role,
				'display_name' => $display_name,
				'first_name' => $first_name,
				'last_name' => $last_name,
				'userdata' => json_encode($this->initialize_userdata())
			);

			if (!is_null($data = $this->create_user($email, $password, $userdata)))
			{	// success
				
				$data['site_name'] = $this->ci->config->item('website_name', 'tank_auth');

				$user_id = $data['user_id'];

				if ($login)
				{
					//Immediately log user in
					$this->login($email, $password);
					unset($data['password']); // Clear password (just for any case)					
				}
				else
				{
					$import_account_data['user_id'] = $data['user_id'];
					$import_account_data['username'] = $userdata['username'];
					$this->ci->session->set_userdata('admin-import-account-data', $import_account_data);
				}				

				// Add welcome profile notice
				$this->ci->load->model('profile_notices_model');

				if ($login)
				{
					$this->ci->session->set_flashdata('just_registered', TRUE);
				}
				
				if ($role == ROLE_STUDENT)
				{
					$this->ci->profile_notices_model->add_notice(WELCOME_NEW_STUDENT, $user_id, 1000); 

					if ($this->ci->input->post('open_requests'))
					{
						$request_id = $this->ci->session->userdata('open_request_id');

						if ($request_id && $this->ci->requests_model->open($request_id, $user_id, TRUE))
						{
							$this->ci->session->set_userdata('open_request_id', NULL);
							$response = $this->ci->form_validation->response(STATUS_OK, array('requestId' => $request_id));								
							$this->ci->reaction_notice->set("<b>Request made!</b><hr>We've also made an account for you and logged you in.", 'success', '8000');
						}
						else
						{
							$response = $this->ci->form_validation->response();
							$this->ci->reaction_notice->set("<b>Welcome to Tutorical!</b>", 'success', 3500);
						}
					}
				}
				else
				{
					if ($login)
					{
						$this->ci->reaction_notice->set("<b>Welcome to Tutorical!</b><br>Let's start by ".anchor('account/profile','making your profile').".", 'success', 0);
						$this->ci->profile_notices_model->add_notice(MAKE_PROFILE, $user_id, 1000);
					}
					$response = $this->ci->form_validation->response();
				}
			}
			else
			{
				$response = $this->ci->form_validation->response(STATUS_UNKNOWN_ERROR);
			}
		}
		return $response;
	}
}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */