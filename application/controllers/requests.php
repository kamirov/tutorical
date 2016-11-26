<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Requests extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form'));
		$this->load->library(array('security', 'form_validation'));
		$this->load->model('requests_model');
	}

	function index($request_id = NULL, $apply = NULL)
	{
		if (!$request_id)
		{
			show_404();
		}

		$data['request'] = $this->requests_model->get_request($request_id);

		if (!$data['request'])
			show_404();

//		return;

		$data['apply'] = $apply;

		$data['currency_sign'] = get_currency_sign($data['request']['currency']);
		$user_id = $this->session->userdata('user_id');
		
		if (!$user_id)		// Not logged in
		{
			$data['visitor_role'] = 'visitor-guest';
		}
		elseif ($user_id == $data['request']['user_id'])	// Is the student who posted the request
		{
			$data['visitor_role'] = 'visitor-poster';
		}
		elseif ($this->session->userdata('role') == ROLE_STUDENT)	// Is a student (can't apply to request)
		{
			$data['visitor_role'] = 'visitor-student';			
		}
		elseif ($application = $this->requests_model->get_tutor_application($request_id, $user_id))		// Is tutor that has applied
		{
			$data['visitor_role'] = 'visitor-tutor-applied';
			$data['current_user_application'] = $application;
		}
		else 		// Is tutor that has not applied
		{
			$data['visitor_role'] = 'visitor-tutor';
		}
/*
		var_dump($data['request']);
		return;
*/
        if ($this->session->flashdata('previous_page')) 
        {
              $data['previous_page'] = $this->session->flashdata('previous_page');
        }

        if ($data['visitor_role'] == 'visitor-tutor-applied'
        	&& $application['status'] == RESPONSE_STATUS_REJECTED)
        {
        	$data['rejected'] = TRUE;
        }
        else
        {
        	$data['rejected'] = FALSE;	
        }

		$data['meta'] = $this->config->item('requests');
        $data['meta']['title'] = str_replace('{SUBJECT}', $data['request']['subjects_string'], $data['meta']['title']);
        $data['meta']['title'] = str_replace('{CITY}', $data['request']['location_city'], $data['meta']['title']);
        $data['meta']['title'] = str_replace('{COUNTRY}', $data['request']['location_country'], $data['meta']['title']);
        $data['meta']['title'] = str_replace('{DETAILS}', $data['request']['details'].', '.$data['request']['details'], $data['meta']['title']);

        $data['meta']['description'] = $data['request']['details'];

        $data['end_of_page_divs'] = $this->load->view('components/requests/reject-dropdown', FALSE);

		$this->load->page('requests/regular', $data);
	}

	function close($request_id)
	{
		if ($this->_toggle($request_id, 'close'))
			$this->reaction_notice->set_quick('Your request is <b>closed</b>.', 'info');

		redirect("requests/$request_id");
	}

	function open($request_id)
	{
		if ($this->_toggle($request_id, 'open'))
			$this->reaction_notice->set_quick('Your request is <b>open</b>.');

		redirect("requests/$request_id");
	}

	function _request_toggle_check($request_id)
	{
		$user_id = $this->session->userdata('user_id');

		if (!$user_id)
		{
			$this->tank_auth->bounce_if_unlogged();
		}

		if (!$request_id)
		{
			show_404();
		}

		return $user_id;
	}

	function _toggle($request_id, $action = 'close')
	{
		$user_id = $this->_request_toggle_check($request_id);

		if ($this->requests_model->toggle_request($request_id, $user_id, $action))
		{
 			$this->requests_model->requests_model->update_session_requests($user_id);
 			return TRUE;
		}
		// Otherwise either DB problem or request doesn't belong to logged-in user
	}

	function accept()
	{
		if (!$this->input->is_ajax_request())
		{
			show_404();			
		}

		$this->form_validation->set_rules('request-id', '', 'trim|integer|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('application-id', '', 'trim|integer|strip_tags|required|xss_clean');

		if (!$this->form_validation->run())
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}
	
		$application_id = $this->input->post('application-id');
		$student_id = $this->session->userdata('user_id');
		$request_id = $this->input->post('request-id');

		if ($this->requests_model->confirm_ownership($request_id, $student_id)
			&& $this->requests_model->accept($request_id, $application_id, $student_id))
		{
			$this->reaction_notice->set("<b>You've accepted a tutor!</b><hr>To see, contact, and review them, visit your ".anchor('account/tutors', 'Tutors page').".", "success", 0);
			$response = $this->form_validation->response();
		}
		else
		{
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
		}

		echo json_encode($response);
	}

	function reject()
	{
/*
		$response = $this->form_validation->response();
		echo json_encode($response);
		return;
*/		
		if (!$this->input->is_ajax_request())
		{
			show_404();			
		}

		$this->form_validation->set_rules('request-id', '', 'trim|integer|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('application-id', '', 'trim|integer|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('message', 'Reason for Rejection', 'trim|strip_tags|required|xss_clean');

		if (!$this->form_validation->run())
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}

		$application_id = $this->input->post('application-id');
		$student_id = $this->session->userdata('user_id');
		$request_id = $this->input->post('request-id');
		$message = $this->input->post('message');

		if ($this->requests_model->confirm_ownership($request_id, $student_id)
			&& $this->requests_model->reject($request_id, $application_id, $message))
		{
			$response = $this->form_validation->response();

			// Send invite profile notice
			// Later, also send an email
		}
		else
		{
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
		}

		echo json_encode($response);
	}

	function invite()
	{
		if (!$this->input->is_ajax_request())
		{
			show_404();			
		}

		$this->form_validation->set_rules('username', '', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('request-id', '', 'trim|integer|strip_tags|required|xss_clean');

		if (!$this->form_validation->run())
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}

		$tutor = $this->users->get_user_by_username($this->input->post('username'));
		$tutor_id = $tutor->id;

		$student_id = $this->session->userdata('user_id');
		$request_id = $this->input->post('request-id');

		if ($this->requests_model->confirm_ownership($request_id, $student_id)
			&& $this->requests_model->invite($request_id, $tutor_id))
		{
			$response = $this->form_validation->response();
		}
		else
		{
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);			
		}

		echo json_encode($response);
	}

	function apply()
	{
		if (!($this->form_validation->not_spam($this->input->post()) && $this->input->is_ajax_request()))
		{
			show_404();			
		}

		$user_id = $this->session->userdata('user_id');
		$request_id = $this->input->post('request-id');

		if (!$user_id || !$request_id)
		{
			show_404();
		}

		$this->form_validation->set_rules('message', 'Message', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('request-id', '', 'trim|integer|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('hourly-rate', 'Proposed Hourly Price', 'trim|strip_tags|is_money|required|xss_clean');

		$valid = $this->form_validation->run();

	    $errors = array();
		$hourly_rate = $this->input->post('hourly-rate');

	    $hourly_rate = preg_replace("/[^0-9.]/", "", $hourly_rate);

	    if (!$hourly_rate)
	    	$hourly_rate = 0;

		if ($hourly_rate > 99999)
    		$errors = array_merge($errors, array('hourly-rate' => $this->form_validation->make_error("Please enter an Hourly Rate less than 99,999")));
		elseif ($hourly_rate <= 0)
    		$errors = array_merge($errors, array('hourly-rate' => $this->form_validation->make_error("Please enter an Hourly Rate greater than 0")));

		if (!empty($errors))
		{
			echo json_encode($this->form_validation->response(STATUS_VALIDATION_ERROR, array('errors' => $errors)));
    		return;
		}
		elseif (!$valid)	
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}

		$post = $this->input->post();

		$data = array(
			'request_id' => $request_id,
			'tutor_id' => $user_id,
			'message' => $post['message'],
			'price' => $hourly_rate,
			'status' => RESPONSE_STATUS_PENDING
		);
/*
		if ($this->session->userdata('init'))
		{
			$this->load->model('admin_model');

			$user = $this->users->get_user_by_id($user_id);
			$request = $this->requests_model->get_request($request_id);
			$posted = date('Y-m-d H:i:s', $request['posted']);
			$min_possible_request_date = '2013-04-10 00:00:00';
			$datetime_min = max($user->created, $posted, $min_possible_request_date);

//			var_export($posted);
//			var_export($user->created);

//			return;
			
			$data['posted'] = $this->admin_model->generate_datetime($datetime_min);
		}
*/
//		var_export($data);

		$response = $this->requests_model->apply($data);
		echo json_encode($response);

	}

	function delete_application()
	{
		$user_id = $this->session->userdata('user_id');
		$request_id = $this->input->post('request-id');
		$application = $this->requests_model->get_tutor_application($request_id, $user_id);

		if (!$this->input->is_ajax_request()
			|| !$application
			|| $application['tutor_id'] != $user_id
			|| $application['request_id'] != $request_id)
		{
			show_404();
		}

		$response = $this->requests_model->delete_application($application['id']);

		echo json_encode($response);
	}

	function show_hide_request_or_application()
	{
		if (!$this->input->is_ajax_request())
		{
			show_404();
		}

		$this->form_validation->set_rules('type', 'Type', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('id', 'Items', 'trim|strip_tags|required|xss_clean');	
		$this->form_validation->set_rules('action', 'Action', 'trim|strip_tags|xss_clean');	

		if (!$this->form_validation->run())	
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}

		$item_id = $this->input->post('id');
		$type = $this->input->post('type');
		$action = $this->input->post('action');

		if ($action == 'show')
		{
			$hide = FALSE;
		}
		else
		{
			$hide = TRUE;
		}

		$this->requests_model->show_hide_request_or_application($type, $item_id, $hide);
	}

	function new_request()
	{	
		$data['meta'] = $this->config->item('requests-new');
		$this->load->page('requests/make', $data);
	}

	function make()
	{
		$this->edit(TRUE);
	}

	function edit($initial_edit = FALSE)
	{
//		var_export($_POST);
//		return;
		if (!($this->form_validation->not_spam($this->input->post()) && $this->input->is_ajax_request()))
		{
			show_404();
		}

		$this->form_validation->set_rules('subjects', 'Subject', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('location', 'Location', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('details', 'Details', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('max-price', 'Max Price', 'trim|strip_tags|is_money|xss_clean');
		$this->form_validation->set_rules('currency', 'Currency', 'trim|strip_tags|callback_correct_currency_code|xss_clean');
		$this->form_validation->set_rules('type', 'Type', 'trim|strip_tags|required|xss_clean');

		if (!$initial_edit)
		{
			$this->form_validation->set_rules('request-id', '', 'trim|strip_tags|required|integer|xss_clean');
		}

//		var_export($_POST);

		if (!$this->form_validation->run())	
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}

		// These should only cause an error if there was a JS problem 
		$this->form_validation->set_rules('lat', '', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('lon', '', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('country', '', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('city', '', 'trim|strip_tags|required|xss_clean');

		if (!$this->form_validation->run())	
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}

	    $errors = array();

	    // Deal with subjects
	    $subject = $this->input->post('subjects');
	    if (count(explode(',', $subject)) > 1)
	    {
	    	$errors += array('subjects' => $this->form_validation->make_error("Please enter only one subject."));
	    }

	    // Deal with max price
		$max_price = $this->input->post('max-price');
	    $max_price = preg_replace("/[^0-9.]/", "", $max_price);
	    if (!$max_price)
	    	$max_price = 0;

		if ($max_price > 99999)
    		$errors += array('max-price' => $this->form_validation->make_error("Please enter a Max Price less than 99,999"));
		elseif ($max_price < 0)
    		$errors += array('max-price' => $this->form_validation->make_error("Please enter a positive Max Price"));

		if (!empty($errors))
		{
			echo json_encode($this->form_validation->response(STATUS_VALIDATION_ERROR, array('errors' => $errors)));
    		return;
		}

		$post = $this->input->post();

		if (!in_array($post['type'], array(REQUEST_TYPE_DISTANCE, REQUEST_TYPE_BOTH)))
		{
			$post['type'] = REQUEST_TYPE_LOCAL;
		}

		$user_id = $this->session->userdata('user_id');		// If not logged in, it would return FALSE == 0

		$request_data = array(
			'user_id' => $user_id,
			'type' => $post['type'],
			'details' => $post['details'],
			'price' => $max_price,
			'currency' => $post['currency'],
			'location_name' => $post['location'],
			'location_lat' => $post['lat'],
			'location_lon' => $post['lon'],
			'location_city' => $post['city'],
			'location_country' => $post['country'],
			'subject_ids' => $this->subjects_model->parse_subjects_string($post['subjects'], 1)
		);

		if ($this->session->userdata('init'))
		{
			$this->load->model('admin_model');
			$user = $this->users->get_user_by_id($user_id);
			$request_data['posted'] = $this->admin_model->generate_datetime('-7 days');
		}


		if ($initial_edit)
		{
//			$request_data['status'] = ($user_id ? REQUEST_STATUS_OPEN : REQUEST_STATUS_PENDING);

			// First we make a pending request (inactive)
			$request_data['status'] = REQUEST_STATUS_PENDING;
			$request_id = $this->requests_model->make($request_data);

			$response_data = array(
				'requestId' => $request_id, 
				'needsAuth' => FALSE
			);

			// If request is made and user is logged in, then open the request
			if ($request_id && $user_id)
			{
				$request_data['status'] = REQUEST_STATUS_OPEN;
				
				if ($this->requests_model->open($request_id, $user_id, TRUE))
				{

					$this->requests_model->update_session_requests($user_id);
					$this->reaction_notice->set_quick('<b>Your request is made!</b>');
					$response = $this->form_validation->response(STATUS_OK, $response_data);
				}
				else 	// Somehow opening failed. User id might be spoofed
				{
					$response = $this->form_validation->invalid_response();
				}
			}
			else
			{
				// if request was made but user isn't logged in...
				if ($request_id)
				{
					$response_data['needsAuth'] = TRUE;
					$this->session->set_userdata('open_request_id', $request_id);
					$response = $this->form_validation->response(STATUS_OK, $response_data);
				}
				// else, total failure
				else
				{
					$response = $this->form_validation->invalid_response();
				}
			}
		}
		else
		{
			$request_data['id'] = $this->input->post('request-id');
			
			$response_data = array(
				'requestId' => $request_data['id'], 
				'needsAuth' => FALSE
			);

			if ($this->requests_model->edit($request_data))
			{
				$this->requests_model->update_session_requests($user_id);
				$this->reaction_notice->set_quick('<b>Edit successful!</b>');
				$response = $this->form_validation->response(STATUS_OK, $response_data);
			}
			else
			{
				$response = $this->form_validation->invalid_response();
			}
		}

		echo json_encode($response);
	}

	function correct_currency_code($code)
	{
		$allowable_currencies = array('USD','EUR','GBP','CAD','AUD','AFN','ALL','DZD','ARS','BSD','BHD','BDT','BBD','BMD','BRL','BGN','XOF','XAF','CLP','CNY','CNY','COP','XPF','CRC','HRK','CZK','DKK','DOP','XCD','EGP','EEK','FJD','HKD','HUF','ISK','INR','IDR','IRR','IQD','ILS','JMD','JPY','JOD','KES','KRW','KWD','LBP','MYR','MUR','MXN','MAD','NZD','NOK','OMR','PKR','PEN','PHP','PLN','QAR','RON','RUB','SAR','SGD','ZAR','KRW','LKR','SDG','SEK','CHF','TWD','THB','TTD','TND','TRY','AED','VEF','VND','ZMK');

		if (in_array($code, $allowable_currencies))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('correct_currency_code', "Sorry, this isn't a valid currency code. ");
			return FALSE;
		}
	}

	function _remap($method)
	{
	  $param_offset = 2;

	  // Default to index
	  if (!method_exists($this, $method))
	  {
	    // We need one more param
	    $param_offset = 1;
	    $method = 'index';
	  }

	  // Since all we get is $method, load up everything else in the URI
	  $params = array_slice($this->uri->rsegment_array(), $param_offset);

	  // Call the determined method with all params
	  call_user_func_array(array($this, $method), $params);
	}
}

/* End of file request.php */
/* Location: ./application/controllers/request.php */