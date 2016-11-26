<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin Model
 *
 */
class Admin_model extends CI_Model
{
	private $user_id;

	function __construct()
	{
		parent::__construct();
		$this->user_id = $this->session->userdata('user_id');
		$this->load->model('data_model');
	}

	function get_admin_data()
	{
		if ($this->session->userdata('init') != TRUE)
		{
			show_404();
			return;
		}

		$admin = array();

		$this->db->from('users')
				 ->where('role', ROLE_ADMIN)
				 ->where('init', TRUE);
		$admin['num_of_init_tutors'] = $this->db->count_all_results();

		$this->db->from('users u')
				 ->join('tutor_profiles tp', 'u.id = tp.user_id')
				 ->where('role', ROLE_TUTOR)
				 ->where('init', FALSE)
				 ->where('tp.profile_made', FALSE);
		$admin['num_of_fin_not_made_tutors'] = $this->db->count_all_results();

		$this->db->from('users u')
				 ->join('tutor_profiles tp', 'u.id = tp.user_id')
				 ->where('role', ROLE_TUTOR)
				 ->where('init', FALSE)
				 ->where('tp.profile_made', TRUE);
		$admin['num_of_fin_made_tutors'] = $this->db->count_all_results();

		$this->db->from('users')
				 ->where('role', ROLE_STUDENT)
				 ->where('init', TRUE);
		$admin['num_of_init_students'] = $this->db->count_all_results();

		$this->db->from('users')
				 ->where('role', ROLE_STUDENT)
				 ->where('init', FALSE);
		$admin['num_of_fin_students'] = $this->db->count_all_results();

		$admin['tutor_contacts'] = $this->db->select('st.id AS st_id, st.student_id, st.tutor_id, st.message, st.contacted, u.display_name AS student_name, u.email AS student_email, u.username AS student_username')
											->from('students_tutors st')
											->where('st.reviewed', false)
											->join('users u', 'u.id = st.student_id')
											->get()->result_array();

		foreach($admin['tutor_contacts'] as &$contact)
		{
			$tutor_id = $contact['tutor_id'];
			$tutor = $this->tutor_model->get_tutor($tutor_id, 'id');

			$contact['tutor_name'] = $tutor['display_name'];
			$contact['tutor_username'] = $tutor['username'];
			$contact['tutor_email'] = $tutor['email'];
		}

		$admin['reports'] = $this->db->select('r.id, r.type, r.username_or_item_id, r.message')
									 ->from('reports r')
									 ->get()->result_array();

		foreach($admin['reports'] as &$report)
		{
//			var_dump($report);
			if ($report['type'] == REPORT_TYPE_REVIEW || $report['type'] == REPORT_TYPE_EXTERNAL_REVIEW)
			{
				if ($report['type'] == REPORT_TYPE_REVIEW)
				{
					$report['type'] = 'Review';

					$this->db->select('pc.content, u.username')
							 ->from('user_reviews ur')
							 ->where('ur.id', $report['username_or_item_id'])
		 					 ->join('profile_comments pc', 'ur.profile_comment_id = pc.id')
							 ->join('users u', 'u.id = pc.commented_user_id', 'left');
				}
				else
				{
					$report['type'] = 'External Review';

					$this->db->select('uer.content, u.username')
							 ->from('user_external_reviews uer')
							 ->where('uer.id', $report['username_or_item_id'])
							 ->join('users u', 'u.id = uer.user_id');
				}

				$data = $this->db->get()->row_array();

				$report['content'] = $data['content'];
				$report['link'] = base_url('tutors/'.$data['username']);
			}
			elseif ($report['type'] == REPORT_TYPE_TUTOR)
			{
				$report['type'] = 'Tutor';
				$report['content'] = ' ';
				$report['link'] = base_url('tutors/'.$report['username_or_item_id']);					
			}
			elseif ($report['type'] == REPORT_TYPE_REQUEST)
			{
				$report['type'] = 'Request';
				$report['content'] = ' ';
				$report['link'] = base_url('requests/'.$report['username_or_item_id']);	
			}
		}
		unset($report);

//		var_dump($admin['tutor_contacts']);


		$admin['contacts'] = $this->db->get('contacts')->result_array();
		$admin['subjects'] = $this->db->where('status', ITEM_STATUS_PENDING)
									  ->get('subjects')->result_array();

		$admin['new_tutors'] = $this->db->select('u.id, u.username, u.display_name AS name, ul.city, ul.country')
											->from('users u')
											->join('tutor_profiles tp', 'tp.user_id = u.id')
											->join('user_locations ul', 'ul.user_id = u.id')
											->where('tp.is_active', TRUE)
											->where('tp.shared', FALSE)
											->where('u.init', FALSE)
											->get()->result_array();

		$admin['new_requests'] = $this->db->select('r.id, r.details, u.display_name AS name, location_city city, location_country country, s.name AS subject')
											->from('users u')
											->join('requests r', 'r.user_id = u.id')
											->join('requests_subjects rs', 'rs.request_id = r.id')
											->join('subjects s', 's.id = rs.subject_id')
											->where('r.shared', FALSE)
											->where('u.init', FALSE)
											->get()->result_array();

		$unfiltered_data = array(
			'locations' => $this->data_model->get_all_items('locations'),
			'degrees' => $this->data_model->get_all_items('degrees'),
			'fields' => $this->data_model->get_all_items('fields'),
			'schools' => $this->data_model->get_all_items('schools'),
//			'positions' => $this->data_model->get_all_items('positions'),
//			'companies' => $this->data_model->get_all_items('companies'),
		);

		$admin['data'] = array();

		foreach($unfiltered_data as $table => $data)
		{
			foreach($data as $item)
			{
				$item['table'] = $table;
				$admin['data'][] = $item;
			}
		}

		return $admin;
	}

