<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller
{
	private $user_id;
	private $profile_made;
	private $has_tutors;
	private $has_students;
	private $role;
	private $nav_data;

	function __construct()
	{
		parent::__construct();

		$this->tank_auth->bounce_if_unlogged();
		$this->load->library(array('security', 'form_validation'));

		$this->role = $this->session->userdata('role');
		$this->user_id = $this->session->userdata('user_id');
		
		$this->profile_made = $this->session->userdata('account_profile_made');
		$this->has_tutors = $this->student_model->has_tutors($this->user_id);
		$this->has_students = ($this->role == ROLE_STUDENT ? FALSE : $this->tutor_model->has_students($this->user_id));

		$this->nav_data['profile_made'] = $this->profile_made;
		$this->nav_data['role'] = $this->role;
		$this->nav_data['has_tutors'] = $this->has_tutors;
		$this->nav_data['has_students'] = $this->has_students; 
		$this->nav_data['profile_made'] = $this->profile_made; 
	}

	// Index represents the dashboard
	function index()
	{
        $data['meta'] = $this->config->item('account-dashboard');

        $data['profile_link'] = "http://tutorical.com/tutors/".$this->session->userdata('username');

		// Handle profile notices
		$this->load->model('profile_notices_model');
		$data['profile_notices'] = $this->profile_notices_model->get_all_notices($this->user_id);
		$count = count($data['profile_notices']);

		for($i = 0; $i < $count; $i++)
		{
			$notice = $data['profile_notices'][$i];
			if ($notice['type'] == 'urgent')
				$meta = 'urgent';
/*			if ($notice['is_sticky'])
				$meta = 'sticky';
*/			elseif ($notice['is_new'])
				$meta = 'new';
			else
				$meta = $notice['type'];
			$data['profile_notices'][$i]['meta'] = $meta;
		}

		$this->profile_notices_model->new_to_old($this->user_id);
 
		if ($this->session->flashdata('profile_already_made'))
		{
			$data['profile_already_made'] = TRUE;
		}
		if ($this->session->flashdata('password_changed_user_logged'))
		{
			$data['password_changed_user_logged'] = TRUE;
		}
		elseif ($this->session->flashdata('password_created'))
		{
			$data['password_created'] = TRUE;
		}
		elseif ($this->session->flashdata('password_changed_already_logged'))
		{
			$data['password_changed_already_logged'] = TRUE;
		}
		// Password reset should trump logged in
		elseif ($this->session->flashdata('just_registered'))
		{
			$data['just_registered'] = TRUE;
		}
		// we only want logged in to show if user did not just register
		elseif ($this->session->flashdata('logged_in'))
		{
			$data['just_logged_in'] = TRUE;
		}		

		if ($this->session->flashdata('profile_not_made'))
		{
			$data['profile_not_made'] = TRUE;
		}
 	
		if ($this->session->flashdata('already_logged_in'))
		{
			$data['already_logged_in'] = TRUE;
		}
 	
		$this->nav_data['active'] = 'dashboard';
		$data['account_nav'] = $this->load->view('components/account_nav', $this->nav_data, TRUE);

		$userdata = $this->session->userdata('userdata');
		if (isset($userdata['hidden_dashboard_requests']))
		{
			$hidden_dashboard_requests = $userdata['hidden_dashboard_requests'];
		}
		else
		{
			$hidden_dashboard_requests = NULL;
		}

		$this->load->model('find_model');
		$args = $this->profile_model->get_profile_location($this->user_id);

		// If no location -> profile not yet made
		if (!$args['lat'])
		{
			$data['location_set'] = FALSE;
		}
		else
		{
			$data['location_set'] = TRUE;
			
			$args['limit_count'] = 4;
			$args['sort'] = 'new';
			$args['include_logged_user'] = FALSE;
			$args['exclude_ids'] = $hidden_dashboard_requests;

	//		var_dump($args);

			$data['requests'] = array(
				'local' => $this->find_model->find('local', 'requests', $args),
	//			'distance' => $this->find_model->find('distance', 'requests', $args),
				'more_local' => FALSE,
	//			'more_distance' => FALSE,
				'location' => array(
					'city' => $args['city'],
					'country' => $args['country']
				)
			);

//			var_dump($data['requests']);

			// This is a hack. We want to display "See more" if more than x requests are nearby. So, we get the newest x+ requests. If we get a full x+1, then we have more than x. In this case, we show the "See more". This avoids a second SQL call to count the results. We also remove the last item to ensure that only the x requests are shown.
			if ($data['requests']['local']['original_page_count'] == $args['limit_count'])
			{
				$data['requests']['more_local'] = TRUE;

				// The original page count doesn't include the count AFTER we remove the applied-to requests. For those, we check a direct count()
				if (count($data['requests']['local']['items']) == $args['limit_count'])
				{
					array_pop($data['requests']['local']['items']);
				}
			}
			/*
			if (count($data['requests']['distance']['items']) == $args['limit_count'])
			{
				$data['requests']['more_distance'] = TRUE;
				array_pop($data['requests']['distance']['items']);
			}
			*/
		}



//		var_dump($data['requests']['local']['items']); return;

		$this->load->page('account/dashboard', $data);
	}

	// AJAX function
	function hide_request_from_dashboard()
	{
		if (!$this->input->is_ajax_request())
		{
			show_404();
		}

		$this->form_validation->set_rules('id', '', 'trim|strip_tags|required|xss_clean');	

		if (!$this->form_validation->run())	
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}

		$request_id = $this->input->post('id');
		
		if ($this->requests_model->hide_requests_from_dashboard($request_id))
		{
			$response = $this->form_validation->response();
		}
		else
		{
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
		}

		echo json_encode($response);
	}

	function admin()
	{
		$this->load->model('admin_model');

		if ($this->session->userdata('init') != TRUE)
		{
			show_404();
			return;
		}

		// We can't use form_validation since the attempt_signup function uses it

		// Check if new admin made
		$first_name = $this->input->post('admin-first-name');
		$last_name = $this->input->post('admin-last-name');

		if ($first_name && $last_name) 
		{
			$role = ($this->input->post('role') == 'Tutor' ? ROLE_ADMIN : ROLE_STUDENT);
			$admin = $this->admin_model->make_admin($this->input->post('admin-first-name'), $this->input->post('admin-last-name'), $role);

			$this->tank_auth->logout();
			$this->tank_auth->login($admin['username'], $admin['unhashed_password']);

			$redirect = $this->session->userdata('prefs');
			$redirect = $redirect['redirect']; 
			redirect($redirect);
		}
		
		// Nothing new made, just show the page
	    $data['meta'] = $this->config->item('account-admin');
        $data['admin'] = $this->admin_model->get_admin_data();

//        var_dump($data['admin']);
        $data['subject_categories'] = $this->subjects_model->get_subject_categories();

		$this->nav_data['active'] = 'admin';
		$data['account_nav'] = $this->load->view('components/account_nav', $this->nav_data, TRUE);

		$this->load->page('account/admin', $data);
	}

	function profile()
	{
		$this->load->model('profile_model');

		if ($this->session->flashdata('profile_just_made'))
		{
			$data['profile_just_made'] = TRUE;
		}

        $data['meta'] = $this->config->item('account-profile');

		$this->nav_data['active'] = 'profile';
		$data['account_nav'] = $this->load->view('components/account_nav', $this->nav_data, TRUE);

		$data['user'] = $this->profile_model->get_profile($this->user_id);

		if ($data['user']['role'] != ROLE_STUDENT)
			$data['currency_sign'] = get_currency_sign($data['user']['currency']);

		$this->load->page('account/profile', $data);

	}

	function settings()
	{
        $data['meta'] = $this->config->item('account-settings');

        $userdata = $this->session->userdata('userdata');
        $data['notification_settings'] = $userdata['notification_settings'];

		$this->nav_data['active'] = 'settings';

		$data['account_nav'] = $this->load->view('components/account_nav', $this->nav_data, TRUE);
		$this->load->page('account/settings', $data);
	}

	function correct_password($password)
	{
		if (!$this->tank_auth->check_password($password, $this->session->userdata('user_id'))) 
		{
			$this->form_validation->set_message('correct_password', 'Sorry, this password is incorrect.');

			return FALSE;
		}
		else
		{
			return TRUE;
		}
	} 

	function email_not_same($email)
	{
		if ($email == $this->session->userdata('email'))
		{
			$this->form_validation->set_message('email_not_same', "But that's your current email!");
			return FALSE;
		}
		return TRUE;
	} 

	/* AJAX function */

	function change_role()
	{
		if ($this->input->is_ajax_request())
		{
			$this->form_validation->set_rules('confirm-password', 'the Password', 'trim|strip_tags|required|xss_clean|callback_correct_password');

			if (!$this->form_validation->run()) {
				echo json_encode($this->form_validation->invalid_response());
				return;
			}
/*
			$this->form_validation->set_rules('role', 'Role', 'trim|strip_tags|required|xss_clean');

			if (!$this->form_validation->run()) {
				echo json_encode($this->form_validation->invalid_response());
				return;
			}

			$role = $this->input->post('role');
*/
			$role = ROLE_TUTOR;

			if (!$this->session->userdata('user_id') || $this->role == ROLE_TUTOR)
			{
				echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));
				return;								
			}

			if ($data = $this->users->change_role($this->user_id, $role))
			{
				$this->session->set_userdata('role', $role);

				$this->reaction_notice->set("<b>You're now a Tutorical tutor!</b><br>Let's start by ".anchor('account/profile','making your profile').".", 'success', 0);

				$this->profile_notices_model->delete_notice(WELCOME_NEW_STUDENT, $this->user_id);
				$this->profile_notices_model->add_notice(MAKE_PROFILE, $this->user_id, 1000);

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


	/* AJAX function */

	function edit_notification_settings()
	{
		if (!$this->input->is_ajax_request())
		{
			show_404();
		}

		$notification_settings = (array) $this->input->post('email-notifications');

		$userdata = $this->session->userdata('userdata');
		$userdata['notification_settings'] = $notification_settings;

		if ($this->tank_auth->set_userdata($userdata))
			$response = $this->form_validation->response();
		else
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);

		echo json_encode($response);
	}


	/* AJAX function */

	function change_email()
	{
		if ($this->input->is_ajax_request())
		{
			$this->form_validation->set_rules('confirm-password', 'the Password', 'trim|strip_tags|required|xss_clean|callback_correct_password');

			if (!$this->form_validation->run()) {
				echo json_encode($this->form_validation->invalid_response());
				return;
			}

			$this->form_validation->set_rules('email', 'Email', 'trim|strip_tags|required|xss_clean|valid_email|callback_email_not_registered|callback_email_not_same');

			if (!$this->form_validation->run()) {
				echo json_encode($this->form_validation->invalid_response());
				return;
			}

			if (!$this->session->userdata('user_id'))
			{
				echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));
				return;								
			}
			
			$new_email = $this->input->post('email');

			if ($data = $this->tank_auth->change_email($new_email))
			{
				$old_email = $this->session->userdata('email');
				$this->session->set_userdata('email', $new_email);

				echo json_encode($this->form_validation->response(STATUS_OK, array('old-email' => $old_email, 'new-email' => $new_email)));
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

	function send_password_changed_email()
	{
		if ($this->input->is_ajax_request())
		{
			$to = $this->session->userdata('email');

			$notification_settings = $this->session->userdata('userdata');
			$notification_settings = $notification_settings['notification_settings'];

			if (in_array('general_pass_changed', $notification_settings))
			{
				$email = array(
					'to' => $to,
					'subject' => "Your Tutorical password has been changed",
					'template' => 'reset_password'
				);
				$this->email->process_email($email);
			}
		}
		else
		{
			show_404();
		}
	}

	function send_email_change_emails()
	{
		if ($this->input->is_ajax_request())
		{
			$notification_settings = $this->session->userdata('userdata');
			$notification_settings = $notification_settings['notification_settings'];

			if (!in_array('general_email_changed', $notification_settings))
			{
				return;
			}

			if (($old_email = $this->input->post('old-email'))
				&& ($new_email = $this->input->post('new-email')))
			{
			
				$data = array(
					'new_email' => $new_email,
					'old_email' => $old_email
				);

				$emails = array(
					array(
						'to' => $old_email,
						'subject' => "Your Tutorical account's login/email has been changed",
						'template' => 'email-changed-from',
						'data' => $data
					),
					array(
						'to' => $new_email,
						'subject' => "This is your Tutorical account's new login/email",
						'template' => 'email-changed-to',
						'data' => $data
					)
				);
				$this->email->process_emails($emails);
			}
		}
		else
		{
			show_404();
		}
	}

	function email_not_registered($email)
	{
		if ($email == $this->session->userdata('email'))
		{
			$this->form_validation->set_message('email_not_registered', 'That\'s your current email!');
			return FALSE;
		}
		if ($this->tank_auth->is_email_available($email))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('email_not_registered', 'Sorry, That email is taken.');
			return FALSE;
		}
	} 

	/* AJAX function */

	function change_password()
	{
		if ($this->input->is_ajax_request())
		{
			$this->form_validation->set_rules('confirm-password', 'the Password', 'trim|strip_tags|required|xss_clean|callback_correct_password');

			if (!$this->form_validation->run()) 
			{
				echo json_encode($this->form_validation->invalid_response());
				return;
			}

			$this->form_validation->set_rules('password', 'the Password', 'trim|strip_tags|required|xss_clean|max_length[80]');

			if (!$this->form_validation->run()) 
			{
				echo json_encode($this->form_validation->invalid_response());
				return;
			}

			if (!$this->session->userdata('user_id'))
			{
				echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));
				return;								
			}
			
			$password = $this->input->post('confirm-password');
			$new_password = $this->input->post('password');

			if ($this->tank_auth->change_password($password, $new_password))
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

	function correct_delete_text($delete_text)
	{
		if (mb_strtolower($delete_text, 'UTF-8') == mb_strtolower(DELETE_TEXT, 'UTF-8'))
		{
			return TRUE;
		}

		$this->form_validation->set_message('correct_delete_text', "Please type 'goodbye' to delete your account.");
		return FALSE;
	}

	/* AJAX function */

	function delete()
	{
		if ($this->input->is_ajax_request())
		{
			$this->form_validation->set_rules('confirm-password', 'the Password', 'trim|strip_tags|required|xss_clean|callback_correct_password');

			if (!$this->form_validation->run()) 
			{
				echo json_encode($this->form_validation->invalid_response());
				return;
			}

			$this->form_validation->set_rules('delete-account-text', 'Delete Text', 'trim|strip_tags|callback_correct_delete_text|xss_clean');

			if (!$this->form_validation->run()) 
			{
//				echo json_encode($this->form_validation->response(STATUS_VALIDATION_ERROR, array('delete-text' => $this->input->post('delete-account-text'))));
				echo json_encode($this->form_validation->invalid_response());
				return;
			}

			$password = $this->input->post('confirm-password');

//			if (TRUE)
			if ($this->tank_auth->delete_user($password))
			{
				echo json_encode($this->form_validation->response());
				return;
			}
		}
		else 
		{
			show_404();
		}
	}

	function students()
	{
		if ($this->role == ROLE_STUDENT)
		{
			$this->reaction_notice->set('Sorry, only tutors can see that page.', 'warning', 4000);
			redirect('account');
		}
/*
		elseif (!$this->has_students)
		{
			$this->reaction_notice->set('That page will become available when a student contacts you through your profile.', 'warning', 7000);
			redirect('account');
		}
*/ 

		// Use this because worked_with might have been changed by other person
		$userdata = $this->tank_auth->get_userdata($this->user_id);
//		var_dump($userdata);

		$data['worked_with_ids'] = $userdata['worked_with_ids'];

		$data['contacts'] = array();	
        $contact_types = array(
        	'pending' => STUDENT_STATUS_PENDING,
        	'active' => STUDENT_STATUS_ACTIVE,
        	'past' => STUDENT_STATUS_PAST
        );

        $show_hidden = $this->input->get('hidden');
        if ($show_hidden == 'show')
        {
        	$data['show_hidden'] = TRUE;
	        $hide_array = NULL;
        }
        else
        {
        	$data['show_hidden'] = FALSE;
        	$hide_array = $userdata['hidden_past_student_ids'];        	
        }

//        var_dump($userdata['worked_with_ids']);

//        var_dump($hide_array);

        if ($userdata['hidden_past_student_ids'])
        {
        	$data['has_hidden'] = TRUE;        	
        }
        else
        {
        	$data['has_hidden'] = FALSE;
        }

        foreach($contact_types as $type => $status)
        {
        	$students = $this->tutor_model->get_contacts('student', $this->user_id, $status, $hide_array);

        	foreach($students as &$student)
        	{
        		$student['type'] = $type;

        		if (in_array($student['id'], $userdata['hidden_past_student_ids']))
        			$student['hidden'] = TRUE;
        		else
        			$student['hidden'] = FALSE;

	        	$data['contacts'][] = $student;
        	}
        	unset($tutor);
        }
        $data['contact_count'] = count($data['contacts']);

        $data['meta'] = $this->config->item('account-students');

		$this->nav_data['active'] = 'students';
		$data['account_nav'] = $this->load->view('components/account_nav', $this->nav_data, TRUE);

		$this->load->page('account/students', $data);
	}

	function requests()
	{
        $data['meta'] = $this->config->item('account-requests');

        $show_hidden = $this->input->get('hidden');
        if ($show_hidden == 'show')
        	$show_hidden = TRUE;
        else
        	$show_hidden = FALSE;

        $data['requests'] = array(
        	'users' => $this->requests_model->get_short_requests($this->user_id, array(REQUEST_STATUS_OPEN, REQUEST_STATUS_CLOSED), $show_hidden),	
        	'others' => $this->requests_model->get_tutors_requests($this->user_id, array(RESPONSE_STATUS_PENDING, RESPONSE_STATUS_APPROVED, RESPONSE_STATUS_REJECTED), FALSE, $show_hidden),
        	'invited' => $this->requests_model->get_tutors_requests($this->user_id, array(RESPONSE_STATUS_INVITED), TRUE, $show_hidden),
        	'has_hidden' => $this->requests_model->has_hidden_requests_or_applications($this->user_id)
        );

        // Remove invited requests that are not open
        foreach($data['requests']['invited'] as $key => $request)
        {
        	if ($request['status'] != REQUEST_STATUS_OPEN)
        	{
        		unset($data['requests']['invited'][$key]);
        	}
        }

		$this->nav_data['active'] = 'requests';
		$data['account_nav'] = $this->load->view('components/account_nav', $this->nav_data, TRUE);

		$this->load->page('account/requests', $data);
	}

	function update_tutors_student_status()
	{
		if (!($this->input->is_ajax_request()
			&& ($student_id = $this->input->post('student_id'))
			&& ($status = $this->input->post('status'))))
		{
			show_404();
		}

//		var_dump($status);

		$response = $this->tutor_model->update_tutors_student_status($student_id, $this->user_id, $status);
		echo json_encode($response);
	}


	function update_students_tutor_status()
	{
		if (!($this->input->is_ajax_request()
			&& ($tutor_id = $this->input->post('tutor_id'))
			&& ($status = $this->input->post('status'))))
		{
//			echo json_encode($this->input->post());
			show_404();
		}

		$response = $this->tutor_model->update_students_tutor_status($tutor_id, $this->user_id, $status);
		echo json_encode($response);
	}

	function tutors()
	{
/*
		if (!$this->has_tutors)
		{
			$this->reaction_notice->set('That page will become available when you contact a tutor through their profile.', 'warning', 7000);
			redirect('account');
		}
*/
		// Use this because worked_with might have been changed by other person
		$userdata = $this->tank_auth->get_userdata($this->user_id);

		$favourited_ids = $userdata['favourited_tutor_ids'];
		$data['favourited'] = $this->tutor_model->get_featured_tutor_details($favourited_ids);

		$data['worked_with_ids'] = $userdata['worked_with_ids'];
	
        $data['contacts'] = array();
        $contact_types = array(
        	'pending' => STUDENT_STATUS_PENDING,
        	'active' => STUDENT_STATUS_ACTIVE,
        	'past' => STUDENT_STATUS_PAST
        );

        $show_hidden = $this->input->get('hidden');
        if ($show_hidden == 'show')
        {
        	$data['show_hidden'] = TRUE;
	        $hide_array = NULL;
        }
        else
        {
        	$data['show_hidden'] = FALSE;
        	$hide_array = $userdata['hidden_past_tutor_ids'];        	
        }

//        var_dump($userdata['worked_with_ids']);

        if ($userdata['hidden_past_tutor_ids'])
        {
        	$data['has_hidden'] = TRUE;        	
        }
        else
        {
        	$data['has_hidden'] = FALSE;
        }

        foreach($contact_types as $type => $status)
        {
        	$tutors = $this->tutor_model->get_contacts('tutor', $this->user_id, $status, $hide_array);

//        	var_dump($tutors);

        	foreach($tutors as &$tutor)
        	{
        		$tutor['type'] = $type;

        		if (in_array($tutor['id'], $userdata['hidden_past_tutor_ids']))
        			$tutor['hidden'] = TRUE;
        		else
        			$tutor['hidden'] = FALSE;

	        	$data['contacts'][] = $tutor;
        	}
        	unset($tutor);
        }
        $data['contact_count'] = count($data['contacts']);

        $data['meta'] = $this->config->item('account-tutors');

		$this->nav_data['active'] = 'tutors';

		$data['account_nav'] = $this->load->view('components/account_nav', $this->nav_data, TRUE);
		$this->load->page('account/tutors', $data);
	}

	function update_review()
	{
		if (!($this->input->is_ajax_request()
			&& ($tutor_id = $this->input->post('tutor-id'))
			))
		{
			show_404();
		}

		// We make a special exception for tutor-id because an error message for this will mean nothing; this being not an int means (a) it was modified via inspector or (b) a freak error
		if (!is_numeric($this->input->post('tutor-id')))
		{
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
			echo json_encode($response);
			return;
		}

		$this->form_validation->set_rules('rating', 'the Star Rating', 'trim|strip_tags|required|less_than[6]|greater_than[0]|xss_clean');
		$this->form_validation->set_rules('expertise', 'Expertise', 'trim|strip_tags|required|less_than[6]|greater_than[0]|xss_clean');
		$this->form_validation->set_rules('helpfulness', 'Helpfulness', 'trim|strip_tags|required|less_than[6]|greater_than[0]|xss_clean');
		$this->form_validation->set_rules('response', 'Response', 'trim|strip_tags|required|less_than[6]|greater_than[0]|xss_clean');
		$this->form_validation->set_rules('clarity', 'Clarity', 'trim|strip_tags|required|less_than[6]|greater_than[0]|xss_clean');
		$this->form_validation->set_rules('content', 'the Description', 'trim|strip_tags|required|xss_clean');

	    if (!$this->form_validation->run())
	    {
        	$response = $this->form_validation->invalid_response();
	    }
	    else
	    {
//			$response = $this->form_validation->response();
			$response = $this->student_model->update_review($tutor_id, $this->user_id, $this->input->post());
	    }

		echo json_encode($response);
	}

	function delete_review()
	{
		if (!($this->input->is_ajax_request()
			&& ($tutor_id = $this->input->post('tutor-id'))
			))
		{
			show_404();
		}

		// We make a special exception for tutor-id because an error message for this will mean nothing; this being not an int means (a) it was modified via inspector or (b) a freak error
		if (!is_numeric($this->input->post('tutor-id')))
		{
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
			echo json_encode($response);
			return;
		}

		$response = $this->student_model->delete_review($tutor_id, $this->user_id);

		echo json_encode($response);
	}


	function show_hide_tutor()
	{
		if (!$this->input->is_ajax_request())
		{
			show_404();
		}

		$this->form_validation->set_rules('id', '', 'trim|strip_tags|required|xss_clean');	
		$this->form_validation->set_rules('action', 'Action', 'trim|strip_tags|xss_clean');	

		if (!$this->form_validation->run())	
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}

		$tutor_id = $this->input->post('id');
		$student_id = $this->user_id;
		$action = $this->input->post('action');

		if ($action == 'show')
		{
			$hide = FALSE;
		}
		else
		{
			$hide = TRUE;
		}

		$this->tutor_model->show_hide_tutor($tutor_id, $student_id, $hide);
	}

	function show_hide_student()
	{
		if (!$this->input->is_ajax_request())
		{
			show_404();
		}

		$this->form_validation->set_rules('id', '', 'trim|strip_tags|required|xss_clean');	
		$this->form_validation->set_rules('action', 'Action', 'trim|strip_tags|xss_clean');	

		if (!$this->form_validation->run())	
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}

		$student_id = $this->input->post('id');
		$tutor_id = $this->user_id;
		$action = $this->input->post('action');

		if ($action == 'show')
		{
			$hide = FALSE;
		}
		else
		{
			$hide = TRUE;
		}

		$this->student_model->show_hide_student($student_id, $tutor_id, $hide);
	}

	function send_welcome_if_needed()
	{
		$user_id = $this->session->userdata('user_id');
		if ($this->input->is_ajax_request() 
			&& !$this->users->was_user_welcomed($user_id)
			&& !$this->session->userdata('init'))
		{
			$email = array(
    			'to' => $this->session->userdata('email'),
    			'subject' => "Welcome to Tutorical!",
    			'template' => 'welcome',
    			'priority' => IMPORTANT_EMAIL_PRIORITY
    		);

			if ($this->email->process_email($email))
			{
				$this->users->set_user_to_welcomed($user_id);
			}
		}
	}

	function _bounce_if_profile_not_made() 
	{
		
		if (!$this->profile_made) {
			$this->session->set_flashdata('profile_not_made', TRUE);
			redirect('account');
		}
	}

	function delete_profile_notice()
	{
		if (!($this->input->is_ajax_request()))
		{
			show_404();
		}

		$this->form_validation->set_rules('tpn-id', '', 'trim|integer|strip_tags|required|xss_clean');

		if (!$this->form_validation->run())
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}

		$tpn_id = $this->input->post('tpn-id');

		if ($this->profile_notices_model->confirm_ownership($tpn_id, $this->user_id)
			&& $this->profile_notices_model->delete_notice_by_id($tpn_id))
		{
			$response = $this->form_validation->response();
		}
		else
		{
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
		}

		echo json_encode($response);
	}

	function save_student_notes()
	{
		if (!($this->input->is_ajax_request()))
		{
			show_404();
		}

		$this->form_validation->set_rules('student-id', '', 'trim|integer|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('student-notes', '', 'trim|strip_tags|xss_clean');


		if (!$this->form_validation->run())
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}

		$student_id = $this->input->post('student-id');
		$tutor_id = $this->session->userdata('user_id');
		$student_notes = $this->input->post('student-notes');

		if ($this->tutor_model->save_student_notes($student_id, $tutor_id, $student_notes))
		{
			$response = $this->form_validation->response();
		}
		else
		{
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
		}

		echo json_encode($response);
	}

	function save_tutor_notes()
	{
		if (!($this->input->is_ajax_request()))
		{
			show_404();
		}

		$this->form_validation->set_rules('tutor-id', '', 'trim|integer|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('tutor-notes', '', 'trim|strip_tags|xss_clean');


		if (!$this->form_validation->run())
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}

		$tutor_id = $this->input->post('tutor-id');
		$student_id = $this->session->userdata('user_id');
		$tutor_notes = $this->input->post('tutor-notes');

		if ($this->student_model->save_tutor_notes($tutor_id, $student_id, $tutor_notes))
		{
			$response = $this->form_validation->response();
		}
		else
		{
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
		}

		echo json_encode($response);
	}

	function marketing()
	{
//		var_dump($this->session->all_userdata());

		if ($this->role == ROLE_STUDENT)
		{
			$this->reaction_notice->set('Sorry, only tutors can see that page.', 'warning', 4000);
			redirect('account');
		}
		elseif (!$this->profile_made)
		{
			$this->reaction_notice->set('Please finish making your profile before viewing that page.', 'warning', 5000);
			redirect('account/profile');
		}

        $data['meta'] = $this->config->item('account-marketing');

        $data['username'] = $this->session->userdata('username');
        $data['profile_link'] = "http://tutorical.com/tutors/".$data['username'];

		$this->nav_data['active'] = 'marketing';
		$data['account_nav'] = $this->load->view('components/account_nav', $this->nav_data, TRUE);

		// Get classifieds templates
		$username = $this->session->userdata('username');
		$classifieds_data = array();
		$classifieds_data['tutor'] = $this->tutor_model->get_tutor($username, 'username', array('usage' => 'classifieds'));
		$classifieds_data['currency_sign'] = get_currency_sign($classifieds_data['tutor']['currency']);

		$data['classifieds'] = array(
			'craigslist' => trim(remove_tabs_and_new_lines($this->load->view("classifieds-templates/craigslist", $classifieds_data, TRUE))),
			'kijiji' => trim(remove_tabs_and_new_lines($this->load->view("classifieds-templates/kijiji", $classifieds_data, TRUE)))
		);
		$post_title = $classifieds_data['tutor']['display_name'].' - Tutor in '.$classifieds_data['tutor']['city'].', '.$classifieds_data['tutor']['country'];

		$data['classifieds']['post_title'] = $post_title;
		$data['classifieds']['post_location'] = $classifieds_data['tutor']['location'];

//		echo '<pre>'.$data['classifieds']['craigslist'].'</pre>';
//		return;

		$this->load->page('account/marketing', $data);
	}

	function classified_templates($type, $username)
	{
		if ($this->session->userdata('init') != TRUE)
		{
			show_404();
			return;
		}

		$username = str_replace('_', '-', $username);

		$data = array();

		$data['tutor'] = $this->tutor_model->get_tutor($username, 'username', array('usage' => 'classifieds'));

		if (!$data['tutor'])
		{
		      show_404();
		}

		$data['currency_sign'] = get_currency_sign($data['tutor']['currency']);

		$this->load->view("classifieds-templates/$type", $data);
	}

	function update_favourites()
	{
/*		$response = $this->form_validation->response();
		echo json_encode($response);
		return;
*/

		if (!$this->input->is_ajax_request())
		{
			show_404();
		}

		$this->form_validation->set_rules('tutor_id', '', 'trim|strip_tags|integer|required');
		$this->form_validation->set_rules('favourite', '', 'trim|strip_tags|integer|required');

		if (!$this->form_validation->run()) 
		{
			$response = $this->form_validation->invalid_response();
			echo json_encode($response);
			return;
		}

		$tutor_id = $this->input->post('tutor_id');
		$favourite = $this->input->post('favourite');

		$this->load->model('tutor_model');

		if ($this->tutor_model->update_favourites($tutor_id, $favourite))
		{
			$response = $this->form_validation->response();
		}
		else
		{
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
		}

		echo json_encode($response);
	}
}

/* End of file account.php */
/* Location: ./application/controllers/account.php */