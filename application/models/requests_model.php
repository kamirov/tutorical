<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Requests Model
 *
 *
 */
class Requests_model extends CI_Model
{
	private $user_id;
	private $profile_table = 'tutor_profiles';

	function __construct()
	{
		parent::__construct();
	}

	function get_short_requests($user_id, $statuses = array(REQUEST_STATUS_OPEN, REQUEST_STATUS_CLOSED, REQUEST_STATUS_EXPIRED), $show_hidden = TRUE)
	{
		$this->db->select('r.id, UNIX_TIMESTAMP(r.posted) posted, r.status, r.hidden_on_requests_page AS hidden')
				 ->from('requests r')
				 ->where("r.user_id", $user_id)
				 ->where_in('r.status', $statuses)
				 ->order_by('r.status ASC, r.posted DESC');

		if (!$show_hidden)
			$this->db->where('r.hidden_on_requests_page', FALSE);
		
		$requests =	$this->db->get()->result_array();

		foreach($requests as &$request)
		{
			$request['subjects_string'] = implode(', ', $this->get_requests_subjects($request['id']));
		}

		return $requests;
	}

	function get_tutors_requests($user_id, $statuses = NULL, $get_user = FALSE, $show_hidden = TRUE)
	{
		$this->db->start_cache();

		$this->db->select('r.id, UNIX_TIMESTAMP(r.posted) posted, r.status, r.type, rt.id as application_id, rt.status as application_status, rt.hidden_on_requests_page AS hidden')
				 ->distinct()
				 ->from('requests r')
				 ->join('requests_tutors rt', 'r.id = rt.request_id')
				 ->order_by('r.status ASC, application_status ASC, r.posted DESC')
				 ->where("rt.tutor_id", $user_id)
				 ->where_in('r.status', array(REQUEST_STATUS_OPEN, REQUEST_STATUS_CLOSED, REQUEST_STATUS_EXPIRED));

		if (!$show_hidden)
			$this->db->where('rt.hidden_on_requests_page', FALSE);

		$this->db->stop_cache();
		
		if ($statuses)
		{
			$this->db->where_in("rt.status", $statuses);
		}
		if ($get_user)
		{
			$this->db->join('users u', 'u.id = rt.tutor_id')
					 ->select('u.display_name, u.username');
		}

		$requests = $this->db->get()->result_array();

		$this->db->flush_cache();

//		var_export(nl2br($this->db->last_query()));


		foreach($requests as &$request)
		{
			$request['subjects_string'] = implode(', ', $this->get_requests_subjects($request['id']));
		}

		return $requests;		
	}

	// Eventually merge with get_short_requests
	function get_users_session_requests($user_id)
	{
		$requests = $this->db->select('r.id, UNIX_TIMESTAMP(r.posted) posted')
							->from('requests r')
							->where("r.user_id", $user_id)
							->where_in('r.status', array(REQUEST_STATUS_OPEN))
							->get()->result_array();

		foreach($requests as &$request)
		{
			$request['subjects_string'] = implode(', ', $this->get_requests_subjects($request['id']));
			$request['posted'] = $request['posted'];
		}

		return $requests;
	}