	function make_admin($first_name, $last_name, $role)
	{
		$display_name = $first_name.' '.$last_name;
		$userdata = array(
			'first_name' => $first_name,
			'last_name' => $last_name,
			'display_name' => $display_name,
			'username' => $this->tank_auth->make_username($first_name.' '.$last_name),
			'password' => $this->tank_auth->make_password('zeldalinkhorse'),
			'created' => $this->generate_datetime(),
			'role' => $role,
			'email' => ($role == ROLE_STUDENT ? 'capricanian@gmail.com' : 'kobol.tutor@gmail.com'),
			'init' => TRUE,
			'prefs' => json_encode($this->tank_auth->get_prefs(array('redirect' => 'account/profile')))
		);

		if (!is_null($this->users->create_user($userdata)))
		{
			$userdata['unhashed_password'] = 'zeldalinkhorse';
			return $userdata;
		}
		return NULL;
	}

	function generate_datetime($start = NULL, $end = NULL)
	{
		if (is_null($start))
		{
			$start = strtotime('January 1, 2013');			
		}
		else
		{
			$start = strtotime($start);
		}
		if (is_null($end))
		{
			$end = time();			
		}
		else
		{
			$end = strtotime($end);
		}
	
		$time_stamp = mt_rand($start, $end);
		$datetime = date("Y-m-d H:i:s", $time_stamp);	
		return $datetime;
	}

	function clear_tutor_contact($st_id)
	{
		$this->db->where('id', $st_id)
				 ->update('students_tutors', array('reviewed' => true));
		return $this->db->affected_rows() > 0;
	}

	// To be removed
	function share($type, $data)
	{
		return;

		$id = $data['id'];

		if ($this->set_shared_to_true($type, $id))
		{

			include(APPPATH.'libraries/tmhOAuth/tmhOAuth.php');
			include(APPPATH.'libraries/tmhOAuth/tmhUtilities.php');

			if ($type == 'tutor')
			{
				$link = base_url('tutors/'.$data['username']);
				$link = 'tutorical.com/tutors/'.$data['username'];
				$text = 'New tutor in '.$data['city'].', '.$data['country'].' - '.$data['name'].'. Welcome! | '.$link;
			}
			else
			{
//				$link = base_url('requests/'.$data['id']);
				$link = 'tutorical.com/requests/'.$data['id'];
				$text = 'New request for a '.$data['subject'].' tutor in '.$data['city'].', '.$data['country'].' | '.$link;				
			}

			$tmhOAuth = new tmhOAuth(array(
			  'consumer_key' => 'OUxRmzrIX3ixI0QCkRGGA',
			  'consumer_secret' => '0IgVeZTDxzDhFz8rBG37hieDncncxsrCFIPkPgLu8',
			  'user_token' => '1003138632-ng8r53t8uZAgR350XFIDvPnmIaVE7Ryg6uq9CYX',
			  'user_secret' => 'lX1AXvpgvR6Yv7qLPFeVZCeM406QkJIVpXzYRAvpNw',
			));

			$response = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array(
			  'status' => $text
			));

			if ($response != 200) 
			{
				return FALSE;
			}

			return TRUE;
		}
	}

	function set_shared_to_true($type, $id)
	{		
		$data = array('shared' => TRUE);

		if ($type == 'tutor')
		{
			$this->db->where('user_id', $id)
					 ->update('tutor_profiles', $data);
		}
		else
		{
			$this->db->where('id', $id)
					 ->update('requests', $data);			
		}

		return $this->db->affected_rows() > 0;
	}
	
	function approve_student($st_id)
	{
		$data = array();

		$this->db->select('st.message, u.username, u.email, u.avatar_path, u.display_name AS name')
				 ->from('students_tutors st')
				 ->where('st.id', $st_id)
				 ->join('users u', 'u.id = st.student_id');
		$unfiltered_student_data = $this->db->get()->row_array();

		$this->db->select('u.id, u.email, u.display_name AS name')
				 ->from('students_tutors st')
				 ->where('st.id', $st_id)
				 ->join('users u', 'u.id = st.tutor_id');
		$unfiltered_tutor_data = $this->db->get()->row_array();

		$data = array(
			'student_name' => $unfiltered_student_data['name'],
			'student_email' => $unfiltered_student_data['email'],
			'student_profile_path' => "students/{$unfiltered_student_data['username']}",
			'student_avatar_path' => $unfiltered_student_data['avatar_path'],
			'message' => nl2br($unfiltered_student_data['message']),
			'tutor_id' => $unfiltered_tutor_data['id'],
			'tutor_name' => $unfiltered_tutor_data['name'],
			'tutor_email' => $unfiltered_tutor_data['email']
		);

		$this->db->where('id', $st_id)
				 ->update('students_tutors', array('status' => STUDENT_STATUS_PENDING));

		return $data;
	}

	function add_contact($email, $message)
	{
		return $this->db->insert('contacts', array('email' => $email, 'message' => $message));
	}

	function clear_contact($row_id)
	{
		$this->db->delete('contacts', array('id' => $row_id));

		return $this->db->affected_rows() > 0;
	}

	function delete_report($row_id)
	{
		$this->db->delete('reports', array('id' => $row_id));

		return $this->db->affected_rows() > 0;
	}

	function add_ut_url($import_url)
	{
		$this->db->replace('data_universitytutor_urls', array('name' => $import_url));
		return $this->db->affected_rows() > 0;			
	}

}

/* End of file admin_model.php */
/* Location: ./application/models/admin_model.php */