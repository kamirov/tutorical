<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form'));
		$this->load->library(array('security', 'form_validation'));
		$this->lang->load('tank_auth');
	}

	function signup($role = NULL)
	{
		if (!$role || !($role == 'tutor' || $role == 'student'))
		{
			redirect('signup/tutor');
		}

		if ($this->tank_auth->is_logged_in())	// logged in
		{									
			$this->reaction_notice->set('<b>You\'ve already signed up!</b><br> To make another account, '.anchor('logout','log out').'.', 8000);
			redirect('');
		}
		else	// not logged in
		{			
	        $data['meta'] = $this->config->item("auth-signup-$role");
			$this->load->page("signup/$role", $data);
		}
	}

	function login()
	{	
		if ($this->tank_auth->is_logged_in()) 	// logged in
		{						
			$this->reaction_notice->set('<b>You\'re already logged in!</b><br> To log in to another account, '.anchor('logout','log out').'.', 'warning', 8000);
			redirect('account');
		} 
		else 	// not logged in
		{
	        $data['meta'] = $this->config->item('auth-login');
			$this->load->page('login', $data);
		}
	}


	/**
	 * Logout user
	 *
	 * @return void
	 */
	function logout()
	{
		$this->tank_auth->logout();
		$this->reaction_notice->set_quick('<b>You\'ve logged out!</b>', 'alert');
		redirect('');
	}

	/* Ajax Function */
	function validate_email()
	{		
		if ($this->input->is_ajax_request())
		{
			if ($this->input->post('is_register'))
			{
				$this->form_validation->set_rules('email', 'Email', 'trim|strip_tags|required|xss_clean|valid_email|callback_email_unregistered');
			}
			else			
			{
				$this->form_validation->set_rules('email', 'Email', 'trim|strip_tags|required|xss_clean|valid_email|callback_email_registered');		
			}

			if (!$this->form_validation->run()) {
				echo form_error('email');
			}			
			else 
			{
				echo NULL;
			}			
		}
		else
		{
			show_404();
		}
	}

	/**
	 * Send activation email again, to the same or new email address
	 *
	 * @return void
	 */
	function send_again()
	{
		if (!$this->tank_auth->is_logged_in(FALSE)) {							// not logged in or activated
			redirect('auth/login/');

		} else {
			$this->form_validation->set_rules('email', 'Email', 'trim|strip_tags|required|xss_clean|valid_email');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->change_email(
						$this->form_validation->set_value('email')))) {			// success

					$data['site_name']	= $this->config->item('website_name', 'tank_auth');
					$data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

//					$this->_send_email('activate', $data['email'], $data);

					$this->_show_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)
						$data['errors'][$k] = '<div class="error-messages">'.$this->lang->line($v).'</div>';
				}
			}
			$this->load->page('auth/send_again_form', $data);
		}
	}

	/**
	 * Activate user account.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function activate()
	{
		$user_id		= $this->uri->segment(3);
		$new_email_key	= $this->uri->segment(4);

		// Activate user
		if ($this->tank_auth->activate_user($user_id, $new_email_key)) {		// success
			$this->tank_auth->logout();
			$this->_show_message($this->lang->line('auth_message_activation_completed').' '.anchor('auth/login/', 'Login'));

		} else {																// fail
			$this->_show_message($this->lang->line('auth_message_activation_failed'));
		}
	}

	/**
	 * Generate reset code (to change password) and send it to user
	 *
	 * @return void
	 */
	function forgot_password()
	{
        $data['meta'] = $this->config->item('auth-recovery');
		$this->load->page('recovery', $data);	
	}

	/* AJAX function */
	function send_recovery()
	{
		if ($this->input->is_ajax_request() && $this->form_validation->not_spam($this->input->post()))
		{
			$this->form_validation->set_rules('email', 'Email', 'trim|strip_tags|required|xss_clean|valid_email|callback_email_registered');
			$this->form_validation->set_rules('open_requests', '', 'trim|strip_tags|integer|xss_clean');

		    if (!$this->form_validation->run())
		    {
	        	echo json_encode($this->form_validation->invalid_response());
	        	return;
		    }

		    $email = $this->input->post('email');

			if (!is_null($data = $this->tank_auth->forgot_password($email))) 
			{
				$data['site_name'] = $this->config->item('website_name', 'tank_auth');

				if ($this->input->post('open_requests'))
				{
					$data['request_id'] = $this->session->userdata('open_request_id');
				}
				else
				{
					$data['request_id'] = '';
				}

				// Send email with password activation link
				if ($email_status = $this->_send_email('forgot_password', $data['email'], $data))
//				if (TRUE)
				{
					$data = array(
						'domain' => end(explode('@', $data['email'])),
						'emailStatus' => $email_status
					);

					echo json_encode($this->form_validation->response(STATUS_OK, $data));
		        	return;
				}
				else
				{
					echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));
		        	return;					
				}
			}
			else
			{
				echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));
	        	return;
			}
		}
	}

	/**
	 * Replace user password (forgotten) with a new one (set by user).
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_password($user_id = NULL, $new_pass_key = NULL, $is_new = 0, $request_id = NULL)
	{
		if (!isset($user_id, $new_pass_key))
		{
			show_404();
			return;
		}

        $data['meta'] = $this->config->item('auth-reset');
		
		if (!$this->tank_auth->can_reset_password($user_id, $new_pass_key))
		{
			$this->load->page('reset-token-expired', $data);
			return;
		}

        $data['reset_user_id'] = $user_id;
        $data['reset_new_pass_key'] = $new_pass_key;
        $data['is_new'] = $is_new;
        $data['request_id'] = $request_id;

		$this->load->page('reset-password', $data);
	}

	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @return void
	 */
	function unregister()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('auth/login/');

		} else {
			$this->form_validation->set_rules('password', 'Password', 'trim|strip_tags|required|xss_clean');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->delete_user(
						$this->form_validation->set_value('password'))) {		// success
					$this->_show_message($this->lang->line('auth_message_unregistered'));

				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)
						$data['errors'][$k] = '<div class="error-messages">'.$this->lang->line($v).'</div>';
				}
			}
			$this->load->page('auth/unregister_form', $data);
		}
	}

	function email_registered($email)
	{
		if ($this->tank_auth->is_email_available($email))
		{
			$this->form_validation->set_message('email_registered', 'Sorry! That email isn\'t registered.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	} 

	function email_unregistered($email)
	{
		if (!$this->tank_auth->is_email_available($email))
		{
			$this->form_validation->set_message('email_unregistered', 'Sorry! That email has been registered.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	} 

	function check_password($password, $email_field)
	{	
		$email = $this->input->post($email_field);

		if (!$this->tank_auth->check_password($password, $email, 'email'))
		{
			$this->form_validation->set_message('check_password', 'Sorry! That password is incorrect.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	} 

	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	void
	 */
	function _send_email($type, $email, &$data)
	{
		$email = array(
			'to' => $email,
			'subject' => sprintf($this->lang->line('auth_subject_'.$type), 'Tutorical'),
			'template' => $type,
			'data' => $data,
			'priority' => IMPORTANT_EMAIL_PRIORITY
		);

		return $this->email->process_email($email);
	}

	function attempt_reset()
	{
		// No need to do an anti spam here since they'd only reach this page if they visited the link sent to their email.
		if ($this->input->is_ajax_request()) 
		{
			$this->form_validation->set_rules('user-id', '', 'trim|strip_tags|required|integer|xss_clean');	
			$this->form_validation->set_rules('is-new', '', 'trim|strip_tags|integer|xss_clean');	
			$this->form_validation->set_rules('request-id', '', 'trim|strip_tags|integer|xss_clean');	
			$this->form_validation->set_rules('new-pass-key', '', 'trim|strip_tags|required|xss_clean');	
			$this->form_validation->set_rules('password', 'New Password', 'trim|strip_tags|required|max_length[80]|xss_clean');	

			if (!$this->form_validation->run())	
			{
				$response = $this->form_validation->invalid_response();
				echo json_encode($response);
				return;
			}
			else
			{
				$user_id = $this->input->post('user-id');
				$new_pass_key = $this->input->post('new-pass-key');
				$new_password = $this->input->post('password');
				$is_new = $this->input->post('is-new');


				if (!is_null($data = $this->tank_auth->reset_password($user_id, $new_pass_key, $new_password)))
				{
					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					if ($this->input->post('request-id'))
					{
						$request_id = $this->session->userdata('open_request_id');

						if ($request_id && $this->requests_model->open($request_id, $user_id, TRUE))
						{
							$this->session->set_userdata('open_request_id', NULL);
							$response = $this->form_validation->response(STATUS_OK, array('requestId' => $request_id));
							$this->reaction_notice->set("<b>Request made and password reset!</b><hr>We've also logged you in.", 'success', '7000');
						}
						else
						{
							$response = $this->form_validation->response();
						}
					}
					else
					{
						$response = $this->form_validation->response();						
					}

					$notification_settings = $this->tank_auth->get_userdata($user_id);
					$notification_settings = $notification_settings['notification_settings'];

					if (in_array('general_pass_changed', $notification_settings))
					{
						$this->_send_email('reset_password', $data['email'], $data);
					}

					if ($this->tank_auth->is_logged_in())
					{
						$this->session->set_flashdata('password_changed_already_logged', TRUE);
					}
					elseif ($this->tank_auth->login($data['email'], $new_password)) 
					{								
						if ($is_new)
						{
							$this->session->set_flashdata('password_created', TRUE);
						}
						else
						{
							$this->session->set_flashdata('password_changed_user_logged', TRUE);							
						}
					}
				}
				else
				{
					$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
				}
				echo json_encode($response);
			}
		}
		else
		{
			show_404();
		}
	}

	function attempt_login()
	{
		if ($this->input->is_ajax_request() && $this->form_validation->not_spam($this->input->post())) 
		{
			$this->form_validation->set_rules('email', 'Email', 'trim|strip_tags|required|valid_email|max_length[80]|callback_email_registered|xss_clean');	
			$this->form_validation->set_rules('password', 'Password', 'trim|strip_tags|required|max_length[80]|xss_clean');	
			$this->form_validation->set_rules('remember', 'Remember Me', 'integer');

			$this->form_validation->set_rules('open_requests', '', 'trim|strip_tags|integer|xss_clean');

			if (!$this->form_validation->run())	
			{
				$response = $this->form_validation->invalid_response();
			}
			else
			{
				$this->form_validation->set_rules('password', 'Password', 'callback_check_password[email]');
				
				if (!$this->form_validation->run())	
				{
					$response = $this->form_validation->invalid_response();
				}
				else
				{
					$email = $this->input->post('email');
					$password = $this->input->post('password');
					$remember = $this->input->post('remember');

					if ($this->tank_auth->login($email, $password, $remember))
					{

						if ($this->input->post('open_requests'))
						{
							$user_id = $this->session->userdata('user_id');
							$request_id = $this->session->userdata('open_request_id');

							if ($request_id && $this->requests_model->open($request_id, $user_id, TRUE))
							{
								$this->session->set_userdata('open_request_id', NULL);
								$response = $this->form_validation->response(STATUS_OK, array('requestId' => $request_id));								
								$this->reaction_notice->set("<b>Request made!</b><hr>We've also logged you in.", 'success', '5000');
							}
							else
							{
								$response = $this->form_validation->response();
							}
						}
						else
						{
							$this->reaction_notice->set_quick('<b>You\'ve logged in!</b>');
							$response = $this->form_validation->response();
							$this->session->set_flashdata('logged_in', TRUE);						
						}
					}
					else
					{
						$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
					}
				}
			}
			echo json_encode($response);
		}
		else
		{
			show_404();
		}
	} 

	function attempt_signup()
	{
		if ($this->input->is_ajax_request() 
			&& $this->form_validation->not_spam($this->input->post()))
		{
			$response = $this->tank_auth->attempt_signup();
			echo json_encode($response);
		}
		else
		{
			show_404();
		}
	} 
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */