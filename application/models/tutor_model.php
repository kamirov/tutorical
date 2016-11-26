<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tutor Model
 *
 * This model updates retrieves and sets data for tutors. 
 *
 */
class Tutor_model extends CI_Model {

	function __construct() {
		parent::__construct();
		//$ci =& get_instance();
		$this->load->model('tank_auth/users');
	}

	function get_tutor($term, $by = 'username', $args = NULL) 
	{
		if ($by == 'username')
		{
			$term = urldecode($term);
			$user = $this->users->get_user_by_username($term);

			if (!$user || $user->role == ROLE_STUDENT)
			{
				return NULL;
			}
			else
			{
				$user_id = $user->id;
			}
		}
		else
		{
			$user_id = $term;
		}

		$this->load->model('profile_model');
		$profile = $this->profile_model->get_profile($user_id, $args);
		$profile['affiliated_request_ids'] = $this->requests_model->get_affiliated_request_ids($user_id);

		return $profile;
	}

	function tutor_active($user_id)
	{
		$this->db->select('is_active')
				 ->from('tutor_profiles')
				 ->where('user_id', $user_id);

		$tutor = $this->db->get()->row_array();		

		if ($tutor && $tutor['is_active'])
		{
			return TRUE;
		}
		return FALSE;
	}

	function tutor_profile_made($user_id)
	{
		$this->db->select('profile_made')
				 ->from('tutor_profiles')
				 ->where('user_id', $user_id);

		$tutor = $this->db->get()->row_array();		

		if ($tutor && $tutor['profile_made'])
		{
			return TRUE;
		}
		return FALSE;
	}

	function count($args) 
	{
		$defaults = array(
			'lat' => NULL,
			'lon' => NULL,
			'subject_id' => NULL,
			'sort' => 'distance',
			'distance' => DEFAULT_FIND_DISTANCE,
			'units' => 'km',
			'limit_from' => 0,
			'limit_count' => 5
		);

		$opts = array_merge($defaults, $args);
		$lat = $opts['lat'];
		$lon = $opts['lon'];

		if (!($lat && $lon))
		{
			return NULL;
		}

		if ($opts['units'] == 'km')
		{
			$mult = 6371;	// km multiplier
		}
		else
			$mult = 3959;	// miles multiplier

		$subjects_clause = ($opts['subject_id'] ? " AND us.subject_id = {$opts['subject_id']}" : '');

		$sql = "
			SELECT COUNT(*) AS tutor_count FROM
			(
			SELECT DISTINCT tp.id, 
				($mult
				* acos
				( 
				cos(radians($lat)) 
				* cos(radians(ul.lat)) 
				* cos(radians(ul.lon) - radians($lon)) 
				+ sin(radians($lat)) 
				* sin(radians(ul.lat))
				) 
				) AS distance
				FROM `tutor_profiles` tp
				JOIN `user_locations` ul ON `tp`.`user_id` = `ul`.`user_id`
				JOIN `users_subjects` us ON `tp`.`user_id` = `us`.`user_id`
				WHERE `tp`.`is_active` = 1
				$subjects_clause
				HAVING `distance` <= {$opts['distance']}
			) AS tmp
		";

//		var_export(nl2br($sql));

		$query = $this->db->query($sql);

		$count = $query->row()->tutor_count;

		return $count;
	}

