<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Student Model
 *
 * This model updates retrieves and sets data for tutors. 
 *
 */
class Student_model extends CI_Model {
	private $user_id;

	function __construct() {
		parent::__construct();
		$this->load->model('tank_auth/users');
	}

	function get_student($term, $by = 'username') 
	{
		if ($by == 'username')
		{
			$term = urldecode($term);
			$user = (array) $this->users->get_user_by_username($term);

			if (!$user)
				return NULL;

			$user['avatar_url'] = base_url($user['avatar_path']);
			$user['reviews'] = $this->profile_model->get_reviews($user['id'], 'reviewer');

			if ($user['role'] != ROLE_STUDENT)
			{
				$user['profile_made'] = $this->profile_model->profile_made($user['id']);
			}

	    	$user['requests'] = $this->requests_model->get_short_requests($user['id']);
		}
		else
		{
			return NULL;
		}
		return $user;
	}

	function make_student_from_contact($email, $first_name)
	{
		// First name is listed as "Your Name" on the form

		$pass = $this->tank_auth->make_random_password();
		$username = $this->tank_auth->make_username($first_name);

		if (!$username)		// If username can't be made, then user entered some gibberish that was stripped (e.g. '@#$%^&*)
		{
			return false;
		}

		$data = array(
			'first_name' => $first_name,
			'display_name' => $first_name,
			'username' => $username
		);

		return $this->make_student($email, $pass, $data);
	}

	function make_student($email, $pass, $data = array())
	{
		$data['role'] = ROLE_STUDENT;
		
		if ($this->tank_auth->create_user($email, $pass, $data, FALSE))
		{
			return $this->tank_auth->forgot_password($email);	
		}
		return NULL;
	}

	function has_tutors($user_id)
	{
		$this->db->select('1', FALSE)
				 ->from('students_tutors')
				 ->where('student_id', $user_id)
				 ->where('tutor_id !=', DELETED_ID)
				 ->where_in('status', array(STUDENT_STATUS_ACTIVE, STUDENT_STATUS_PENDING, STUDENT_STATUS_PAST, STUDENT_STATUS_NEEDS_APPROVAL))
				 ->get();
		return $this->db->affected_rows();
	}

	function update_review($commented_user_id, $commenter_id, $review_data)
	{
		$this->db->trans_start(IS_TEST_MODE);

		$this->db->select('id')
				 ->from('profile_comments')
				 ->where('commenter_id', $commenter_id)
				 ->where('commented_user_id', $commented_user_id);

		$row = $this->db->get()->row();

		$data = array(
			'commenter_id' => $commenter_id,
			'commented_user_id' => $commented_user_id,
			'content' => $review_data['content']
		);

		if ($row)
		{
			$data['id'] = $row->id;			
		}
/*
		if ($this->session->userdata('init') == TRUE)
		{
			$this->load->model('admin_model');

			$this->db->select('contacted')
					 ->from('students_tutors')
					 ->where('student_id', $commenter_id)
					 ->where('tutor_id', $commented_user_id);

			$contacted = $this->db->get()->row()->contacted;

			$data['posted'] = $this->admin_model->generate_datetime($contacted);
		}
*/
		$this->db->replace('profile_comments', $data);

		$comment_id = $this->db->insert_id();
		
		$data = array(
			'reviewed_user_id' => $commented_user_id,
			'profile_comment_id' => $comment_id,
			'rating' => $review_data['rating'],
			'expertise' => $review_data['expertise'],
			'helpfulness' => $review_data['helpfulness'],
			'response' => $review_data['response'],
			'clarity' => $review_data['clarity']
		);

		$this->db->replace('user_reviews', $data);

		$this->tutor_model->update_average_rating($commented_user_id);

		$this->db->trans_complete();

		if ($this->db->trans_status())
			return $this->form_validation->response(STATUS_OK, array('rating' => $review_data['rating']));

		return $this->form_validation->response(STATUS_DATABASE_ERROR);
	}

	function delete_review($commented_user_id, $commenter_id)
	{
		$this->db->trans_start(IS_TEST_MODE);

		$this->db->select('id')
				 ->from('profile_comments')
				 ->where('commenter_id', $commenter_id)
				 ->where('commented_user_id', $commented_user_id);

		$comment_id = $this->db->get()->row()->id;

		$this->db->delete('profile_comments', array('id' => $comment_id));
		$this->db->delete('user_reviews', array('profile_comment_id' => $comment_id));
		
		$this->tutor_model->update_average_rating($commented_user_id);

		$this->db->trans_complete();

		if ($this->db->trans_status())
			return $this->form_validation->response(STATUS_OK);

		return $this->form_validation->response(STATUS_DATABASE_ERROR);
	}

	function save_tutor_notes($tutor_id, $student_id, $tutor_notes)
	{
		$this->db->select('tutor_notes')
				 ->from('students_tutors')
				 ->where('student_id', $student_id)
				 ->where('tutor_id', $tutor_id);

		$current_tutor_notes = $this->db->get()->row_array();
		$current_tutor_notes = $current_tutor_notes['tutor_notes'];
				 
		if ($tutor_notes == $current_tutor_notes)
		{
			return TRUE;
		}

		$this->db->where('student_id', $student_id)
				 ->where('tutor_id', $tutor_id)
				 ->update('students_tutors', array('tutor_notes' => $tutor_notes));

		return $this->db->affected_rows() > 0;
	}

	function show_hide_student($student_id, $tutor_id, $hide = TRUE)
	{
		$userdata = $this->tank_auth->get_userdata($tutor_id);

//		var_dump($userdata['hidden_past_student_ids']);
//		return;

//		var_dump($favourite);

		// We do this outside of the conditional in case some tinkerer changes the HTML data attribute for favourited
		if (!isset($userdata['hidden_past_student_ids']))
		{
			$userdata['hidden_past_student_ids'] = array();
		}

		$index = array_search($student_id, $userdata['hidden_past_student_ids']);

//		var_dump($student_id, $hide, $index, $userdata['hidden_past_student_ids']);
//		return;

		if ($hide && $index === FALSE)
		{
			$userdata['hidden_past_student_ids'][] = $student_id;
		}
		elseif ($index !== FALSE)
		{
			unset($userdata['hidden_past_student_ids'][$index]);
			$userdata['hidden_past_student_ids'] = array_values($userdata['hidden_past_student_ids']);
		}

		return $this->tank_auth->set_userdata($userdata, $tutor_id);
	}
}

/* End of file tutor_model.php */
/* Location: ./application/models/tutor_model.php */