	function get_invite_email_data($request_id, $tutor_id)
	{
		$this->db->select('u.display_name AS student_name, u.username,
							rs.subject_id,
							s.name AS `subject`,
							r.id AS request_id, r.details')
				 ->from('requests r')
				 ->where('r.id', $request_id)
				 ->join('users u', 'u.id = r.user_id')
				 ->join('requests_subjects rs', 'rs.request_id = r.id')
				 ->join('subjects s', 's.id = rs.subject_id');		

		$email_data = $this->db->get()->row_array();
		$email_data['student_profile_path'] = 'students/'.$email_data['username'];

		$this->db->select('u.email, u.userdata')
				 ->from('users u')
				 ->where('u.id', $tutor_id);

		$tutor_data = $this->db->get()->row_array();

		$email_data['tutor_email'] = $tutor_data['email'];
		$email_data['tutor_userdata'] = $tutor_data['userdata'];

		return $email_data;

	}

	function get_application_email_data($application_id)
	{
		$this->db->select('u.display_name AS tutor_name, u.email AS tutor_email, u.username, u.userdata AS tutor_userdata,
							rt.price, rt.message AS application_message, rt.student_response,
							rs.subject_id,
							s.name AS `subject`,
							r.id AS request_id, r.details AS request_details, r.currency, r.user_id AS student_id')
				 ->from('requests_tutors rt')
				 ->where('rt.id', $application_id)
				 ->join('users u', 'u.id = rt.tutor_id')
				 ->join('requests r', 'r.id = rt.request_id')
				 ->join('requests_subjects rs', 'rs.request_id = r.id')
				 ->join('subjects s', 's.id = rs.subject_id');

		$email_data = $this->db->get()->row_array();
		$email_data['currency_sign'] = get_currency_sign($email_data['currency']);
		$email_data['tutor_profile_path'] = 'tutors/'.$email_data['username'];

		$this->db->select('u.email, u.display_name, u.username, u.userdata')
				 ->from('users u')
				 ->where('u.id', $email_data['student_id']);

		$student_data = $this->db->get()->row_array();

		$email_data['student_email'] = $student_data['email'];
		$email_data['student_name'] = $student_data['display_name'];
		$email_data['student_userdata'] = $student_data['userdata'];
		$email_data['student_profile_path'] = 'students/'.$student_data['username'];

		return $email_data;
	}

	function apply($application)
	{
		$this->db->trans_start(IS_TEST_MODE);

		if ($current_application = $this->get_tutor_application($application['request_id'], $application['tutor_id']))
		{
//			var_export($application);
			// We get the whole application because later on we'll do checks agains the current status to detect if student has already rejected this application (which means the tutor is now reapplying)

			$application_id = $current_application['id'];
			$this->db->where('id', $application_id)
					 ->update('requests_tutors', $application);

			$reaction = "<b>Edit succcessful!</b>";

			if ($current_application['status'] == RESPONSE_STATUS_REJECTED)
			{
				$this->db->set('num_of_applications', 'num_of_applications+1', FALSE)
						 ->where('id', $application['request_id'])
						 ->update('requests');				
			}
		}
		else
		{
			$this->db->set('num_of_applications', 'num_of_applications+1', FALSE)
					 ->where('id', $application['request_id'])
					 ->update('requests');

			// PROBLEM. This erases the 'is_invited' value. Need to do a check to see if tutor has been  invited, then change the application to have that
			$this->db->replace('requests_tutors', $application);	// Use replace to account for invited tutors

			$application_id = $this->db->insert_id();
			$reaction = "<b>Application successful!</b>";
		}
/*
		if ($this->session->userdata('init'))
		{
			$accept = mt_rand(0, 100);
		}
*/
		
		$this->db->trans_complete(IS_TEST_MODE);

		$status = $this->db->trans_status();

		if (!$current_application && $status)
		{
			$email_data = $this->get_application_email_data($application_id);

//			var_dump($email_data);

			$student_notification_settings = json_decode($email_data['student_userdata']);
			$student_notification_settings = $student_notification_settings->notification_settings;

			if (in_array('student_applied', $student_notification_settings))
			{
				$email = array(
					'to' => $email_data['student_email'],
					'subject' => $email_data['tutor_name']." has applied to your tutor request!",
					'reply_to_name' => $email_data['tutor_name'],
					'reply_to_email' => $email_data['tutor_email'],
					'template' => 'new_application',
					'data' => $email_data,
					'priority' => IMPORTANT_EMAIL_PRIORITY
				);

				$this->email->process_email($email);	// This is optional, so should not be part of conditional
			}
		}

		if ($status)
		{
			$this->reaction_notice->set_quick($reaction);
			return $this->form_validation->response();
		}

		return $this->form_validation->response(STATUS_DATABASE_ERROR);
	}

	function get_tutor_application($request_id, $user_id)
	{
		return $this->db->where('tutor_id', $user_id)
				 		->where('request_id', $request_id)
				 		->where_in('status', array(RESPONSE_STATUS_PENDING, RESPONSE_STATUS_REJECTED, RESPONSE_STATUS_APPROVED))
				 		->get('requests_tutors')->row_array();
	}

	function get_application_by_id($application_id)
	{
		return $this->db->where('id', $application_id)
				 		->get('requests_tutors')->row_array();
	}

	function delete_application($application_id)
	{
		$this->db->trans_start(IS_TEST_MODE);

		$application = $this->get_application_by_id($application_id);

		$this->db->where('id', $application_id)
				 ->delete('requests_tutors');

		$this->db->set('num_of_applications', 'num_of_applications-1', FALSE)
				 ->where('id', $application['request_id'])
				 ->update('requests');

		$this->db->trans_complete(IS_TEST_MODE);

		if ($this->db->trans_status())
		{
			$this->reaction_notice->set_quick('<b>Application deleted!</b>');
			return $this->form_validation->response();
		}

		return $this->form_validation->response(STATUS_DATABASE_ERROR);
	}

	function batch_toggle_request($request_ids, $status = REQUEST_STATUS_CLOSED)
	{
		$data = array();

		$data['status'] = $status;

		$this->db->where_in('id', $request_ids)
				 ->update('requests', $data);

		if ($this->db->affected_rows() > 0)
			return TRUE;
	}

	function toggle_request($request_id, $user_id, $action = 'close')
	{
		$data = array();

		if ($action == 'close')
		{
			$data['status'] = REQUEST_STATUS_CLOSED;
		}
		else
		{
			$data['status'] = REQUEST_STATUS_OPEN;
		}

		$this->db->where('id', $request_id)
				 ->where('user_id', $user_id)
				 ->update('requests', $data);

		if ($this->db->affected_rows() > 0)
			return TRUE;
	}

	function update_session_requests($user_id)
	{
		$requests = $this->get_users_session_requests($user_id);
		$this->session->set_userdata('requests', $requests);
	}

	function get_request($val, $by = 'id')
	{
		$request = $this->db->select('r.id, r.type, r.user_id, r.details, r.price, r.currency, r.location_name, r.location_lat, r.location_lon, r.location_city, r.location_country, r.accepted_id, UNIX_TIMESTAMP(r.posted) posted, r.status, u.username, u.avatar_path, u.display_name')
							->from('requests r')
							->join('users u', 'u.id = r.user_id', 'left')
							->where("r.$by", $val)
							->where_in('r.status', array(REQUEST_STATUS_OPEN, REQUEST_STATUS_CLOSED, REQUEST_STATUS_EXPIRED))
							->get()->row_array();

//		echo nl2br($this->db->last_query());
	
		if (empty($request))
			return NULL;


		$subjects_data['subjects_array'] = $this->get_requests_subjects($request['id']);

		$request['subjects_string'] = implode(', ', $subjects_data['subjects_array']);
		$request['subjects_count'] = count($subjects_data['subjects_array']);
		$request['subjects_table'] = $this->load->view('components/profile/subjects', $subjects_data, TRUE);			

		$country_code = $this->get_country_code($request['location_country']);
		$request['flag_url'] = base_url("assets/images/flags/$country_code.gif");

		$request['avatar_url'] = base_url($request['avatar_path']);
		$request['profile_path'] = 'students/'.$request['username'];

		$request['applications'] = $this->get_applications($request['id']);

		if ($request['applications'])
		{
			$request['applications_meta'] = array(
				'count' => count($request['applications']),
				'min_price' => $request['applications'][0]['price'],
				'avg_price' => $request['applications'][0]['price'],
				'max_price' => $request['applications'][0]['price'],
				'sum_price' => 0
			);

			$sum_price = 0;
			foreach($request['applications'] as $application)
			{
				$sum_price += $application['price'];

				if ($application['price'] < $request['applications_meta']['min_price'])
				{
					$request['applications_meta']['min_price'] = $application['price'];
				}
				if ($application['price'] > $request['applications_meta']['max_price'])
				{
					$request['applications_meta']['max_price'] = $application['price'];
				}
			}

			$request['applications_meta']['sum_price'] = $sum_price;
			$request['applications_meta']['avg_price'] = number_format((float)($sum_price / $request['applications_meta']['count']), 2, '.', '');
		}

		return $request;
	}

	function confirm_ownership($request_id, $user_id)
	{
		$query = $this->db->select('1', FALSE)
				 	  	  ->from('requests')
				 		  ->where('id', $request_id)
			 			  ->where('user_id', $user_id)
					 	  ->get();

		return $query->num_rows() == 1;
	}

	function get_affiliated_request_ids($tutor_id)
	{
		$ids = $this->db->select('request_id')
						->from('requests_tutors')
						->where('tutor_id', $tutor_id)
						->get()->result_array();

		$ids = combine_subarrays($ids, 'request_id');

		return $ids;
	}

	function accept($request_id, $application_id, $student_id)
	{
		$this->db->trans_start(IS_TEST_MODE);

		$data = array(
			'status' => RESPONSE_STATUS_APPROVED
		);
		$this->db->where('request_id', $request_id)
				 ->where('id', $application_id)
				 ->update('requests_tutors', $data);

		$data = array(
			'accepted_id' => $application_id,
			'status' => REQUEST_STATUS_CLOSED
//			'message' => $this->request_to_message($request_id, $application_id)
		);
		$this->db->where('id', $request_id)
				 ->update('requests', $data);

		$tutor_id = $this->db->select('tutor_id')
				 		 ->from('requests_tutors')
				 		 ->where('id', $application_id)
				 		 ->get()->row_array();

		$tutor_id = $tutor_id['tutor_id'];

//		var_export(func_get_args());

		// Used later in function, but got early on to use message in student_data
		$email_data = $this->get_application_email_data($application_id);

		$student_tutor_message = "<div class='for-student'><div class='request-messages'>[Accepted to your ".anchor("requests/".$request_id, $email_data['subject']." request")."]</div>".$email_data['application_message']."</div>";
		$student_tutor_message .= "<div class='for-tutor'><div class='request-messages'>[Accepted your application for their ".anchor("requests/".$request_id, $email_data['subject']." request")."]</div>".$email_data['request_details']."</div>";
		
		$student_data = array(
			'status' => STUDENT_STATUS_TEMP,
			'contacted' => date('Y-m-d H:i:s'),
			'message' => $student_tutor_message
		);

		$this->profile_notices_model->add_notice(ACCEPTED_TO_REQUEST, $tutor_id);
		$this->tutor_model->add_student($student_id, $tutor_id, $student_data);
		$this->tutor_model->update_tutors_student_status($student_id, $tutor_id, STUDENT_STATUS_ACTIVE);

		$this->db->trans_complete();

		$status = $this->db->trans_status();

		if ($status)
		{
			$tutor_notification_settings = json_decode($email_data['tutor_userdata']);
			$tutor_notification_settings = $tutor_notification_settings->notification_settings;

			if (in_array('tutor_accept', $tutor_notification_settings))
			{
				$email = array(
					'to' => $email_data['tutor_email'],
					'subject' => $email_data['student_name']." has accepted your application!",
					'reply_to_name' => $email_data['student_name'],
					'reply_to_email' => $email_data['student_email'],
					'template' => 'application_accepted',
					'data' => $email_data,
					'priority' => IMPORTANT_EMAIL_PRIORITY
				);

				$this->email->process_email($email);	// This is optional, so should not be part of conditional
			}
		}

		return $status;
	}
/*
	function request_to_message($request_id, $application_id)
	{
		$request_details = $this->db->select('r.details')
				 		 ->from('requests r')
				 		 ->where('id', $request_id)
				 		 ->get()->row_array();
		$request_details = $request_details['details'];

		$application = $this->db->select('rt.message, rt.price')
				 		 ->from('requests_tutors rt')
				 		 ->where('id', $request_id)
				 		 ->get()->row_array();
	}
*/
	function reject($request_id, $application_id, $message)
	{
		$this->db->trans_start(IS_TEST_MODE);

		$data = array(
			'status' => RESPONSE_STATUS_REJECTED,
			'student_response' => $message
		);

		$this->db->where('request_id', $request_id)
				 ->where('id', $application_id)
				 ->update('requests_tutors', $data);

		$tutor_id = $this->db->select('tutor_id')
				 		 ->from('requests_tutors')
				 		 ->where('id', $application_id)
				 		 ->get()->row_array();

		$tutor_id = $tutor_id['tutor_id'];
		
//		var_export(func_get_args());

		$this->db->set('num_of_applications', 'num_of_applications-1', FALSE)
				 ->where('id', $request_id)
				 ->update('requests');

		$this->profile_notices_model->add_notice(REJECTED_FROM_REQUEST, $tutor_id);

		$this->db->trans_complete();

		$status = $this->db->trans_status();

		if ($status)
		{
			$email_data = $this->get_application_email_data($application_id);

			$tutor_notification_settings = json_decode($email_data['tutor_userdata']);
			$tutor_notification_settings = $tutor_notification_settings->notification_settings;

			if (in_array('tutor_reject', $tutor_notification_settings))
			{
				$email = array(
					'to' => $email_data['tutor_email'],
					'subject' => $email_data['student_name']." has rejected your application :(",
					'template' => 'application_rejected',
					'data' => $email_data,
					'priority' => IMPORTANT_EMAIL_PRIORITY
				);

				$this->email->process_email($email);	// This is optional, so should not be part of conditional
			}
		}

		return $status;
	}

	function invite($request_id, $tutor_id)
	{	
		$this->db->trans_start(IS_TEST_MODE);

		$data = array(
			'request_id' => $request_id,
			'tutor_id' => $tutor_id,
			'status' => RESPONSE_STATUS_INVITED,
			'invited' => TRUE
		);
		
		$this->db->insert('requests_tutors', $data);
		$this->profile_notices_model->add_notice(INVITED_TO_REQUEST, $tutor_id);

		$this->db->trans_complete();

		$status = $this->db->trans_status();

		if ($status)
		{
			$email_data = $this->get_invite_email_data($request_id, $tutor_id);

			$tutor_notification_settings = json_decode($email_data['tutor_userdata']);
			$tutor_notification_settings = $tutor_notification_settings->notification_settings;

			if (in_array('tutor_invite', $tutor_notification_settings))
			{
				$email = array(
					'to' => $email_data['tutor_email'],
					'subject' => $email_data['student_name']." has invited you to a Tutor Request",
					'template' => 'invited-to-request',
					'data' => $email_data,
					'priority' => IMPORTANT_EMAIL_PRIORITY
				);

				$this->email->process_email($email);	// This is optional, so should not be part of conditional
			}
		}

		return $status;
	}

	function get_requests_subjects($request_id)
	{
		$subjects = $this->db->select('s.name')
							 ->from('requests_subjects rs')
							 ->where('rs.request_id', $request_id)
							 ->join('subjects s', 's.id = rs.subject_id')
							 ->order_by('s.name ASC')
							 ->get()->result_array();

		$subjects = combine_subarrays($subjects, 'name');

		return $subjects;
	}

	// Really don't like this. Eventually, combine request subject id into request table and get rid of these nonsense functions.
	function get_requests_subject_full($request_id)
	{
		$subject = $this->db->select('s.name, s.id')
							 ->from('requests_subjects rs')
							 ->where('rs.request_id', $request_id)
							 ->join('subjects s', 's.id = rs.subject_id')
							 ->order_by('s.name ASC')
							 ->get()->row_array();

		return $subject;
	}


	function get_requests_subjects_string($request_id)
	{
		return implode(', ', $this->get_requests_subjects($request_id));
	}

	function get_applications($request_id)
	{
		$applications = $this->db->select('rt.id, rt.student_response, rt.request_id, rt.tutor_id, rt.message, rt.price, UNIX_TIMESTAMP(rt.posted) posted, rt.invited, rt.status,
									 u.display_name, u.avatar_path, u.username,
									 tp.num_of_reviews, tp.snippet, tp.average_rating')
						   ->from('requests_tutors rt')
						   ->where('rt.request_id', $request_id)
						   ->where_in('rt.status', array(RESPONSE_STATUS_APPROVED, RESPONSE_STATUS_PENDING))
						   ->join('users u', 'u.id = rt.tutor_id', 'left')
						   ->join('tutor_profiles tp', 'tp.user_id = u.id', 'left')
						   ->order_by('posted ASC')
						   ->get()->result_array();
		return $applications;
	}

	function show_hide_request_or_application($type, $item_id, $hide = TRUE)
	{
		if ($type == 'application')
		{
			$table = 'requests_tutors';
			$user_row = 'tutor_id';
		}
		else
		{
			$table = 'requests';
			$user_row = 'user_id';
		}

		$data = array('hidden_on_requests_page' => $hide);
		$user_id = $this->session->userdata('user_id');
		
//		var_export($data);

		$this->db->where('id', $item_id)
				 ->where($user_row, $user_id)
				 ->update($table, $data);
	}

	function has_hidden_requests_or_applications($user_id)
	{
		$this->db->select('1', FALSE)
				 ->from('requests')
				 ->where('user_id', $user_id)
				 ->where('hidden_on_requests_page', TRUE);
		$query = $this->db->get();

		$has_hidden = ($query->num_rows() > 0);

		if (!$has_hidden)
		{
			$this->db->select('1', FALSE)
					 ->from('requests_tutors')
					 ->where('tutor_id', $user_id)
					 ->where('hidden_on_requests_page', TRUE);
			$query = $this->db->get();

			$has_hidden = ($query->num_rows() > 0);
		}

		return $has_hidden;
	}

	function edit($request_data)
	{
		$this->db->trans_start(IS_TEST_MODE);

		$request_id = $request_data['id'];
		$user_id = $this->session->userdata('user_id');
		$subject_ids = $request_data['subject_ids'];
		unset($request_data['subject_ids']);

		$this->db->where('id', $request_id)
				 ->where('user_id', $user_id)
				 ->update('requests', $request_data);
		
		$requests_subjects_data = array();

		foreach($subject_ids as $subject_id)
		{
			$requests_subjects_data[] = array(
				'request_id' => $request_id,
				'subject_id' => $subject_id
			);
		}

		$this->db->delete('requests_subjects', array('request_id' => $request_id));
		$this->db->insert_batch('requests_subjects', $requests_subjects_data);

		$this->db->trans_complete();

		if ($this->db->trans_status())
		{
			return TRUE;
		}
//		return $response;			
	}

	function make($request_data)
	{
		$this->db->trans_start(IS_TEST_MODE);

		$subject_ids = $request_data['subject_ids'];
		unset($request_data['subject_ids']);

		$this->db->insert('requests', $request_data);
		
		$request_id = $this->db->insert_id();
		$requests_subjects_data = array();

		foreach($subject_ids as $subject_id)
		{
			$requests_subjects_data[] = array(
				'request_id' => $request_id,
				'subject_id' => $subject_id
			);
		}

		$this->db->insert_batch('requests_subjects', $requests_subjects_data);

		$this->db->trans_complete();

		if ($this->db->trans_status())	
		{
			return $request_id;
/*
			if ($request_data['user_id'])
			{
				$this->update_session_requests($request_data['user_id']);
				$this->reaction_notice->set_quick('<b>Your request is made!</b>');
				$response_data['needsAuth'] = FALSE;
			}
			else
			{
				$this->session->set_userdata('open_request_id', $request_id);
				$response_data['needsAuth'] = TRUE;			
			}

			$response = $this->form_validation->response(STATUS_OK, $response_data);
*/
		}
	}

	function open($request_id, $user_id, $initial_open = FALSE)
	{
		$this->db->where('id', $request_id)
				 ->update('requests', array('status' => REQUEST_STATUS_OPEN, 'user_id' => $user_id));

		$this->update_session_requests($user_id);

		$updated = ($this->db->affected_rows() > 0);

		if ($initial_open && $updated)				
		{
			$request = $this->get_request($request_id);

			// Only send email to tutors if not init
			if (!$this->session->userdata('init'))
			{
				$this->requests_model->email_tutors($request);				
			}
		}
		
		return $updated;
	}

	function email_tutors($request)
	{
		$this->load->model('find_model');

		$subject = $this->get_requests_subject_full($request['id']);

		$args = array(
			'lat' => $request['location_lat'],
			'lon' => $request['location_lon'],
			'limit_count' => NULL,
			'subject_id' => $subject['id'],
			'distance' => DEFAULT_EMAIL_TUTORS_DISTANCE,
			'include_userdata' => TRUE,
			'sort' => 'new'	// Need this to avoid distance tutors being ordered by non-existant 'distance' column
		);	

		$email_list = array(
			'local' => array(),
			'distance' => array()
		);

		if ($request['type'] == REQUEST_TYPE_LOCAL
			|| $request['type'] == REQUEST_TYPE_BOTH)
		{
			$local_tutors = $this->find_model->find('local', 'tutors', $args);
//			echo 'local<br>';
//			var_dump($local_tutors);

			foreach($local_tutors['items'] as $tutor)
			{
				$tutor_notification_settings = json_decode($tutor['userdata']);
				$tutor_notification_settings = $tutor_notification_settings->notification_settings;

				if (in_array('tutor_local_request', $tutor_notification_settings))
				{
					$email_list['local'][] = $tutor['email'];
				}
			}
		}
//		echo 'email-list 1<br>';
//		var_dump($email_list);

		if ($request['type'] == REQUEST_TYPE_DISTANCE
			|| $request['type'] == REQUEST_TYPE_BOTH)
		{
			$distance_tutors = $this->find_model->find('distance', 'tutors', $args);
//			echo 'distance<br>';
//			var_dump($distance_tutors);
			
			foreach($distance_tutors['items'] as $tutor)
			{
				$tutor_notification_settings = json_decode($tutor['userdata']);
				$tutor_notification_settings = $tutor_notification_settings->notification_settings;

				if (in_array('tutor_distance_request', $tutor_notification_settings)
					&& !in_array($tutor['email'], $email_list['local']))
				{
					$email_list['distance'][] = $tutor['email'];					
				}
			}
		}
//		echo 'email-list-2<br>';
//		var_dump($email_list);

		$data = array(
			'subject' => $subject['name'],
			'request_id' => $request['id'],
			'student_name' => $request['display_name'],
			'student_profile_path' => 'students/'.$request['username'],
			'details' => $request['details']
		);

		// Construct and send emails
		$emails = array();

		if ($email_list['local'])
		{
			$emails[] = array(
				'bcc' => $email_list['local'],
				'subject' => "New {$subject['name']} request near you",
				'template' => 'new-local-request',
				'data' => $data
			);
		}
		if ($email_list['distance'])
		{
			$emails[] = array(
				'bcc' => $email_list['distance'],
				'subject' => "New {$subject['name']} distance request",
				'template' => 'new-distance-request',
				'data' => $data
			);
		}

		return $this->email->process_emails($emails, TRUE);		// Force queue

//		echo $emails[1]['message'];
//		var_dump($data, $email_list);
	}

	function get_country_code($country)
	{
	  	$countrycodes = array (
	      'Afghanistan' => 'AF',
	      'Åland Islands' => 'AX',
	      'Albania' => 'AL',
	      'Algeria' => 'DZ',
	      'American Samoa' => 'AS',
	      'Andorra' => 'AD',
	      'Angola' => 'AO',
	      'Anguilla' => 'AI',
	      'Antarctica' => 'AQ',
	      'Antigua and Barbuda' => 'AG',
	      'Argentina' => 'AR',
	      'Australia' => 'AU',
	      'Austria' => 'AT',
	      'Azerbaijan' => 'AZ',
	      'Bahamas' => 'BS',
	      'Bahrain' => 'BH',
	      'Bangladesh' => 'BD',
	      'Barbados' => 'BB',
	      'Belarus' => 'BY',
	      'Belgium' => 'BE',
	      'Belize' => 'BZ',
	      'Benin' => 'BJ',
	      'Bermuda' => 'BM',
	      'Bhutan' => 'BT',
	      'Bolivia' => 'BO',
	      'Bosnia and Herzegovina' => 'BA',
	      'Botswana' => 'BW',
	      'Bouvet Island' => 'BV',
	      'Brazil' => 'BR',
	      'British Indian Ocean Territory' => 'IO',
	      'Brunei Darussalam' => 'BN',
	      'Bulgaria' => 'BG',
	      'Burkina Faso' => 'BF',
	      'Burundi' => 'BI',
	      'Cambodia' => 'KH',
	      'Cameroon' => 'CM',
	      'Canada' => 'CA',
	      'Catalonia' => 'CT',
	      'Cape Verde' => 'CV',
	      'Cayman Islands' => 'KY',
	      'Central African Republic' => 'CF',
	      'Chad' => 'TD',
	      'Chile' => 'CL',
	      'China' => 'CN',
	      'Christmas Island' => 'CX',
	      'Cocos (Keeling) Islands' => 'CC',
	      'Colombia' => 'CO',
	      'Comoros' => 'KM',
	      'Congo' => 'CG',
	      'Zaire' => 'CD',
	      'Cook Islands' => 'CK',
	      'Costa Rica' => 'CR',
	      'Côte D\'Ivoire' => 'CI',
	      'Croatia' => 'HR',
	      'Cuba' => 'CU',
	      'Cyprus' => 'CY',
	      'Czech Republic' => 'CZ',
	      'Denmark' => 'DK',
	      'Djibouti' => 'DJ',
	      'Dominica' => 'DM',
	      'Dominican Republic' => 'DO',
	      'Ecuador' => 'EC',
	      'Egypt' => 'EG',
	      'El Salvador' => 'SV',
	      'Equatorial Guinea' => 'GQ',
	      'Eritrea' => 'ER',
	      'Estonia' => 'EE',
	      'Ethiopia' => 'ET',
	      'Falkland Islands (Malvinas)' => 'FK',
	      'Faroe Islands' => 'FO',
	      'Fiji' => 'FJ',
	      'Finland' => 'FI',
	      'France' => 'FR',
	      'French Guiana' => 'GF',
	      'French Polynesia' => 'PF',
	      'French Southern Territories' => 'TF',
	      'Gabon' => 'GA',
	      'Gambia' => 'GM',
	      'Georgia' => 'GE',
	      'Germany' => 'DE',
	      'Ghana' => 'GH',
	      'Gibraltar' => 'GI',
	      'Greece' => 'GR',
	      'Greenland' => 'GL',
	      'Grenada' => 'GD',
	      'Guadeloupe' => 'GP',
	      'Guam' => 'GU',
	      'Guatemala' => 'GT',
	      'Guernsey' => 'GG',
	      'Guinea' => 'GN',
	      'Guinea-Bissau' => 'GW',
	      'Guyana' => 'GY',
	      'Haiti' => 'HT',
	      'Heard Island and Mcdonald Islands' => 'HM',
	      'Vatican City State' => 'VA',
	      'Honduras' => 'HN',
	      'Hong Kong' => 'HK',
	      'Hungary' => 'HU',
	      'Iceland' => 'IS',
	      'India' => 'IN',
	      'Indonesia' => 'ID',
	      'Iran, Islamic Republic of' => 'IR',
	      'Iraq' => 'IQ',
	      'Ireland' => 'IE',
	      'Isle of Man' => 'IM',
	      'Israel' => 'IL',
	      'Italy' => 'IT',
	      'Jamaica' => 'JM',
	      'Japan' => 'JP',
	      'Jersey' => 'JE',
	      'Jordan' => 'JO',
	      'Kazakhstan' => 'KZ',
	      'KENYA' => 'KE',
	      'Kiribati' => 'KI',
	      'Korea, Democratic People\'s Republic of' => 'KP',
	      'Korea, Republic of' => 'KR',
	      'Kuwait' => 'KW',
	      'Kyrgyzstan' => 'KG',
	      'Lao People\'s Democratic Republic' => 'LA',
	      'Latvia' => 'LV',
	      'Lebanon' => 'LB',
	      'Lesotho' => 'LS',
	      'Liberia' => 'LR',
	      'Libyan Arab Jamahiriya' => 'LY',
	      'Liechtenstein' => 'LI',
	      'Lithuania' => 'LT',
	      'Luxembourg' => 'LU',
	      'Macao' => 'MO',
	      'Macedonia, the Former Yugoslav Republic of' => 'MK',
	      'Madagascar' => 'MG',
	      'Malawi' => 'MW',
	      'Malaysia' => 'MY',
	      'Maldives' => 'MV',
	      'Mali' => 'ML',
	      'Malta' => 'MT',
	      'Marshall Islands' => 'MH',
	      'Martinique' => 'MQ',
	      'Mauritania' => 'MR',
	      'Mauritius' => 'MU',
	      'Mayotte' => 'YT',
	      'Mexico' => 'MX',
	      'Micronesia, Federated States of' => 'FM',
	      'Moldova, Republic of' => 'MD',
	      'Monaco' => 'MC',
	      'Mongolia' => 'MN',
	      'Montenegro' => 'ME',
	      'Montserrat' => 'MS',
	      'Morocco' => 'MA',
	      'Mozambique' => 'MZ',
	      'Myanmar' => 'MM',
	      'Namibia' => 'NA',
	      'Nauru' => 'NR',
	      'Nepal' => 'NP',
	      'Netherlands' => 'NL',
	      'Netherlands Antilles' => 'AN',
	      'New Caledonia' => 'NC',
	      'New Zealand' => 'NZ',
	      'Nicaragua' => 'NI',
	      'Niger' => 'NE',
	      'Nigeria' => 'NG',
	      'Niue' => 'NU',
	      'Norfolk Island' => 'NF',
	      'Northern Mariana Islands' => 'MP',
	      'Norway' => 'NO',
	      'Oman' => 'OM',
	      'Pakistan' => 'PK',
	      'Palau' => 'PW',
	      'Palestinian Territory, Occupied' => 'PS',
	      'Panama' => 'PA',
	      'Papua New Guinea' => 'PG',
	      'Paraguay' => 'PY',
	      'Peru' => 'PE',
	      'Philippines' => 'PH',
	      'Pitcairn' => 'PN',
	      'Poland' => 'PL',
	      'Portugal' => 'PT',
	      'Puerto Rico' => 'PR',
	      'Qatar' => 'QA',
	      'Réunion' => 'RE',
	      'Romania' => 'RO',
	      'Russian Federation' => 'RU',
	      'Russia' => 'RU',
	      'Rwanda' => 'RW',
	      'Saint Helena' => 'SH',
	      'Saint Kitts and Nevis' => 'KN',
	      'Saint Lucia' => 'LC',
	      'Saint Pierre and Miquelon' => 'PM',
	      'Saint Vincent and the Grenadines' => 'VC',
	      'Samoa' => 'WS',
	      'San Marino' => 'SM',
	      'Sao Tome and Principe' => 'ST',
	      'Saudi Arabia' => 'SA',
	      'Senegal' => 'SN',
	      'Serbia' => 'RS',
	      'Seychelles' => 'SC',
	      'Sierra Leone' => 'SL',
	      'Singapore' => 'SG',
	      'Slovakia' => 'SK',
	      'Slovenia' => 'SI',
	      'Solomon Islands' => 'SB',
	      'Somalia' => 'SO',
	      'South Africa' => 'ZA',
	      'South Georgia and the South Sandwich Islands' => 'GS',
	      'Spain' => 'ES',
	      'Sri Lanka' => 'LK',
	      'Sudan' => 'SD',
	      'Suriname' => 'SR',
	      'Svalbard and Jan Mayen' => 'SJ',
	      'Scotland' => 'SS',
	      'Swaziland' => 'SZ',
	      'Sweden' => 'SE',
	      'Switzerland' => 'CH',
	      'Syrian Arab Republic' => 'SY',
	      'Taiwan, Province of China' => 'TW',
	      'Tajikistan' => 'TJ',
	      'Tanzania, United Republic of' => 'TZ',
	      'Thailand' => 'TH',
	      'Timor-Leste' => 'TL',
	      'Togo' => 'TG',
	      'Tokelau' => 'TK',
	      'Tonga' => 'TO',
	      'Trinidad and Tobago' => 'TT',
	      'Tunisia' => 'TN',
	      'Turkey' => 'TR',
	      'Turkmenistan' => 'TM',
	      'Turks and Caicos Islands' => 'TC',
	      'Tuvalu' => 'TV',
	      'Uganda' => 'UG',
	      'Ukraine' => 'UA',
	      'United Arab Emirates' => 'AE',
	      'United Kingdom' => 'GB',
	      'United States' => 'US',
	      'United States Minor Outlying Islands' => 'UM',
	      'Uruguay' => 'UY',
	      'Uzbekistan' => 'UZ',
	      'Vanuatu' => 'VU',
	      'Venezuela' => 'VE',
	      'Viet Nam' => 'VN',
	      'Virgin Islands, British' => 'VG',
	      'Virgin Islands, U.S.' => 'VI',
	      'Wales' => 'WA',
	      'Wallis and Futuna' => 'WF',
	      'Western Sahara' => 'EH',
	      'Yemen' => 'YE',
	      'Zambia' => 'ZM',
	      'Zimbabwe' => 'ZW'
	    );
		if (isset($countrycodes[$country]))	
			return $countrycodes[$country];
		return '_noflag';
	}

	function hide_requests_from_dashboard($request_id)
	{
		$userdata = $this->session->userdata('userdata');

		if (!isset($userdata['hidden_dashboard_requests']))
		{
			$userdata['hidden_dashboard_requests'] = array();
		}

		$index = array_search($request_id, $userdata['hidden_dashboard_requests']);

		if ($index === FALSE)
		{
			$userdata['hidden_dashboard_requests'][] = $request_id;
		}

		return $this->tank_auth->set_userdata($userdata);
	}
}

/* End of file requests_model.php */
/* Location: ./application/models/requests_model.php */