	function find($args) 
	{	
		$defaults = array(
			'lat' => NULL,
			'lon' => NULL,
			'subject_id' => NULL,
			'sort' => 'distance',
			'distance' => DEFAULT_FIND_DISTANCE,
			'units' => 'km',
			'limit_from' => 0,
			'limit_count' => 5
		);

		$opts = array_merge($defaults, $args);

		$lat = $opts['lat'];
		$lon = $opts['lon'];

		if (!($lat && $lon))
		{
			return NULL;
		}

		if ($opts['units'] == 'km')
		{
			$mult = 6371;	// km multiplier
		}
		else
			$mult = 3959;	// miles multiplier

		$tutor_results = array(
			'lats' => array(),
			'lons' => array(),
			'tutors' => array()
		);

		$this->db
				->distinct()
				->select("
					tp.*,
					up.price_type, up.price, up.price_high, up.currency, up.notes AS price_notes,
					u.username, u.display_name, u.avatar_path,
					ul.user_id, ul.lat, ul.lon,
					($mult
					 * acos
						( 
							  cos(radians($lat)) 
							* cos(radians(ul.lat)) 
							* cos(radians(ul.lon) - radians($lon)) 
							+ sin(radians($lat)) 
							* sin(radians(ul.lat))
						) 
					) AS distance
				")
				->from('tutor_profiles tp')
				->join('user_locations ul', 'tp.user_id = ul.user_id')
				->join('user_prices up', 'up.user_id = ul.user_id')
				->join('users u', 'up.user_id = u.id')
				->join('users_subjects us', 'us.user_id = u.id')
				->where('tp.is_active', TRUE)
				->having('distance <= '.$opts['distance'])
				->limit($opts['limit_count'], $opts['limit_from']);

		if ($opts['subject_id'])
			$this->db->where('us.subject_id', $opts['subject_id']);

		if ($opts['sort'] == 'distance')
			$this->db->order_by('distance', 'asc');
		elseif ($opts['sort'] == 'price')
			$this->db->order_by('price asc, distance asc');

		$result = $this->db->get()->result_array();

//		var_export(nl2br($this->db->last_query()));

		$cur_marker_letter = 'A';

		foreach($result as $tutor) 
		{	
			$tutor['avatar_url'] = base_url($tutor['avatar_path']); 
			$tutor['marker_url'] = 'http://www.google.com/mapfiles/marker'.$cur_marker_letter++.'.png';
				
			$tutor['distance'] = round($tutor['distance'], 2);

			if ($tutor['distance'] < 0.1)
				$tutor['distance'] = 'Very close';
			elseif ($tutor['distance'] == 1 && $opts['units'] == 'miles')
				$tutor['distance'] .= ' mile away';				
			else
				$tutor['distance'] .= ' '.$opts['units'].' away';

			$tutor_results['tutors'][] = $tutor;
			$tutor_results['lats'][] = $tutor['lat'];
			$tutor_results['lons'][] = $tutor['lon'];
		}
		$tutor_results['current_page_count'] = count($tutor_results['tutors']);
		return $tutor_results;
	}

	function confirm_tutor_has_subject($user_id, $subject_id)
	{
		$this->db->select('1', FALSE)
				 ->where('user_id', $user_id)
				 ->where('subject_id', $subject_id);

		$query = $this->db->get('users_subjects');

//		var_export($this->db->last_query());

		return ($query->num_rows() == 1);
	}

	function get_tutors_subjects($user_id) {
		$this->db
				->select('s.id subject_id, s.name, us.id users_subjects_id')
				->from('users_subjects us')
				->join('subjects s', 's.id = us.subject_id')
				->where('us.user_id', $user_id)
				->order_by('name', 'ASC');		
		return $this->db->get()->result_array();
	}

	function get_tutors_availability($user_id) {
		$this->db
				->select('time, mon, tue, wed, thu, fri, sat, sun')
				->from('user_availabilities ua')
				->where('ua.user_id', $user_id);

		return $this->db->get()->result_array();
	}

	function toggle_profile() {
		$sql = 'UPDATE tutor_profiles 
						SET is_active = IF(is_active=1, 0, 1)
						WHERE user_id = ?';

		$this->db->query($sql, array($this->session->userdata('user_id')));

		return $this->db->affected_rows();

	}

	function is_profile_made($user_id) {
		$this->db->select('1', FALSE)
				 ->from('tutor_profiles')
				 ->where('profile_made', TRUE)
				 ->where('user_id', $user_id)
				 ->get();
		return $this->db->affected_rows();
	}

	function has_students($user_id)
	{
		$this->db->select('1', FALSE)
				 ->from('students_tutors')
				 ->where('tutor_id', $user_id)
				 ->where('student_id !=', DELETED_ID)
				 ->where_in('status', array(STUDENT_STATUS_ACTIVE, STUDENT_STATUS_PENDING, STUDENT_STATUS_PAST))
				 ->get();
		return $this->db->affected_rows();
	}

	function add_student($student_id, $tutor_id, $data = array())
	{
		if ($student_id == $tutor_id)
			return FALSE;

		$data['student_id'] = $student_id;
		$data['tutor_id'] = $tutor_id;

		if($this->db->replace('students_tutors', $data))
		{
//			$this->profile_notices_model->add_notice(NEW_STUDENT_ADDED, $tutor_id, 1000);
//			return TRUE;

			$this->load->model('student_model');
			$this->student_model->show_hide_student($data['student_id'], $data['tutor_id'], FALSE);
			$this->show_hide_tutor($data['tutor_id'], $data['student_id'], FALSE);

			if ($data['status'] == STUDENT_STATUS_ACTIVE)
			{
				$this->add_worked_with($data['student_id'], $data['tutor_id']);
			}

			return array('students_tutors_id' => $this->db->insert_id());
		}
		return FALSE;
	}
// ur.expertise, ur.helpfulness, ur.response, ur.charity, ur.rating
	function get_contacts($type, $user_id, $status = STUDENT_STATUS_ACTIVE, $hide_array = NULL)
	{
//		var_dump($user_id);

		$this->db->start_cache();
		$this->db->select('st.contacted, st.message, st.tutor_notes, st.student_notes,
						   u.id AS contact_id, u.username, u.email, u.avatar_path, u.display_name, u.role,
						   pc.content review_content,
						   r.expertise review_expertise, r.helpfulness review_helpfulness, r.response review_response, r.clarity review_clarity, r.rating review_rating')
				 ->from('students_tutors st');

		if (is_array($status))
			$this->db->where_in('status', $status);
		else
			$this->db->where('status', $status);


		$this->db->stop_cache();

		if ($type == 'student')
		{
			$this->db->select('st.student_id as id')
					 ->where('tutor_id', $user_id)
					 ->where('u.id !=', DELETED_ID)
					 ->join('users u', 'st.student_id = u.id')
				     ->join('profile_comments pc', 'pc.commenter_id = st.student_id AND pc.commented_user_id = st.tutor_id', 'left');
		}
		else
		{
			$this->db->select('st.tutor_id as id')
					 ->where('student_id', $user_id)
					 ->where('u.id !=', DELETED_ID)
					 ->join('users u', 'st.tutor_id = u.id')
				     ->join('profile_comments pc', 'pc.commented_user_id = st.tutor_id  AND pc.commenter_id = st.student_id', 'left');
		}

		if ($hide_array)
		{
			$this->db->where_not_in($type.'_id', $hide_array);
		}

		$this->db->join('user_reviews r', 'r.profile_comment_id = pc.id', 'left')
				 ->order_by('st.contacted', 'DESC');


		$contacts = $this->db->get()->result_array();

//		echo $this->db->last_query();

//		var_dump($contacts);

//		echo $this->db->last_query();

		$this->db->flush_cache();

		foreach($contacts as &$contact)
		{
			$review = array(
				'content' => $contact['review_content'],
				'expertise' => $contact['review_expertise'],
				'helpfulness' => $contact['review_helpfulness'],
				'response' => $contact['review_response'],
				'clarity' => $contact['review_clarity'],
				'rating' => $contact['review_rating'],
			);

			$contact['review'] = json_encode($review);
			$contact['avatar_url'] = base_url($contact['avatar_path']);

			if ($type == 'student')
			{
				$contact['profile_path'] = 'students/'.$contact['username'];
			}
			else
			{
				$contact['profile_path'] = 'tutors/'.$contact['username'];
			}

			unset($contact['review_content'], $contact['review_expertise'], $contact['review_helpfulness'], $contact['review_response'], $contact['review_clarity'], $contact['review_rating'], $contact['avatar_path']);
		}
		unset($contact);

		return $contacts;
	}

	function add_worked_with($student_id, $tutor_id)
	{
		// We don't have to update sessions here because the students and tutors pages don't used the session userdata, but get from DB each time

		// Update tutor's info
		$userdata = $this->tank_auth->get_userdata($tutor_id);
		$worked_with_ids = $userdata['worked_with_ids'];

		if (!in_array($student_id, $worked_with_ids))
		{
			$userdata['worked_with_ids'][] = $student_id;
			$this->tank_auth->set_userdata($userdata, $tutor_id);
		}

		// Now update student's info
		$userdata = $this->tank_auth->get_userdata($student_id);
		$worked_with_ids = $userdata['worked_with_ids'];
		if (!in_array($tutor_id, $worked_with_ids))
		{
			$userdata['worked_with_ids'][] = $tutor_id;
			$this->tank_auth->set_userdata($userdata, $student_id);
		}
	}

	// On students page
	function update_tutors_student_status($student_id, $tutor_id, $status)
	{		
//		var_dump(func_get_args());
		if ($status == STUDENT_STATUS_ACTIVE)
		{
			$this->load->model('student_model');

			$this->student_model->show_hide_student($student_id, $tutor_id, FALSE);
			$this->show_hide_tutor($tutor_id, $student_id, FALSE);

			$this->add_worked_with($student_id, $tutor_id);

			// This is a hack. Eventually, replace show_hide_x with one func for both types
		}

//		return $this->form_validation->response();

		$this->db->where('student_id', $student_id)
				 ->where('tutor_id', $tutor_id)
				 ->update('students_tutors', array('status' => $status));

		if ($this->db->affected_rows() > 0)
			return $this->form_validation->response();
		return $this->form_validation->response(STATUS_DATABASE_ERROR);
	}

	// On tutors page
	function update_students_tutor_status($tutor_id, $student_id, $status)
	{		
//		return $this->form_validation->response();

		// Glitch here: a student can do their own ajax request for a pending tutor, changing their status to active, then get the tutor's contact info. Best to do a check to make sure that student is not changing status when status is PENDING

		if ($status == STUDENT_STATUS_PENDING)
		{
			$this->load->model('student_model');
			$this->student_model->show_hide_student($student_id, $tutor_id, FALSE);
		}

		$this->db->where('tutor_id', $tutor_id)
				 ->where('student_id', $student_id)
				 ->update('students_tutors', array('status' => $status));

		if ($this->db->affected_rows() > 0)
			return $this->form_validation->response();

		/* If here, then no rows updated -> failure somewhere. 

		Best not to remove from worked_with_ids if they're present BECAUSE:
			1) If a student is activating a tutor, then they already worked with them before
			2) If we unset the worked with ID here, then we run the risk of having a failed tutor re-request hide the tutor's contact info from a student
		*/

		return $this->form_validation->response(STATUS_DATABASE_ERROR);
	}

	function update_average_rating($tutor_id)
	{
		$query = $this->db->query("SELECT AVG(rating) AS average, COUNT(rating) AS num_of_reviews
									FROM user_reviews
									WHERE reviewed_user_id = ".$this->db->escape($tutor_id));

		$num_of_reviews = $query->row()->num_of_reviews;

		$average = $query->row()->average;
		$average = floor($average * 2) / 2;		// Get nearest half-integer

		$this->db->where('user_id', $tutor_id)
				 ->update('tutor_profiles', array('average_rating' => $average, 'num_of_reviews' => $num_of_reviews));
	}

	function save_student_notes($student_id, $tutor_id, $student_notes)
	{
		$this->db->select('student_notes')
				 ->from('students_tutors')
				 ->where('student_id', $student_id)
				 ->where('tutor_id', $tutor_id);

		$current_student_notes = $this->db->get()->row_array();
		$current_student_notes = $current_student_notes['student_notes'];
				 
		if ($student_notes == $current_student_notes)
		{
			return TRUE;
		}

		$this->db->where('student_id', $student_id)
				 ->where('tutor_id', $tutor_id)
				 ->update('students_tutors', array('student_notes' => $student_notes));

		return $this->db->affected_rows() > 0;
	}

	function update_favourites($tutor_id, $favourite)
	{
		$userdata = $this->session->userdata('userdata');

//		var_dump($favourite);

		// We do this outside of the conditional in case some tinkerer changes the HTML data attribute for favourited
		if (!isset($userdata['favourited_tutor_ids']))
		{
			$userdata['favourited_tutor_ids'] = array();
		}

		$index = array_search($tutor_id, $userdata['favourited_tutor_ids']);

		if ($favourite & $index === FALSE)
		{
			$userdata['favourited_tutor_ids'][] = $tutor_id;
		}
		elseif ($index !== FALSE)
		{
			unset($userdata['favourited_tutor_ids'][$index]);
			$userdata['favourited_tutor_ids'] = array_values($userdata['favourited_tutor_ids']);	
		}

//		var_dump($tutor_id, $favourite, $userdata);

		return $this->tank_auth->set_userdata($userdata);
	}

	function get_featured_tutor_details($tutor_ids)
	{
		if (!$tutor_ids)
			return array();
		
		$this->db->select('id, username, avatar_path, display_name')
				 ->where_in('id', $tutor_ids);
		
		$tutors = $this->db->get('users')->result_array();

		return $tutors;
	}

	function show_hide_tutor($tutor_id, $student_id, $hide = TRUE)
	{
		// Don't use session. Data might have been changed by tutor
//		$user_id = $this->session->userdata('user_id');
		$userdata = $this->tank_auth->get_userdata($student_id);

//		var_dump($favourite);

		// We do this outside of the conditional in case some tinkerer changes the HTML data attribute for favourited
		if (!isset($userdata['hidden_past_tutor_ids']))
		{
			$userdata['hidden_past_tutor_ids'] = array();
		}

		$index = array_search($tutor_id, $userdata['hidden_past_tutor_ids']);

		if ($hide && $index === FALSE)
		{
			$userdata['hidden_past_tutor_ids'][] = $tutor_id;
		}
		elseif ($index !== FALSE)
		{
			unset($userdata['hidden_past_tutor_ids'][$index]);
			$userdata['hidden_past_tutor_ids'] = array_values($userdata['hidden_past_tutor_ids']);
		}

		return $this->tank_auth->set_userdata($userdata, $student_id);
	}

}

/* End of file tutor_model.php */
/* Location: ./application/models/tutor_model.php */