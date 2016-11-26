<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Signup Model
 *
 * This model updates database during signup process. 
 *
 */
class Signup_model extends CI_Model
{
	private $user_id;

	function __construct()
	{
		parent::__construct();
		//$ci =& get_instance();
		$this->user_id = $this->session->userdata('user_id');
	}

	function create_signup()
	{
		$data = array
		(
			'user_id' => $this->user_id
		);

		$this->db->insert('user_signup', $data);

		return $this->db->affected_rows();
	}

	function update_step_1($post)
	{
		$this->db->trans_start(IS_TEST_MODE);



		if (isset($post['in_signup']))
		{
			// Update user_signup
			$data = array(
				'step_1' => SIGNUP_STEP_COMPLETE
			);
			$this->db->update('user_signup', $data, 'user_id = '.$this->user_id);

			$data = array(
				'step_2' => SIGNUP_STEP_STARTED
			);
			$this->db->update('user_signup', $data, 'user_id = '.$this->user_id);

			// Insert data into tutor_profiles
			$data = array
			(
				'user_id' => $this->user_id
			);

			$this->db->replace('tutor_profiles', $data);
		}
		
		// Insert data into user_prices
		$data = array
		(
			'user_id' => $this->user_id,
			'price_type' => $post['price-type'],
			'subject_id' => 0	// 0 for standard price
		);

		if ($post['price-type'] == 'per_hour')
		{
			$hourly_rate = preg_replace('/[^0-9.]/i', '', $post['hourly-rate']);
			$data['price'] = round(floatval($hourly_rate), 2);
			$data['currency'] = $post['currency'];
		}	
		elseif ($post['price-type'] == 'free')
		{
			$data['notes'] = $post['reason'];
		}

		$this->db->replace('user_prices', $data);
		
		// Insert data into user_locations
		$data = array
		(
			'user_id' => $this->user_id,
			'name' => $post['location'],
			'lat' => $post['lat'],
			'lon' => $post['lon'],
			'viewport' => $post['viewport'],
			'country' => $post['country'],
			'city' => $post['city']
		);
		$this->db->replace('user_locations', $data);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function update_step_2($post)
	{
		$this->load->helper('text');

		$this->db->trans_start(IS_TEST_MODE);

		// Split subject into array of subjects
		$inputted_subjects = explode(',', $post['subjects']);

		// Remove new lines and returns
		$inputted_subjects = preg_replace('/\s\s+/', '', $inputted_subjects);

		// Trim each subject
		$inputted_subjects = array_map('trim', $inputted_subjects);

		// Remove empty subject elements
		$inputted_subjects = array_filter($inputted_subjects);

		$inputted_subjects = array_iunique($inputted_subjects);

		// Only proceed if there are still any elements left
		if (count($inputted_subjects) == 0)
		{
			return TRUE;
		}

		$subjects_to_be_removed = array();

		// Get all subjects that tutor already has
		$users_subjects_results = $this->tutor_model->get_tutors_subjects($this->user_id);

		// Remove any subjects from inputted_subjects if tutor already has them added (it means that we've already processed those) 
		$users_subjects = array();
		foreach($users_subjects_results as $users_subject)
		{
			$key = array_searchi($users_subject['name'], $inputted_subjects);
			
			if ($key === FALSE)
			{
				$subjects_to_be_removed[$users_subject['subject_id']] = $users_subject['users_subjects_id'];
			}
			else
			{
				unset($inputted_subjects[$key]);
			}

			array_push($users_subjects, $users_subject['name']);
		}

		if (!empty($subjects_to_be_removed))
		{
			// Decrement occurrences value of subjects_to_be_removed, if there are any
			$this->db->set('occurrences', 'occurrences-1', FALSE)
					 ->where_in('id', array_keys($subjects_to_be_removed))
					 ->update('subjects');

			// If no rows affected, then DB connection problem; abandon ship!
			if ($this->db->affected_rows() === 0)
				return FALSE;

			// Remove subject from users_subjects table
			$this->db->where_in('id', array_values($subjects_to_be_removed))
					 ->delete('users_subjects');

			if ($this->db->affected_rows() === 0)
				return FALSE;
		}

		// If all inputted subjects have already been added to the tutor's subject list, and none have been removed,  then just skip the rest of the process
		if (empty($inputted_subjects))
		{
			$this->db->trans_complete();
			return $this->db->trans_status();
		}

		// Get all subjects that already exist in database; have to store them as separate var because SQL result comes back as a 2D array
		$subjects_in_database_result = $this->subjects_model->get_existing_subjects($inputted_subjects);

		$subjects_in_database = array();
		if (!empty($subjects_in_database_result))
		{
			// Have to save 1D array to $subjects_in_database so that we can array_diff the subjects_in_database from all the subjects
			foreach($subjects_in_database_result as $subject_in_database)
			{
				array_push($subjects_in_database, $subject_in_database['name']);
			}
		}

		// We use array_udiff to do a case-insensitive array_diff (e.g. if 'inputted_subjects' contains "CaLcUlUs" and 'new_subjects' contains "calculus", they will equal); this is important so that we can have proper case stored in the database, instead of random cases that users could potentially input; also it removes duplicate values from 'subjects_in_database'
		$new_subjects = array_udiff($inputted_subjects, $subjects_in_database, 'strcasecmp');

		// Title case each subject
		$subjects_in_database = array_map('title_case', $subjects_in_database);
		$new_subjects = array_map('title_case', $new_subjects);

		// Get rid of duplicates in 'new_subjects' subjects (the array_udiff above gets rid of them in the 'subjects_in_database')
		$new_subjects = array_iunique($new_subjects);

		// Update user_signup
		$data = array(
			'step_2' => SIGNUP_STEP_COMPLETE
		);
		$this->db->update('user_signup', $data, 'user_id = '.$this->user_id);

		$data = array(
			'step_3' => SIGNUP_STEP_STARTED
		);
		$this->db->update('user_signup', $data, 'user_id = '.$this->user_id);

		// Increment occurrences value of subjects_in_database, if there are any
		if (!empty($subjects_in_database))
		{
			$this->db->set('occurrences', 'occurrences+1', FALSE)
					 ->where_in('name', $subjects_in_database)
					 ->update('subjects');

			// If no rows affected, then DB connection problem; abandon ship!
			if ($this->db->affected_rows() === 0)
				return FALSE;			
		}

		// Insert new subjects into DB, if there are any
		if (!empty($new_subjects))
		{

			$data = array();
			foreach ($new_subjects as $subject)
			{
				// Description and other values (if any) TBA
				$subject_data = array(
					'name' => $subject
				);
				array_push($data, $subject_data);
			}
			$this->db->insert_batch('subjects', $data);
		}

		// Get subject ids of all subjects in inputted_subjects (I'd like to merge this into the next query, but not sure how yet)

		$query = $this->db->select("{$this->user_id} user_id, id subject_id", FALSE)
				 ->where_in('name', $inputted_subjects)
				 ->get('subjects');

		$result = $query->result_array();
//		var_dump($result);
		
		$this->db->insert_batch('users_subjects', $result);

		// We do the candidate check at the end so that (a) any failures would have already happened and (b) we know who caused the defaulting (so we can give them a badge)
		$this->subjects_model->convert_default_candidates();

		$this->db->trans_complete();

		return $this->db->trans_status();
	}
	
	function update_step_3($post)
	{
		$this->db->trans_start(IS_TEST_MODE);

		// Insert availability
		$avail = json_decode($post['availability'], TRUE);
		$all_days = array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');

		// Do a separate delete query until we make a replace_batch method (though even that will need some work, since there are many rows per user_id)
		$this->db->delete('user_availabilities', array('user_id' => $this->user_id));

		$data = array();
		foreach ($avail as $time => $days)
		{
			$row_data = array(
				'user_id' => $this->user_id,
				'time' => $time
			);

			foreach($all_days as $day)
			{
				if (in_array($day, $avail[$time]))
					$is_avail = 1;
				else
					$is_avail = 0;

				$row_data[$day] = $is_avail;
			}
			array_push($data, $row_data);
		}
		$this->db->insert_batch('user_availabilities', $data);

		// Update user_signup
		$data = array(
			'step_3' => SIGNUP_STEP_COMPLETE
		);
		$this->db->update('user_signup', $data, 'user_id = '.$this->user_id);

		$data = array(
			'step_4' => SIGNUP_STEP_STARTED
		);

		$this->db->update('user_signup', $data, 'user_id = '.$this->user_id);
		// Insert notes
		$travel_notes = (isset($post['travel-notes']) ? $post['travel-notes'] : ''); 
		$avail_notes = (isset($post['availability-notes']) ? $post['availability-notes'] : '');

		$data = array(
			'user_id' => $this->user_id,
			'travel_notes' => $travel_notes,
			'availability_notes' => $avail_notes
		);
		$this->db->replace('user_notes', $data);

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	function update_step_4($post)
	{
		$tmp_avatar_path = isset($post['avatar-path']) ? $post['avatar-path'] : '';

		// If no avatar was set (also accounts for any problems during image upload)
		if (!is_file($tmp_avatar_path))
		{
			$avatar_path = DEFAULT_AVATAR_PATH;			
		}
		else
		{
			$avatar_path = str_replace('tmp_', '', $tmp_avatar_path);
			copy($tmp_avatar_path, $avatar_path);
		}

		$this->db->trans_start(IS_TEST_MODE);

		// Update users
		$data = array(
			'avatar_path' => $avatar_path
		);
		$this->db->update('users', $data, 'id = '.$this->user_id);
		$this->session->set_userdata('avatar_url', base_url($avatar_path));


		// Update tutor_profiles
		$data = array
		(
			'gender' => $post['gender'],
			'search_snippet' => $post['search-snippet'],
			'experience' => $post['experience'],
			'education' => $post['education'],
			'about' => $post['about'],
		);
		$this->db->update('tutor_profiles', $data, 'user_id = '.$this->user_id);

		// Update user_signup
		$data = array(
			'step_4' => SIGNUP_STEP_COMPLETE
		);
		$this->db->update('user_signup', $data, 'user_id = '.$this->user_id);

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	function set_user_redirect($redirect)
	{
		$this->db->update('users', array('redirect' => $redirect), 'id = '.$this->user_id);

		return $this->db->affected_rows();	
	}

	function complete_profile()
	{
		$this->session->unset_userdata('current_step');

		$this->db->update('tutor_profiles', array('profile_made' => TRUE, 'is_active' => TRUE), 'user_id = '.$this->user_id);

		$this->load->model('profile_notices_model');

		// Remove make_profile notice and add made_profile notice in its place; see if this inclusion of another model qualifies signup_model to become a library		
		$this->profile_notices_model->delete_notice(MAKE_PROFILE, $this->user_id);
		$this->profile_notices_model->add_notice(MADE_PROFILE, $this->user_id);

		return $this->db->affected_rows();	
	}

	function get_stored_values($requested)
	{
		$stored = array();

		foreach($requested as $table => $rows)
		{
			if ($table == 'users')
				$where = 'id';
			else
				$where = 'user_id';

			$this->db
					->select(implode(',', $rows))
					->from($table)
					->where($where, $this->user_id);

			$returned = $this->db->get()->row_array();
			
//			var_dump($this->user_id);
			$stored[$table] = $returned;

			foreach($rows as $row)
			{
				if (!isset($stored[$table][$row]))
					$stored[$table][$row] = '';
			}
		}

		return $stored;
	}

	function get_field_values($signup_page)
	{
			
		$field_values = array();

		if ($signup_page == 'subjects')
		{
			$this->load->model('subjects_model');
			$field_values['subjects'] = set_value('subjects', implode(',', 
													combine_subarrays(
														$this->tutor_model->get_tutors_subjects($this->session->userdata('user_id')), 'name'
														)
													)
												);
		}
		elseif ($signup_page == 'availability')
		{
			$unfiltered_field_values = $this->signup_model->get_stored_values(array(
				'user_notes' => array('availability_notes', 'travel_notes'),
				)
			);

			$field_values['availability_notes'] = set_value('availability-notes', $unfiltered_field_values['user_notes']['availability_notes']);
			$field_values['travel_notes'] = set_value('travel-notes', $unfiltered_field_values['user_notes']['travel_notes']);
			$field_values['availability'] = set_value(json_decode('availability'), $this->tutor_model->get_tutors_availability($this->session->userdata('user_id')));

			if (!empty($field_values['availability']))
				{
					$field_values['avail_json'] = array();
					foreach($field_values['availability'] as $row)
					{
						$available_days = array();
						foreach(array_slice($row, 1) as $day => $is_available)
						{
							if($is_available) 
								array_push($available_days, $day);
						}
						$field_values['avail_json'][$row['time']] = $available_days;	
					}
					$field_values['avail_json'] = json_encode($field_values['avail_json']);				
				}

		}
		elseif ($signup_page == 'about')
		{
			$unfiltered_field_values = $this->signup_model->get_stored_values(array(
				'users' => array('avatar_path'),
				'tutor_profiles' => array('gender', 'search_snippet', 'experience', 'education', 'about')
				)
			);

			$field_values = array();

			$field_values['photo-upload'] = set_value('photo-upload', $unfiltered_field_values['users']['avatar_path']);

			$field_values['gender'] = set_value('gender', $unfiltered_field_values['tutor_profiles']['gender']);
			$field_values['experience'] = set_value('experience', $unfiltered_field_values['tutor_profiles']['experience']);
			$field_values['education'] = set_value('education', $unfiltered_field_values['tutor_profiles']['education']);
			$field_values['about'] = set_value('about', $unfiltered_field_values['tutor_profiles']['about']);
			$field_values['search_snippet'] = set_value('search-snippet', $unfiltered_field_values['tutor_profiles']['search_snippet']);
		}

		return $field_values;
	}

	function is_valid($page)
	{
		if ($page == 'subjects')
		{
			$this->form_validation->set_rules('subjects', 'Subjects', 'trim|required|xss_clean');

			if (!$this->form_validation->run())
			{
				return false;
			}	
		}
		elseif ($page == 'availability')
		{
			$this->form_validation->set_rules('availability', 'Availability', 'trim|required|xss_clean');
			$this->form_validation->set_rules('travel-notes', 'Where can you meet students?', 'trim|xss_clean');
			$this->form_validation->set_rules('availability-notes', 'Any notes on your availability?', 'trim|xss_clean');

			if (!$this->form_validation->run())	
			{							
				return false;
			}
		}

		elseif ($page == 'about')
		{
			$this->form_validation->set_rules('experience', 'Experience', 'trim|xss_clean');
			$this->form_validation->set_rules('education', 'Education', 'trim|xss_clean');
			$this->form_validation->set_rules('avatar-url', 'Avatar URL', 'trim|xss_clean');
			$this->form_validation->set_rules('about', 'A Bit About You', 'trim|xss_clean');
			$this->form_validation->set_rules('search-snippet', 'Search Snippet', 'trim|xss_clean');

			if (!$this->form_validation->run())	
			{
				return false;
			}	
		}
		
		return true;
	}
}

/* End of file signup_model.php */
/* Location: ./application/models/signup_model.php */