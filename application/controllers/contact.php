<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form'));
		$this->load->library(array('security', 'form_validation'));
	}

	function index()
	{
		$data = array();
		$data['meta'] = $this->config->item('contact');

		$this->load->page('contact', $data);
	}

	function send()
	{		
		if ($this->input->is_ajax_request() && $this->form_validation->not_spam($this->input->post()))
		{
		    $this->form_validation->set_rules('email', 'Email', 'trim|strip_tags|valid_email|xss_clean');    
		    $this->form_validation->set_rules('message', 'Message', 'trim|strip_tags|required|xss_clean');    

		    if (!$this->form_validation->run())
		    {
	        	echo json_encode($this->form_validation->invalid_response());
	        	return;
		    }

			$data = array(
				'email' => $this->input->post('email'),
				'message' => $this->input->post('message')
			);

    		$email = array(
    			'to' => SITE_EMAIL,
    			'subject' => "Tutorical Message",
    			'template' => 'contact',
    			'data' => $data,
    			'priority' => IMPORTANT_EMAIL_PRIORITY
    		);

    		if ($data['email'])
    		{
    			$email['reply_to_email'] = $data['email'];
    		}

			if ($this->email->process_email($email))
//			if (TRUE)
			{
				echo json_encode($this->form_validation->response());
			}
			else
			{
				echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));				
			}
		}
		else
		{
			show_404();
		}
	}

	// Delete this function soon
	function admin()
	{
		return;

	  if ($this->input->is_ajax_request())
	  {
	  	if ($this->session->userdata('init'))
	  	{
	  		$this->load->model('admin_model');

		    $student_id = $this->session->userdata('user_id');

///		    $username = $this->input->post('username');

		    $tutor = $this->users->get_user_by_username($username);
		    $tutor_id = $tutor->id;

		    // Only handle things IF tutor is init and is not the person contacting
		    if ($tutor->init && $student_id != $tutor_id)
		    {
/*
	  			echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR, array('created' => $tutor->created)));
	  			return;
*/
			    $data = array(
			    	'contacted' => $this->admin_model->generate_datetime($tutor->created),
			    	'status' => STUDENT_STATUS_ACTIVE
			    );

		  		if ($this->tutor_model->add_student($student_id, $tutor_id, $data))
		  		{
		  			echo json_encode($this->form_validation->response());
		  			return;
		  		}
		    }

  			echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));
  			return;
	  	}
	  }

	  show_404();
	}

	// This goes here because the /tutors/$username is reserved for potential usernames
	function tutor() 
	{
	  if ($this->input->is_ajax_request() && $this->form_validation->not_spam($this->input->post()))
	  {
	    $this->form_validation->set_rules('contact-user-name', 'name', 'trim|strip_tags|required|xss_clean');    
	    $this->form_validation->set_rules('contact-tutor-email', 'Email', 'trim|strip_tags|required|valid_email|xss_clean');    
//	    $this->form_validation->set_rules('contact-tutor-phone', 'Phone Number', 'trim|strip_tags|xss_clean');    
	    $this->form_validation->set_rules('contact-tutor-message', 'Message', 'trim|strip_tags|required|xss_clean');    
	    $this->form_validation->set_rules('username', 'Username', 'trim|strip_tags|required|xss_clean');    
	    if (!$this->form_validation->run())
	    {
        	echo json_encode($this->form_validation->invalid_response());
        	return;
	    }

	    $tutor_username = $this->input->post('username');
	    
	    $tutor = $this->users->get_user_by_username($tutor_username);
	    $tutor_id = $tutor->id;
	    $student_email = $this->input->post('contact-tutor-email');
	    $student_name = $this->input->post('contact-user-name');
	    $message = $this->input->post('contact-tutor-message');
	    
	    // Check if person is contacting themselves (though could be accidental, so can't be mean)
	    if ($student_email == $tutor->email)
	    {
	    	$errors = array('contact-tutor-email' => $this->form_validation->make_error("But that's the tutor's email!"));
	    	$response = $this->form_validation->response(STATUS_VALIDATION_ERROR, array('errors' => $errors));

	    	echo json_encode($response);
	    	return;
	    }

	    // We use different name/email vars for the contact form as people might be logged into an account but use a different name or email when talking to other tutors
	    $this->session->set_userdata('contact_name', $student_name);
	    $this->session->set_userdata('contact_email', $student_email);
//	    $this->session->set_userdata('phone', $phone);

	    // Handle account

	    if ($this->session->userdata('user_id') && $this->session->userdata('email') == $student_email)	// User logged in and email entered is the user's email (means logged in user is using his own email)
	    {	
	    	$student_id = $this->session->userdata('user_id');
	    	$student_username = $this->session->userdata('username');
	    	$account_status = ACCOUNT_STATUS_LOGGED_IN;
	    }
	    else
	    {
		    $user = $this->users->get_user_by_email($student_email);

//		    var_export($student_email);
//		    var_export($user);
		    
		    if (!$user)		// No user exists, create a student account
		    {
		    	if ($data = $this->student_model->make_student_from_contact($student_email, $student_name))
		    	{
		    		$student_id = $data['user_id'];
		    		$student_username = $data['username'];

		    		$email = array(
		    			'to' => $student_email,
		    			'subject' => "Welcome to Tutorical! We've made a student account for you.",
		    			'template' => 'new_student',
						'data' => $data
		    		);

					if ($email_status = $this->email->process_email($email))
//					if (TRUE)
					{
						$this->profile_notices_model->add_notice(WELCOME_NEW_STUDENT, $student_id, 1000);

						if ($email_status == EMAIL_STATUS_SENT)
						{
							$account_status = ACCOUNT_STATUS_JUST_MADE;
						}
						else
						{
							$account_status = ACCOUNT_STATUS_JUST_MADE_EMAIL_QUEUED;
						}

					}
					else
					{
				    	$account_status = ACCOUNT_STATUS_UNKNOWN;
					}
		    	}
		    	else
		    	{
		    		echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));
		    		return;
		    	}
		    }
		    else
		    {
		    	$student_username = $user->username;
		    	$student_id = $user->id;

		    	if ($user->role == ROLE_STUDENT && !$user->activated)		// User exists but isn't activated; remind to activate (activation only relevant to students right now)
		    	{
		    		$account_status = ACCOUNT_STATUS_EXISTS_BUT_INACTIVE;
		    	}
		    	else	// Activated user but unlogged; make a mention about logging in
		    	{
		    		$account_status = ACCOUNT_STATUS_UNLOGGED_USER;
		    	}	    	
		    }
	    }

	    $email_data = array(
	    	'student_name' => $student_name,
	    	'student_email' => $student_email,
	    	'student_profile_path' => "students/$student_username",
	    	'message' => $message
	    );

    	$email = array(
    		'reply_to_name' => $student_name,
    		'reply_to_email' => $student_email,
    		'to' => $tutor->email,
    		'subject' => "A student has contacted you - $student_name",
    		'template' => 'message-for-tutor',
    		'data' => $email_data
    	);

    	$data = array(        
    		'message' => $message,
    		'contacted' => date('Y-m-d H:i:s'),
    		'status' => STUDENT_STATUS_PENDING
    	);

//    	var_export($this->email->process_email($email));

    	// This conditional is HIDEOUS. Fix it.
		if ($student_id != $tutor_id
			&& $this->tutor_model->add_student($student_id, $tutor_id, $data))
		{	

			
			$tutor_notification_settings = json_decode($tutor->userdata);
			$tutor_notification_settings = $tutor_notification_settings->notification_settings;
			if (in_array('tutor_contact', $tutor_notification_settings))
			{
				$this->email->process_email($email);
			}

		    $data = array(
		    	'accountStatus' => $account_status,
		    	'emailDomain' => end(explode('@', $student_email))
		    );

	        echo json_encode($this->form_validation->response(STATUS_OK, $data));
		}
		else
		{
	    	echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));			
		}
	  }
	  else
	  {
	        show_404();
	  }          
	}
}

/* End of file contact.php */
/* Location: ./application/controllers/contact.php */