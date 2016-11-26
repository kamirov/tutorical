<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users
 *
 * This model represents user authentication data. It operates the following tables:
 * - user account data,
 * - user profiles
 *
 * @package	Tank_auth
 * @author	Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Users extends CI_Model
{
	private $table_name			= 'users';			// user accounts
	private $profile_table_name	= 'tutor_profiles';	// user profiles

	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
		$this->table_name			= $ci->config->item('db_table_prefix', 'tank_auth').$this->table_name;
		$this->profile_table_name	= $ci->config->item('db_table_prefix', 'tank_auth').$this->profile_table_name;
	}

	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	function get_user_by_id($user_id, $activated = 0)
	{
		$this->db->where('id', $user_id);
//		$this->db->where('activated', $activated ? 1 : 0);

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() > 0) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by login (username or email)
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_login($login)
	{
		$this->db->where('LOWER(username)=', strtolower($login));
		$this->db->or_where('LOWER(email)=', strtolower($login));

		$query = $this->db->get($this->table_name);

		if ($query->num_rows() > 0) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by username
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_username($username)
	{

		$this->db->where('LOWER(username)=', strtolower($username));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() > 0) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by email
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_email($email)
	{
		$this->db->where('LOWER(email)=', strtolower($email));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() > 0 ) return $query->row();
		return NULL;
	}

	function user_exists($user_id)
	{
		$this->db->where('id', $user_id);

		$query = $this->db->get($this->table_name);
		return $query->num_rows() > 0;
	}

	/**
	 * Check if username available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_username_available($username)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(username)=', strtolower($username));

		if ($user_id = $this->session->userdata('user_id'))
		{
			$this->db->where('id !=', $user_id);
		}

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 0;
	}

	/**
	 * Check if email available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_email_available($email)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(email)=', strtolower($email));
		$this->db->or_where('LOWER(new_email)=', strtolower($email));

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 0;
	}

	/**
	 * Create new user record
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	function create_user($data, $activated = TRUE)
	{
		if (!isset($data['created']))		// Needed for init
			$data['created'] = date('Y-m-d H:i:s');
		$data['avatar_path'] = DEFAULT_AVATAR_PATH;
		$data['activated'] = $activated ? 1 : 0;

		if ($this->db->insert($this->table_name, $data)) {
			$user_id = $this->db->insert_id();

			if (isset($data['role']) && $data['role'] != ROLE_STUDENT)
				$this->create_profile($user_id);

			return array('user_id' => $user_id);
		}
		return NULL;
	}

	/**
	 * Delete user record
	 *
	 * @param	int
	 * @return	bool
	 */
	function delete_user($user_id)
	{
		$this->db->trans_start();

		 // Delete applications and decrement num_of_applications for each request

		 $requests = $this->db->select('r.id')
		 						->from('requests r')
		 						->join('requests_tutors rt', 'rt.request_id = r.id')
		 						->where('rt.tutor_id', $user_id)
		 						->get()->result_array();

		 $decrementing_request_ids = array();

		 $this->load->model('requests_model');
//		 var_dump($requests);

		 foreach($requests as $request)
		 {
		 	$application = $this->requests_model->get_tutor_application($request['id'], $user_id);

//		 	var_dump($application);

		 	if ($application['status'] != RESPONSE_STATUS_APPROVED)
		 	{
		 		$decrementing_request_ids[] = $request['id'];
		 	}
		 }

		 if ($decrementing_request_ids)
		 {
		 	$this->db->set('num_of_applications', 'num_of_applications-1', FALSE)
		 			 ->where_in('id', $decrementing_request_ids)
		 			 ->update('requests');			
		 }

		 $this->db->where('tutor_id', $user_id)
		  		  ->where('status != '.RESPONSE_STATUS_APPROVED);
		 $this->db->delete('requests_tutors');

		 $this->give_things_to_deleted($user_id);

//		 $this->db->trans_complete();
//		 return FALSE;

		 $this->db->where('id', $user_id);
		 $this->db->delete($this->table_name);

		 $this->db->where('student_id', $user_id)
		 		 ->delete('students_tutors');

		 $this->db->where('tutor_id', $user_id)
		 		 ->delete('students_tutors');

		 $this->delete_profile($user_id);

		$this->db->trans_complete();

//		return FALSE;

		return $this->db->trans_status();
	}

	function give_things_to_deleted($user_id)
	{
		$this->db->where('commenter_id', $user_id)
				 ->update('profile_comments', array('commenter_id' => DELETED_ID));

		$this->db->where('commented_user_id', $user_id)
				 ->update('profile_comments', array('commented_user_id' => DELETED_ID));

		$this->db->where('reviewed_user_id', $user_id)
				 ->update('user_reviews', array('reviewed_user_id' => DELETED_ID));

		// We don't have to do status check here since all the unapproved ones have been deleted by now
		$this->db->where('tutor_id', $user_id)
				 ->update('requests_tutors', array('tutor_id' => DELETED_ID));

		$this->db->where('user_id', $user_id)
				 ->update('requests', array(
				 		'user_id' => DELETED_ID,
				 		'status' => REQUEST_STATUS_CLOSED
				 	));
	}

	/**
	 * Delete user profile
	 *
	 * @param	int
	 * @return	void
	 */
	private function delete_profile($user_id)
	{
		// There has to be a way to multi-table delete

		$this->db->where('user_id', $user_id);
		$this->db->delete($this->profile_table_name);

		$this->db->where('user_id', $user_id);
		$this->db->delete('tutors_profile_notices');

		$this->db->where('user_id', $user_id);
		$this->db->delete('users_subjects');

		$this->db->where('user_id', $user_id);
		$this->db->delete('user_autologin');

		$this->db->where('user_id', $user_id);
		$this->db->delete('user_availabilities');

		$this->db->where('user_id', $user_id);
		$this->db->delete('user_locations');

		$this->db->where('user_id', $user_id);
		$this->db->delete('user_notes');

		$this->db->where('user_id', $user_id);
		$this->db->delete('user_prices');


	}

	/**
	 * Set new password key for user.
	 * This key can be used for authentication when resetting user's password.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function set_password_key($user_id, $new_pass_key)
	{
		$this->db->set('new_password_key', $new_pass_key);
		$this->db->set('new_password_requested', date('Y-m-d H:i:s'));
		$this->db->where('id', $user_id);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	function can_reset_password($user_id, $new_pass_key, $expires = TRUE, $expire_period = 900)
	{
		$this->db->select('1', FALSE);
		$this->db->where('id', $user_id);
		$this->db->where('new_password_key', $new_pass_key);
	
		if ($expires)
			$this->db->where('UNIX_TIMESTAMP(new_password_requested) >', time() - $expire_period);

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 1;
	}

	/**
	 * Change user password if password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	bool
	 */
	function reset_password($user_id, $new_pass, $new_pass_key, $expire_period = 900)
	{
		$this->db->set('password', $new_pass);
		$this->db->set('new_password_key', NULL);
		$this->db->set('new_password_requested', NULL);
		$this->db->set('activated', TRUE);
		$this->db->where('id', $user_id);
		$this->db->where('new_password_key', $new_pass_key);
		$this->db->where('UNIX_TIMESTAMP(new_password_requested) >=', time() - $expire_period);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Change user password
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function change_password($user_id, $new_pass)
	{
		$this->db->set('password', $new_pass);
		$this->db->where('id', $user_id);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Set new email for user (may be activated or not).
	 * The new email cannot be used for login or notification before it is activated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function set_new_email($user_id, $new_email, $new_email_key, $activated)
	{
//		$this->db->set($activated ? 'new_email' : 'email', $new_email);
		$this->db->set('email', $new_email);
//		$this->db->set('new_email_key', $new_email_key);
		$this->db->where('id', $user_id);
//		$this->db->where('activated', $activated ? 1 : 0);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Activate new email (replace old email with new one) if activation key is valid.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function activate_new_email($user_id, $new_email_key)
	{
		$this->db->set('email', 'new_email', FALSE);
		$this->db->set('new_email', NULL);
		$this->db->set('new_email_key', NULL);
		$this->db->where('id', $user_id);
		$this->db->where('new_email_key', $new_email_key);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @return	void
	 */
	function update_login_info($user_id, $record_ip, $record_time)
	{
		$this->db->set('new_password_key', NULL);
		$this->db->set('new_password_requested', NULL);

		if ($record_ip)		$this->db->set('last_ip', $this->input->ip_address());
		if ($record_time)	$this->db->set('last_login', date('Y-m-d H:i:s'));

		$this->db->where('id', $user_id);
		$this->db->update($this->table_name);
	}

	/**
	 * Ban user
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	function ban_user($user_id, $reason = NULL)
	{
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name, array(
			'banned'		=> 1,
			'ban_reason'	=> $reason,
		));
	}

	/**
	 * Unban user
	 *
	 * @param	int
	 * @return	void
	 */
	function unban_user($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name, array(
			'banned'		=> 0,
			'ban_reason'	=> NULL,
		));
	}

	function get_prefs($user_id)
	{
		$prefs = $this->db->select('prefs')
				 		->from('users')
						->where('id', $user_id)
						->get()->row_array();

		$prefs = $prefs['prefs'];

		$prefs = json_decode($prefs);

		return $prefs;
	}

	function set_prefs($user_id, $prefs)
	{
		$encoded_prefs = json_encode($prefs);

		$row = $this->db->where('id', $user_id)
						->update('users', array('prefs' => $encoded_prefs));
		

		return $prefs;
	}

	function change_role($user_id, $role)
	{
		$data = array(
			'role' => $role
		);

		$this->db->where('id', $user_id)
				 ->update('users', $data);

		if ($this->db->affected_rows() > 0)
		{
			if ($role == ROLE_TUTOR && $this->create_profile($user_id))
			{
				return TRUE;
			}
		}
	}

	/**
	 * Create an empty profile for a new user
	 *
	 * @param	int
	 * @return	bool
	 */
	private function create_profile($user_id)
	{
		$this->db->set('user_id', $user_id);
		return $this->db->insert($this->profile_table_name);
	}

	function was_user_welcomed($user_id)
	{
		$this->db->select('welcomed')
				 ->where('id', $user_id);

		$welcomed = $this->db->get($this->table_name)->row_array();
		return $welcomed['welcomed'];
	}

	function set_user_to_welcomed($user_id)
	{
		$this->db->set('welcomed', TRUE)
			 	 ->where('id', $user_id)
			 	 ->update($this->table_name);

		return $this->db->affected_rows() > 0;
	}

	function get_userdata($user_id)
	{
		$this->db->select('userdata')
			     ->from($this->table_name)
			     ->where('id', $user_id);
		$row = $this->db->get()->row_array();
		$userdata = (array) json_decode($row['userdata']);
		return $userdata;
	}

	function set_userdata($userdata, $user_id)
	{
//		var_dump($userdata);
		$userdata = json_encode($userdata);
//		var_dump($userdata);
//		return;
		$this->db->select('userdata')
				 ->where('id', $user_id);

		$current_userdata = $this->db->get($this->table_name)->row_array();
		$current_userdata = $current_userdata['userdata'];

//		var_export($current_userdata);
//		var_export($userdata);
//		var_dump($userdata, $current_userdata); return;

		if ($userdata == $current_userdata)
		{
			return TRUE;
		}
		else
		{
			$this->db->set('userdata', $userdata)
					 ->where('id', $user_id)
					 ->update($this->table_name);

			return $this->db->affected_rows() > 0;			
		}
	}
}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */