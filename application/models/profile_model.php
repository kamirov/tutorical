<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Profile Model
 *
 * This model changes the profile 
 *
 */
class Profile_model extends CI_Model
{
	private $user_id;
	private $profile_table = 'tutor_profiles';
	private $username;
	private $role;

	function __construct()
	{
		parent::__construct();

		if ($admin_import_account_data = $this->session->userdata('admin-import-account-data'))
		{
			// Set username to admin import value (if there is one), then make sure we can import profiles

			$this->user_id = $admin_import_account_data['user_id'];
			$this->username = $admin_import_account_data['username'];
			$this->role = $admin_import_account_data['role'];
			$this->admin_import = TRUE;
		}
		else
		{
			$this->user_id = $this->session->userdata('user_id');
			$this->username = $this->session->userdata('username');
			$this->role = $this->session->userdata('role');
			$this->admin_import = FALSE;
		}

		$this->load->model('data_model');
	}

	function get_profile_location($user_id)
	{
		$coordinates = $this->get_stored_values($user_id, array(
			'user_locations' => array('lat', 'lon', 'city', 'country')
			), 'rows'
		);
		return $coordinates['user_locations'];
	}

	function get_profile($user_id, $args = NULL)
	{
		$unfiltered_profile_rows = $this->get_stored_values($user_id, array(
			'users' => array('id', 'display_name', 'first_name', 'last_name', 'name_type', 'username', 'email', 'avatar_path', 'role', 'created'),
			'user_locations' => array('name', 'lat', 'lon', 'city', 'country', 'specific'),
			'user_prices' => array('price_type', 'price', 'price_high', 'currency', 'notes'),
			'user_notes' => array('availability_notes', 'travel_notes'),
			'tutor_profiles' => array('gender', 'snippet', 'about', 'is_active', 'profile_made', 'average_rating', 'main_subject_id', 'can_meet_tutors_home', 'can_meet_students_home', 'can_meet_public', 'can_meet_centre', 'can_meet_online_local', 'can_meet_online_distant', 'is_active', 'profile_made')
			), 'rows'
		);

		$profile = array();

		$profile['role'] = $unfiltered_profile_rows['users']['role'];

		$profile['id'] = $unfiltered_profile_rows['users']['id'];
		$profile['email'] = $unfiltered_profile_rows['users']['email'];
		$profile['first_name'] = $unfiltered_profile_rows['users']['first_name'];
		$profile['last_name'] = $unfiltered_profile_rows['users']['last_name'];
		$profile['display_name'] = $unfiltered_profile_rows['users']['display_name'];
		$profile['name_type'] = $unfiltered_profile_rows['users']['name_type'];
		$profile['username'] = $unfiltered_profile_rows['users']['username'];
		$profile['avatar_path'] = $unfiltered_profile_rows['users']['avatar_path'];
		$profile['avatar_url'] = base_url($profile['avatar_path']);

		if ($profile['role'] == ROLE_STUDENT)
		{
			$profile['profile_link'] = base_url("students/".$this->username);	
	    	$profile['reviews'] = $this->get_reviews($user_id, 'reviewer');

			$created = new DateTime($unfiltered_profile_rows['users']['created']);
            $profile['joined'] = $created->format('F d, Y');	
		}
		else
		{
			$profile['profile_link'] = base_url("tutors/".$this->username);
			$profile['average_rating'] = $unfiltered_profile_rows['tutor_profiles']['average_rating'];
			$profile['is_active'] = $unfiltered_profile_rows['tutor_profiles']['is_active'];
			$profile['profile_made'] = $unfiltered_profile_rows['tutor_profiles']['profile_made'];
			$profile['main_subject'] = $this->subjects_model->get_subject('id', $unfiltered_profile_rows['tutor_profiles']['main_subject_id']);
			
			$profile['can_meet'] = array(
				'students_home' => $unfiltered_profile_rows['tutor_profiles']['can_meet_students_home'],
				'tutors_home' => $unfiltered_profile_rows['tutor_profiles']['can_meet_tutors_home'],
				'centre' => $unfiltered_profile_rows['tutor_profiles']['can_meet_centre'],
				'public' => $unfiltered_profile_rows['tutor_profiles']['can_meet_public'],
				'online_local' => $unfiltered_profile_rows['tutor_profiles']['can_meet_online_local'],
				'online_distant' => $unfiltered_profile_rows['tutor_profiles']['can_meet_online_distant']
			);

			$profile['has_can_meet'] = (array_filter($profile['can_meet']) ? TRUE : FALSE);

			$profile['price'] = $unfiltered_profile_rows['user_prices']['price'];
			$profile['price_type'] = $unfiltered_profile_rows['user_prices']['price_type'];
			$profile['reason'] = $unfiltered_profile_rows['user_prices']['notes'];
			$profile['currency'] = $unfiltered_profile_rows['user_prices']['currency'];
			$profile['hourly_rate'] = $unfiltered_profile_rows['user_prices']['price'];
			if ($profile['hourly_rate'] == '0.00')
				$profile['hourly_rate'] = '';
			$profile['hourly_rate_high'] = $unfiltered_profile_rows['user_prices']['price_high'];
			if ($profile['hourly_rate_high'] == '0.00')
				$profile['hourly_rate_high'] = '';

	        // If free, but no reason given, then use default reason
	        if ($profile['price_type'] == 'free' && empty($profile['price_notes']))
	        {
	              $profile['price_notes'] = DEFAULT_FREE_REASON;     
	        }


			$profile['city'] = $unfiltered_profile_rows['user_locations']['city'];
			$profile['country'] = $unfiltered_profile_rows['user_locations']['country'];
			$profile['location'] = $unfiltered_profile_rows['user_locations']['name'];
			$profile['lat'] = $unfiltered_profile_rows['user_locations']['lat'];
			$profile['lon'] = $unfiltered_profile_rows['user_locations']['lon'];
			$profile['specific_location'] = $unfiltered_profile_rows['user_locations']['specific'];

			$country_code = $this->get_country_code($profile['country']);
			$profile['flag_url'] = base_url("assets/images/flags/$country_code.gif");

			$profile['travel_notes'] = $unfiltered_profile_rows['user_notes']['travel_notes'];
			$profile['availability_notes'] = $unfiltered_profile_rows['user_notes']['availability_notes'];

			$profile['about'] = $unfiltered_profile_rows['tutor_profiles']['about'];
			$profile['snippet'] = $unfiltered_profile_rows['tutor_profiles']['snippet'];
			$profile['is_active'] = $unfiltered_profile_rows['tutor_profiles']['is_active'];
			$profile['profile_made'] = $unfiltered_profile_rows['tutor_profiles']['profile_made'];

			$profile['gender'] = $unfiltered_profile_rows['tutor_profiles']['gender'];
			if ($profile['gender'] === 'm')
				$profile['gender'] = 'Male';
			elseif ($profile['gender'] === 'f')
				$profile['gender'] = 'Female';
			else
				$profile['gender'] = '';

			$unfiltered_profile_results = $this->get_stored_values($user_id, array(
				'user_links' => array('id', 'url', 'label', 'description', 'type'),
				'user_educations' => array('id', 'school', 'field', 'degree', 'start_year', 'end_year', 'notes'),
				'user_experiences' => array('id', 'company', 'position', 'location', 'start_month', 'start_year','end_month', 'end_year', 'description'),
				'user_volunteerings' => array('id', 'company', 'position', 'location', 'start_month', 'start_year','end_month', 'end_year', 'description'),
				'user_external_reviews' => array('id', 'reviewer', 'rating', 'content', 'url')
				), 'results'
			);

			$profile['links'] = $unfiltered_profile_results['user_links'];
			$profile['education'] = $unfiltered_profile_results['user_educations'];
			$profile['experience'] = $unfiltered_profile_results['user_experiences'];
			$profile['volunteering'] = $unfiltered_profile_results['user_volunteerings'];
			$profile['external_reviews'] = $unfiltered_profile_results['user_external_reviews'];

			$profile['subjects_array'] = combine_subarrays($this->tutor_model->get_tutors_subjects($user_id), 'name');

//			var_dump($this->tutor_model->get_tutors_subjects($user_id), $profile['subjects_array']);
			$profile['subjects_string'] = implode(',', $profile['subjects_array']);
			$subjects_data['subjects_array'] = $profile['subjects_array'];
			$subjects_data['main_subject'] = $profile['main_subject']['name'];

			if (isset($args) && isset($args['usage']))
				$subjects_data['usage'] = $args['usage'];
			else
				$subjects_data['usage'] = NULL;

			$profile['subjects_table'] = $this->load->view('components/profile/subjects', $subjects_data, TRUE);

			$availability_data['availability'] = $this->tutor_model->get_tutors_availability($user_id);

			if (!empty($availability_data['availability']))
			{
				$availability_data['avail_json'] = array();
				$profile['has_availability'] = FALSE;

				foreach ($availability_data['availability'] as $row)
				{
					$available_days = array();
					// We array_slice because 1st el is the time, other 7 are days\
					foreach (array_slice($row, 1) as $day => $is_available)
					{
						if ($is_available)
						{
							array_push($available_days, $day);
							$profile['has_availability'] = TRUE;
						}
					}
					$availability_data['avail_json'][$row['time']] = $available_days;	
				}
				$availability_data['avail_json'] = json_encode($availability_data['avail_json']);
	        	$profile['avail_json'] = $availability_data['avail_json'];
			}
			else
			{
				$profile['has_availability'] = FALSE;
			}

	        $profile['availability'] = $this->load->view('components/profile/availability', $availability_data, TRUE);
	    	$profile['reviews'] = $this->get_reviews($user_id);
		}

		return $profile;
	}

	function get_reviews($user_id, $perspective = 'reviewed')
	{
		if ($perspective == 'reviewed')
		{
			$user_class = 'students';			
		}
		else
		{
			$user_class = 'tutors';						
		}

		$reviews = array();

		$this->db->start_cache();

		$this->db->select('pc.id as comment_id, pc.commenter_id, pc.commented_user_id, pc.parent_id, pc.content, UNIX_TIMESTAMP(pc.posted) as posted, pc.reports,
							ur.id AS review_id, ur.profile_comment_id, ur.expertise, ur.helpfulness, ur.response, ur.clarity, ur.rating,
							u.id AS contact_id, u.username, u.display_name, u.avatar_path, u.role user_role')
			 	 ->from('profile_comments pc')
				 ->join('user_reviews ur', 'ur.profile_comment_id = pc.id')
				 ->order_by('pc.posted', 'DESC');

		$this->db->stop_cache();

		if ($perspective == 'reviewed')
		{
			$this->db->where('pc.commented_user_id', $user_id, FALSE)
				 	 ->join('users u', 'u.id = pc.commenter_id', 'left');
		}
		else
		{
			$this->db->where('pc.commenter_id', $user_id, FALSE)
				 	 ->join('users u', 'u.id = pc.commented_user_id', 'left');
		}

		$unfiltered_reviews = $this->db->get()->result_array();

		$this->db->flush_cache();

		foreach($unfiltered_reviews as $unfiltered_review)
		{			
			$review = array();

			$review['id'] = $unfiltered_review['review_id'];
			$review['rating'] = $this->make_rating('star', $unfiltered_review['rating']);
			$review['expertise'] = $this->make_rating('expertise', $unfiltered_review['expertise'], 'box-ratings');
			$review['helpfulness'] = $this->make_rating('helpfulness', $unfiltered_review['helpfulness'], 'box-ratings');
			$review['response'] = $this->make_rating('response', $unfiltered_review['response'], 'box-ratings');
			$review['clarity'] = $this->make_rating('clarity', $unfiltered_review['clarity'], 'box-ratings');
			$review['comment'] = array(
				'id' => $unfiltered_review['comment_id'],
				'contact_id' => $unfiltered_review['contact_id'],
				'content' => $unfiltered_review['content'],
				'posted' => $unfiltered_review['posted'],
				'reports' => $unfiltered_review['reports'],
				'profile_link' => base_url($user_class.'/'.$unfiltered_review['username']),
				'avatar_url' => base_url($unfiltered_review['avatar_path']),
				'display_name' => $unfiltered_review['display_name'],
				'user_role' => $unfiltered_review['user_role']
			);
/*
			// Change this. Way to get comments has changed drastically
			// Get profile comment
			foreach($comments as $comment)
			{
				if ($review['comment']['id'] == $comment['parent_id'])
				{
					$review['comment']['child'] = $comment;
					$review['comment']['child']['avatar_url'] = base_url($comment['avatar_path']);

					$review['comment']['child']['profile_link'] = base_url('students/'.$comment['username']);

					if ($comment['commenter_id'] == $user_id)	// Means commenter is the tutor him/herself
						$review['comment']['child']['op'] = TRUE;
					else
						$review['comment']['child']['op'] = FALSE;
					break;
				}
			}
*/
			array_push($reviews, $review);
		}

//		var_dump($reviews);
		return $reviews;
	}

	function make_rating($name, $value, $additional_classes = '')
	{
	    $rating = '';

	    if ($name == 'star')
	    {
			$max = 5.5; // *n because each star is broken into n; +1 because loop starts at 1, not 0
			for ($i = 0.5; $i < $max; $i+=0.5)
			{
				$rating.= '<input name="star-average" disabled="disabled" type="radio" '.($value == $i ? 'checked="checked"' : '').' class="star {split:2}"/>';
			}
	    }
	    else
	    {
		    for ($i = 1; $i < 6; $i++)
		    {
		          $input = '<input name="'.$name.'-rating" type="radio" disabled="disabled" class="star '.$additional_classes.'" value="" ';
		          if ($i == $value)
		                $input .= ' checked="checked" ';
		          $input .=' />';

		          $rating .= $input;
		    }
	    }

	    return $rating;
	}

	function profile_made($user_id = NULL)
	{
		if (!$user_id)
		{
			$user_id = $this->user_id;
		}

		$this->db->select('profile_made')
				 ->from('tutor_profiles')
				 ->where('user_id', $user_id);

		return $this->db->get()->row()->profile_made;
	}

	function mandatory_values_filled_in()
	{
		// Mandatory values: Name, Price, Location, Subjects
		$this->db->select('u.id')
				 ->from('users u')
				 ->where('u.id', $this->user_id)
			 	 ->join('tutor_profiles tp', 'u.id = tp.user_id')
			 	 ->join('user_prices up', 'up.user_id = tp.user_id')
			 	 ->join('user_locations ul', 'tp.user_id = ul.user_id')
			 	 ->join('users_subjects us', 'tp.user_id = us.user_id')
			 	 ->limit(1);

		$row = $this->db->get()->row();

		// empty row means one of the joined tables didn't have needed row
		if (empty($row))
			return FALSE;

		else
			return TRUE;
	}

	function was_profile_just_made()
	{
		if (!$this->profile_made()
			&& $this->mandatory_values_filled_in())
		{
			return $this->set_profile_just_made();
		}
		else
		{
			return FALSE;
		}
	}

	function set_profile_just_made()
	{
		$this->profile_notices_model->delete_notice(MAKE_PROFILE, $this->user_id);

		if ($this->admin_import)
		{
			$this->profile_notices_model->add_notice(ADMIN_IMPORT_WELCOME, $this->user_id);
		}
		else
		{
			$this->reaction_notice->set('<b>Great! Your profile is visible!</b><br>You can fill out more of your profile, or get straight to tutoring.<hr>Also, check out the '.anchor('account/marketing', 'Marketing').' page for tips on getting more students.', 'success');
			$this->profile_notices_model->add_notice(MADE_PROFILE, $this->user_id);
		}

		// Have to do this after profile notice check because affected_rows is otherwise 0 (if no notices were removed). We could also just cache the affected rows as a var
		$this->db->update('tutor_profiles', array('profile_made' => TRUE, 'is_active' => TRUE), 'user_id = '.$this->user_id);

		if (!$this->admin_import)
			$this->session->set_userdata('account_profile_made', TRUE);

		return $this->db->affected_rows() > 0;
	}

	function update_profile($table, $data)
	{
		$this->db->trans_start(IS_TEST_MODE);
		if ($table == 'users')
			$this->db->update('users', $data, 'id = '.$this->user_id);
		else
			$this->db->update($table, $data, 'user_id = '.$this->user_id);
		$this->db->trans_complete();
		
		if ($this->db->trans_status())
		{
			if ($this->role != ROLE_STUDENT)
				$response = $this->form_validation->response(STATUS_OK, array('profileJustMade' => $this->was_profile_just_made()));
			else
				$response = $this->form_validation->response();
		}
		else
		{
			$response = $this->form_validation->response(STATUS_DATABASE_ERROR);
		}

		return $response;
	}

	function replace_row($table, $data, $additional_data_to_return = NULL)
	{
		$this->db->trans_start(IS_TEST_MODE);
		$this->db->replace($table, $data);
		$insert_id = $this->db->insert_id();
		$this->db->trans_complete();


		if ($this->db->trans_status())
		{
			$data_to_return = array(
				'rowId' => $insert_id,
				'profileJustMade' => $this->was_profile_just_made()
			);

			if ($additional_data_to_return)
			{
				$data_to_return = array_merge($data_to_return, $additional_data_to_return);
			}

			$response = $this->form_validation->response(STATUS_OK, $data_to_return);
		}
		else
		{
			$response = $this->form_validation->response(STATUS_DATABASE_ERROR);
		}

		return $response;	
	}

	function update_order($type, $items)
	{
		$data = array();

//		var_export($items);

		foreach($items[$type] as $order => $itemId)
		{
//			echo 5;
			$data[] = array(
				'id' => $itemId,
				'order' => $order
			);
		}
//		var_export($data);

		if ($type == 'er')
		{
			$type = 'external_review';
 			}

		$this->db->update_batch("user_{$type}s", $data, 'id');

		if ($this->db->affected_rows() === 0)
		{
			return $this->form_validation->response(STATUS_DATABASE_ERROR);
		}
		return $this->form_validation->response();
	}

	function update_photo($avatar = NULL)
	{
		if ($avatar)
		{
			$_POST['src'] = $avatar['src'];
			$_POST['x'] = '0';	// Have to be strings, since 0 == FALSE
			$_POST['y'] = '0';
			$_POST['w'] = $avatar['width'];
			$_POST['h'] = $avatar['height'];

			$avatar_width = $avatar['width'];
			$avatar_height = $avatar['height'];
		}
		else
		{
			$avatar_width = AVATAR_WIDTH;
			$avatar_height = AVATAR_HEIGHT;	
		}


		// Check for valid input
		$this->form_validation->set_rules('src', 'Photo', 'trim|strip_tags|required|xss_clean');	
		$this->form_validation->set_rules('w', 'Photo', 'trim|strip_tags|required|xss_clean');	
		$this->form_validation->set_rules('x', 'Photo', 'trim|strip_tags|required|xss_clean');	
		$this->form_validation->set_rules('y', 'Photo', 'trim|strip_tags|required|xss_clean');	
		$this->form_validation->set_rules('h', 'Photo', 'trim|strip_tags|required|xss_clean');

		if (!$this->form_validation->run())	
		{
			echo json_encode($this->form_validation->invalid_response());
			return $this->form_validation->invalid_response();
		}

		// Copy and resize uploaded image

		$jpeg_quality = 100;
		$avatar_dir = "assets/uploads/images/{$this->user_id}";
		if (!is_dir($avatar_dir))
			mkdir($avatar_dir);
		$avatar_path = "$avatar_dir/avatar.jpg";
		$src = $this->input->post('src');

		// Get image object; eventually make this into a function
		$image_info = getimagesize($src);
		$image_type = $image_info[2];
		if ($image_type == IMAGETYPE_JPEG) 
		   $img_r = @imagecreatefromjpeg($src);
		elseif($image_type == IMAGETYPE_GIF) 
		   $img_r = @imagecreatefromgif($src);
		elseif($image_type == IMAGETYPE_PNG)
		   $img_r = @imagecreatefrompng($src);

		$dst_r = imagecreatetruecolor($avatar_width, $avatar_height);
		imagecopyresampled($dst_r,$img_r,0,0,$this->input->post('x'),$this->input->post('y'), $avatar_width, $avatar_height, $this->input->post('w'),$this->input->post('h'));
		imagejpeg($dst_r, $avatar_path, $jpeg_quality);

		// Update DB
		$data = array('avatar_path' => $avatar_path);

		$response = $this->update_profile('users', $data);
		if ($response['success'])
		{
			if (!$this->admin_import)
				$this->session->set_userdata('avatar_url', base_url($avatar_path));
		}

		return $response;
	}

	function update_external_review($review = NULL)
	{
		if ($review)
		{
			$_POST['reviewer'] = $review['reviewer'];
			$_POST['rating'] = $review['rating'];
			$_POST['url'] = $review['url'];
			$_POST['content'] = $review['content'];
		}

		$this->form_validation->set_rules('reviewer', 'Reviewer', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('rating', 'Rating', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('url', 'Web Address/URL', '1trim|strip_tags|valid_url|required|xss_clean');
		$this->form_validation->set_rules('content', 'Details', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('item-id', 'Item Id', 'trim|strip_tags|xss_clean');

	    if (!$this->form_validation->run())
	    {
        	$response = $this->form_validation->invalid_response();
        	return $response;
	    }

	    $url = $this->input->post('url');
		if (!(substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://'))
		{
			$url = 'http://'.$url;
		}

	    $data = array(
	    	'id' => $this->input->post('item-id'),
	    	'user_id' => $this->user_id,
	    	'reviewer' => $this->input->post('reviewer'),
	    	'url' => $url,
	    	'content' => $this->input->post('content'),
	    	'rating' => $this->input->post('rating'),
	    );

	    // Check later to see why this is even needed when we're replacing the row anyway
		if ($data['id'])
		{
			// Check to make sure that item-id hasn't been manually changed
			$this->db->select('id, user_id, order')
					 ->from("user_external_reviews")
					 ->where('id', $data['id']);

			$row = $this->db->get()->row();
			$current_user_id = $row->user_id;
			$order = $row->order;						
		}
		else
		{
			$new_order = $this->db->select_max('order', 'max_order')
								  ->where('user_id', $this->user_id)
								  ->get("user_external_reviews")
								  ->row_array();

			$order = $new_order['max_order'] + 1;			
		}
		$data['order'] = $order;

		if (!isset($current_user_id) || $current_user_id == $this->user_id)
		{
			return $this->replace_row("user_external_reviews", $data, array('url' => $url));
		}
		else
		{
			return $this->form_validation->response(STATUS_UNKNOWN_ERROR);
		}

		return $response;		
	}

	function update_display_name($display_name = NULL)
	{
		if ($display_name)
		{
			$first_name = '';
			$last_name = '';
			$update_link = TRUE;
			$name_type = NULL;
		}
		else
		{
			$this->form_validation->set_rules('first-name', 'First Name', 'trim|strip_tags|required|xss_clean');
			$this->form_validation->set_rules('last-name', 'Last Name', 'trim|strip_tags|required|xss_clean');
			$this->form_validation->set_rules('abbreviate-last-name', 'Abbreviate Last Name', 'trim|integer|strip_tags|xss_clean');
			$this->form_validation->set_rules('update-profile-link', 'Update Profile Link', 'trim|integer|strip_tags|xss_clean');

			if (!$this->form_validation->run())
				return $this->form_validation->invalid_response();

			$first_name = $this->input->post('first-name');
			$last_name = $this->input->post('last-name');

			$abbrev = $this->input->post('abbreviate-last-name');
			$update_link = $this->input->post('update-profile-link');

			if ($abbrev)
			{
				// e.g. Samuel Adams >> Samuel A.
				$display_name = $first_name.' '.substr($last_name, 0, 1).'.';
				$name_type = NAME_TYPE_SHORT;
			}
			else
			{
				$display_name = $first_name.' '.$last_name;
				$name_type = NAME_TYPE_FULL;			
			}
		}

		$data = array(
			'first_name' => $first_name,
			'last_name' => $last_name,
			'display_name' => $display_name,
			'name_type' => $name_type
		);


		if ($update_link)
		{
			$data['username'] = $this->tank_auth->make_username($display_name);

			if (!$data['username'])
			{
				$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
				return $response;
			}
		}		

		$response = $this->update_profile('users', $data);

		if ($response['success'])
		{
			if (!$this->admin_import)
				$this->session->set_userdata('display_name', $display_name);
	
			$response['data']['displayName'] = $display_name;

			if ($update_link)
			{
				if (!$this->admin_import)
					$this->session->set_userdata('username', $data['username']);
	
				$response['data']['username'] = $data['username'];
			}
		}
		return $response;
	}

	function update_gender($gender = NULL)
	{
		if ($gender)
		{
			$_POST['gender'] = $gender;
		}

		$this->form_validation->set_rules('gender', 'Gender', 'trim|strip_tags|required|xss_clean');
		if (!$this->form_validation->run())	
			return $this->form_validation->invalid_response();

		$data = array('gender' => $this->input->post('gender'));

		return $this->update_profile($this->profile_table, $data);
	}

	function update_can_meet($can_meet = NULL)
	{
		if ($can_meet)
		{
			$_POST['can-meet-students-home'] = $can_meet['students_home'];
			$_POST['can-meet-tutors-home'] = $can_meet['tutors_home'];
			$_POST['can-meet-online-local'] = $can_meet['online_local'];
			$_POST['can-meet-centre'] = $can_meet['centre'];
		}

		$this->form_validation->set_rules('can-meet-students-home', 'Can Meet', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('can-meet-tutors-home', 'Can Meet', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('can-meet-centre', 'Can Meet', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('can-meet-public', 'Can Meet', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('can-meet-online-local', 'Can Meet', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('can-meet-online-distant', 'Can Meet', 'trim|strip_tags|xss_clean');

		if (!$this->form_validation->run())	
			return $this->form_validation->invalid_response();

		$data = array(
				'can_meet_students_home' => $this->input->post('can-meet-students-home'),
				'can_meet_tutors_home' => $this->input->post('can-meet-tutors-home'),
				'can_meet_centre' => $this->input->post('can-meet-centre'),
				'can_meet_public' => $this->input->post('can-meet-public'),
				'can_meet_online_local' => $this->input->post('can-meet-online-local'),
				'can_meet_online_distant' => $this->input->post('can-meet-online-distant')
		);

		return $this->update_profile($this->profile_table, $data);
	}

	function update_price($price_and_currency = NULL)
	{
		if ($price_and_currency)
		{
			$_POST['price-type'] = 'per_hour';
			$_POST['hourly-rate'] = $price_and_currency['hourly_rate'];
			$_POST['hourly-rate-high'] = $price_and_currency['hourly_rate_high'];
			$_POST['currency'] = $price_and_currency['currency'];
		}

		$this->form_validation->set_rules('price-type', 'Price Type', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('reason', 'Reason', 'trim|strip_tags|xss_clean');
		
		$price_type = $this->input->post('price-type');

		// Validate hourly rate and currency only if per hour was chosen
		if ($price_type == 'per_hour')
		{
			$this->form_validation->set_rules('currency', 'Currency', 'trim|strip_tags|required|callback_correct_currency_code|xss_clean');
			$this->form_validation->set_rules('hourly-rate', "the 'From' Rate", 'trim|strip_tags|required|xss_clean|is_money');
			$this->form_validation->set_rules('hourly-rate-high', "the 'To' Rate", 'trim|strip_tags|xss_clean|is_money');
			
			if (!$this->form_validation->run())	
				return $this->form_validation->invalid_response();

		    $errors = array();

			$hourly_rate = $this->input->post('hourly-rate');
		    $hourly_rate = preg_replace("/[^0-9.]/", "", $hourly_rate);

			if ($hourly_rate >= 1000)
	    		$errors = array_merge($errors, array('hourly-rate' => $this->form_validation->make_error("Please enter a 'From' hourly rate less than 1000")));
			elseif ($hourly_rate <= 0)
	    		$errors = array_merge($errors, array('hourly-rate' => $this->form_validation->make_error("Please enter a 'From' hourly rate greater than 0")));

			$hourly_rate_high = $this->input->post('hourly-rate-high');

			if ($hourly_rate_high)
			{
			    $hourly_rate_high = preg_replace("/[^0-9.]/", "", $hourly_rate_high);

				if ($hourly_rate_high > 1000)
		    		$errors = array_merge($errors, array('hourly-rate-high' => $this->form_validation->make_error("Please enter a 'To' hourly rate less than 1000")));
				elseif ($hourly_rate_high <= 0)
		    		$errors = array_merge($errors, array('hourly-rate-high' => $this->form_validation->make_error("Please enter a 'To' hourly rate greater than 0")));
				elseif ($hourly_rate_high <= $hourly_rate)
		    		$errors = array_merge($errors, array('hourly-rate-high' => $this->form_validation->make_error("The 'To' hourly rate must be larger than the 'From' hourly rate")));
			}
			
			if (!empty($errors))
		    	return $this->form_validation->response(STATUS_VALIDATION_ERROR, array('errors' => $errors));
		}
		else
		{
			if (!$this->form_validation->run())	
				return $this->form_validation->invalid_response();			
		}

		$data = array(
			'user_id' => $this->user_id,
			'subject_id' => 0	// 0 for standard price
		);

		if ($price_type == 'per_hour')
		{			
			if ($hourly_rate > 0)
			{
				$data['price_type'] = $price_type;
			}
			else
			{
				$data['price_type'] = 'free';
			}

			$data['price'] = round(floatval($hourly_rate), 2);
			$data['price_high'] = round(floatval($hourly_rate_high), 2);
			$data['currency'] = $this->input->post('currency');
			$currency_sign = get_currency_sign($data['currency']);
		}	
		elseif ($this->input->post('price-type') == 'free')
		{
			$data['price_type'] = $this->input->post('price-type');
			$data['notes'] = $this->input->post('reason');
			$currency_sign = '';
		}

		$response = $this->replace_row('user_prices', $data);

		$response['data']['currencySign'] = $currency_sign;

		return $response;
	}

	function update_availability($avail = NULL)
	{
		$this->db->trans_start(IS_TEST_MODE);
		
		// Insert availability
		if (!$avail)
			$avail = json_decode($this->input->post('availability'), TRUE);
	
//		echo '<pre>';
//		var_export($avail);
//		echo '</pre>';

		$all_days = array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');

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

		$this->db->delete('user_availabilities', array('user_id' => $this->user_id));
		$this->db->insert_batch('user_availabilities', $data);

		$this->db->trans_complete();

		if ($this->db->trans_status())
		{
			// Have to get this as a separate var because it's formatted differently
			$availability_data = array('availability' => $this->tutor_model->get_tutors_availability($this->user_id));

			$response = $this->form_validation->response(
				STATUS_OK,
				array('html' => $this->load->view('components/profile/availability', $availability_data, TRUE))
			);

			return $response;
		}
		else
		{
			$response = $this->form_validation->response(STATUS_DATABASE_ERROR);

			return $response;
		}

	}

	function update_location($location = NULL)
	{
		if ($location)
		{
			$_POST['lat'] = $location['lat'];
			$_POST['lon'] = $location['lon'];
			$_POST['specific'] = $location['specific'];
			$_POST['city'] = $location['city'];
			$_POST['country'] = $location['country'];
			$_POST['location'] = $location['name'];
		}

		$this->form_validation->set_rules('location', 'Location', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('country', 'Country', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('lat', 'Latitude', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('lon', 'Longitude', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('specific', 'Specific', 'trim|strip_tags|xss_clean');

		if (!$this->form_validation->run())	
			return $this->form_validation->invalid_response();

		$data = array(
			'user_id' => $this->user_id,
			'name' => $this->input->post('location'),
			'lat' => $this->input->post('lat'),
			'lon' => $this->input->post('lon'),
			'country' => $this->input->post('country'),
			'city' => $this->input->post('city'), 
			'specific' => $this->input->post('specific')
		);

		// Don't add this to data_locations, since the tutor's location isn't equal to the city's location

		return $this->replace_row('user_locations', $data);
	}

	function update_subjects($inputted_subjects = NULL)
	{
		if (!$inputted_subjects)
		{
			$this->form_validation->set_rules('subjects', 'Subjects', 'trim|strip_tags|required|xss_clean');
			$this->form_validation->set_rules('main-subject', 'Main Subject', 'trim|strip_tags|required|xss_clean');

			if (!$this->form_validation->run())	
				return $this->form_validation->invalid_response();
			
			$this->load->helper('text');

			$post_subjects = $this->input->post('subjects');

			// Split subject into array of subjects
			$inputted_subjects = explode(',', $post_subjects);

			// Remove new lines and returns
			$inputted_subjects = preg_replace('/\s\s+/', '', $inputted_subjects);

			// Trim each subject
			$inputted_subjects = array_map('trim', $inputted_subjects);

			// Remove empty subject elements
			$inputted_subjects = array_filter($inputted_subjects);

			// Remove duplicates
			$inputted_subjects = array_iunique($inputted_subjects);

			// Only proceed if there are still any elements left
			if (count($inputted_subjects) == 0)
			{
				$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);

				return $response;
			}			
		}

		$subjects_data['subjects_array'] = $inputted_subjects;

		$subjects_to_be_removed = array();

		// Get all subjects that tutor already has
		$users_subjects_results = $this->tutor_model->get_tutors_subjects($this->user_id);

		// Remove any subjects from inputted_subjects if tutor already has them added (it means that we've already processed those) 
		$users_subjects = array();
		foreach($users_subjects_results as $users_subject)
		{
			$key = array_searchi($users_subject['name'], $inputted_subjects);
			
			// If tutor has subject, but it was not in inputted string, then add it to removal array
			if ($key === FALSE)
			{
				$subjects_to_be_removed[$users_subject['subject_id']] = $users_subject['users_subjects_id'];
			}
			// If tutor has subject and it was in inputted string, then we don't want to process it
			else
			{
				unset($inputted_subjects[$key]);
			}

			array_push($users_subjects, $users_subject['name']);
		}

		$this->db->trans_start();

		if (!empty($subjects_to_be_removed))
		{
/*
			// Decrement occurrences value of subjects_to_be_removed, if there are any
			$this->db->set('occurrences', 'occurrences-1', FALSE)
					 ->where_in('id', array_keys($subjects_to_be_removed))
					 ->update('subjects');

			// If no rows affected, then DB connection problem; abandon ship!
			if ($this->db->affected_rows() === 0)
				return FALSE;
*/

			// Remove deleted subjects from users_subjects table
			$this->db->where_in('id', array_values($subjects_to_be_removed))
					 ->delete('users_subjects');



			// If no subjects deleted (and they should have been), then abandon ship
			if ($this->db->affected_rows() === 0)
			{
				$response = $this->form_validation->response(STATUS_DATABASE_ERROR);

				$this->db->trans_complete();

				return $response;
			}
		}

		// If all inputted subjects have already been added to the tutor's subject list, and none have been removed, then just skip the rest of the process
		if (empty($inputted_subjects))
		{

			$this->update_main_subject();
			$this->db->trans_complete();

			if ($this->db->trans_status())
			{
				$response = $this->form_validation->response(
					STATUS_OK,
					array('html' => $this->load->view('components/profile/subjects', $subjects_data, TRUE))
				);				
			}
			else
			{
				$response = $this->form_validation->response(STATUS_DATABASE_ERROR);
			}

			return $response;
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

/*
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
*/

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

		$this->update_main_subject();
		$this->db->trans_complete();

		if ($this->db->trans_status())
		{
			$response = $this->form_validation->response(
				STATUS_OK,
				array(
					'html' => $this->load->view('components/profile/subjects', $subjects_data, TRUE),
					'profileJustMade' => $this->was_profile_just_made()
				)
			);
		}
		else
		{
			$response = $this->form_validation->response(STATUS_DATABASE_ERROR);
		}
		return $response;
	}

	function update_main_subject()
	{
		$main_subject = $this->input->post('main-subject');

		if ($main_subject == FALSE || !$this->subject_can_be_mained($main_subject))
		{
			$main_subject_id = 0;
		}
		else
		{
			$main_subject_id = $this->subjects_model->get_subject('name', $main_subject);
			$main_subject_id = $main_subject_id['id'];
		}

		$this->db->set('main_subject_id', $main_subject_id)
				 ->where('user_id', $this->user_id)
				 ->update('tutor_profiles');

//		var_dump($main_subject_id, $this->user_id, $this->db->affected_rows());
	}

	function subject_can_be_mained($subject)
	{
		if ($subject == FALSE)
			return TRUE;

		$subject = $this->subjects_model->get_subject('name', $subject);

//		var_export($this->user_id);

		if ($subject['id'] && $this->tutor_model->confirm_tutor_has_subject($this->user_id, $subject['id']))
		{
			return TRUE;
		}

		$this->form_validation->set_message('subject_can_be_mained', 'Sorry! Something happened! Please try again or refresh the page.');
		return FALSE;

	}

	function update_about($about = NULL)
	{
		if ($about)
		{
			$_POST['about'] = $about;			
		}

		$this->form_validation->set_rules('about', 'About', 'trim|strip_tags|xss_clean');

		if (!$this->form_validation->run())	
			return $this->form_validation->invalid_response();

		$data = array('about' => $this->input->post('about'));

		return $this->update_profile($this->profile_table, $data);
	}

	function update_snippet($snippet = NULL)
	{
		if ($snippet)
		{
			$_POST['snippet'] = $snippet;			
		}
		
		$this->form_validation->set_rules('snippet', 'Snippet', 'trim|strip_tags|required|max_length['.SNIPPET_MAX_LENGTH.']|xss_clean');

		if (!$this->form_validation->run())	
			return $this->form_validation->invalid_response();

		$data = array('snippet' => $this->input->post('snippet'));

		return $this->update_profile($this->profile_table, $data);
	}

	function update_travel_notes($travel_notes = NULL)
	{
		if ($travel_notes)
		{
			$_POST['travel-notes'] = $travel_notes;
		}

		$this->form_validation->set_rules('travel-notes', 'Travel Notes', 'trim|strip_tags|xss_clean');

		if (!$this->form_validation->run())	
			return $this->form_validation->invalid_response();

		$data = array(
			'travel_notes' => $this->input->post('travel-notes'),
			'user_id' => $this->user_id
		);

		$this->db->select('1', FALSE)
				 ->from('user_notes')
				 ->where('user_id', $this->user_id);

		$query = $this->db->get();

		if ($query->num_rows() == 1)
			return $this->update_profile('user_notes', $data);
		
		return $this->replace_row('user_notes', $data);
	}

	function update_education($education = NULL)
	{
		if ($education)
		{
			$_POST['school'] = $education['school'];
			$_POST['field'] = $education['field'];
			$_POST['degree'] = $education['degree'];
			$_POST['start-year'] = $education['start-year'];
			$_POST['end-year'] = strval($education['end-year']);	// Have to get string; int 0 fails form validation required rule
			$_POST['notes'] = $education['notes'];
		}

		$this->form_validation->set_rules('item-id', 'Item Id', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('school', 'School', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('field', 'Field', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('degree', 'Degree', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('start-year', 'Start Year', 'trim|strip_tags|required|xss_clean|lte['.date('Y').']|gte[1900]');
		$this->form_validation->set_rules('end-year', 'End Year', 'trim|strip_tags|required|xss_clean|lte['.date('Y').']');
		$this->form_validation->set_rules('notes', 'Notes', 'trim|strip_tags|xss_clean');

		if (!$this->form_validation->run())	
		{
			return $this->form_validation->invalid_response();
		}

		$end_year = $this->input->post('end-year');
		$start_year = $this->input->post('start-year');

		if ($end_year != 0)		// not 'present'
		{
			$this->form_validation->set_rules('end-year', "'To' Year", "gte[{$start_year},'From' Year]");					

			if (!$this->form_validation->run())	
				return $this->form_validation->invalid_response();
		}		

		$school = $this->input->post('school');
		$field = $this->input->post('field');
		$degree = $this->input->post('degree');

		// keys in items are the tables
		$items = array(
			'schools' => $school,
			'fields' => $field,
			'degrees' => $degree
		);

		$this->data_model->add_items($items);

		$data = array(
			'id' => $this->input->post('item-id'),
			'user_id' => $this->user_id,
			'school' => $school,
			'field' => $field,
			'degree' => $degree,
			'start_year' => $start_year,
			'end_year' => $end_year,
			'notes' => $this->input->post('notes'),
		);

		if ($data['id'])
		{
			// Check to make sure that item-id hasn't been manually changed
			$this->db->select('id, user_id, order')
					 ->from('user_educations')
					 ->where('id', $data['id']);

			$row = $this->db->get()->row();
			$current_user_id = $row->user_id;
			$order = $row->order;
		}
		else
		{
			$new_order = $this->db->select_max('order', 'max_order')
								  ->where('user_id', $this->user_id)
								  ->get('user_educations')
								  ->row_array();

			$order = $new_order['max_order'] + 1;			
		}
		$data['order'] = $order;

		if (!isset($current_user_id) || $current_user_id == $this->user_id)
		{
			return $this->replace_row('user_educations', $data);
		}
		else
		{
			return false;
		}
	}

	function update_link()
	{
		$this->form_validation->set_rules('item-id', 'Item Id', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('url', 'Web Address/URL', 'trim|strip_tags|valid_url|required|xss_clean');
		$this->form_validation->set_rules('label', 'Label', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('description', 'Description', 'trim|strip_tags|xss_clean');		
		if (!$this->form_validation->run())	
			return $this->form_validation->invalid_response();

		$url = $this->input->post('url');
		$label = $this->input->post('label');

		if (!$label)
		{
			$label = $url;
		}

		if (!(substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://'))
		{
			$url = 'http://'.$url;
		}

		$data = array(
			'id' => $this->input->post('item-id'),
			'user_id' => $this->user_id,
			'url' => $url,
			'label' => $label,
			'description' => $this->input->post('description'),
		);

		if ($data['id'])
		{
			// Check to make sure that item-id hasn't been manually changed
			$this->db->select('id, user_id, order')
					 ->from("user_links")
					 ->where('id', $data['id']);

			$row = $this->db->get()->row();
			$current_user_id = $row->user_id;
			$order = $row->order;
		}
		else
		{
			$new_order = $this->db->select_max('order', 'max_order')
								  ->where('user_id', $this->user_id)
								  ->get('user_links')
								  ->row_array();

			$order = $new_order['max_order'] + 1;			
		}
		$data['order'] = $order;

		if (!isset($current_user_id) || $current_user_id == $this->user_id)
		{
			return $this->replace_row("user_links", $data, array('url' => $url));
		}
		else
		{
			return $this->form_validation->response(STATUS_UNKNOWN_ERROR);
		}
	}

	function update_experience_volunteering()
	{
		$diff_key = $this->input->post('diff-key');

		if ($diff_key != 'experiences' && $diff_key != 'volunteerings')
			return $this->form_validation->response(STATUS_UNKNOWN_ERROR);

		$this->form_validation->set_rules('start-month', 'Start Month', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('start-year', 'Start Year', 'trim|strip_tags|required|xss_clean|lte['.date('Y').']|gte[1900]');
		
		$currently_work = $this->input->post('current');

		if (!$currently_work)
		{
			$this->form_validation->set_rules('end-month', 'End Month', 'trim|strip_tags|required|xss_clean');
			$this->form_validation->set_rules('end-year', 'End Year', 'trim|strip_tags|required|xss_clean|lte['.date('Y').']|gte[1900]');
		}

		if (!$this->form_validation->run())	
			return $this->form_validation->invalid_response();

		$this->form_validation->set_rules('item-id', 'Item Id', 'trim|strip_tags|xss_clean');

		if ($diff_key == 'experiences')
			$this->form_validation->set_rules('company', 'Company', 'trim|strip_tags|xss_clean');
		else
			$this->form_validation->set_rules('company', 'Company', 'trim|strip_tags|required|xss_clean');

		$this->form_validation->set_rules('position', 'Position', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('location', 'Location', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('description', 'Description', 'trim|strip_tags|xss_clean');


		$times = array(
			'start_month' => $this->input->post('start-month'),
			'start_year' => $this->input->post('start-year'),
			'end_month' => $this->input->post('end-month'),
			'end_year' => $this->input->post('end-year')
		);

		// Empty any invalid or 0 entries
		foreach($times as $key => $val)
		{
			if ($val == false || $val == '0')
			{
				$times[$key] = '';
			}
		}

		// If someone manually changes values
		if (!is_numeric($times['start_month']) 
			|| $times['start_month'] > 12 
			|| $times['start_month'] < 0)
		{
			$times['start_month'] = '';
		}
		if (!is_numeric($times['end_month']) 
			|| $times['end_month'] > 12 
			|| $times['end_month'] < 0)
		{
			$times['end_month'] = '';
		}

		if (!$currently_work)
		{

			$this->form_validation->set_rules('end-year', "'To' Year", "gte[{$times['start_year']},'From' Year]");

			if (!$this->form_validation->run())
				return $this->form_validation->invalid_response();

			if ($times['start_year'] == $times['end_year']
					&& $times['start_month'] != 0
					&& $times['end_month'] != 0)
			{
				$this->form_validation->set_rules('end-month', "'To' Month", "gte[{$times['start_month']},'From' Month]");
 
				if (!$this->form_validation->run())
					return $this->form_validation->invalid_response();
			}
		}
		else
		{
			$times['end_month'] = 0;		// Reset this value if they're "Presently working"
			$times['end_year'] = 0;		// Reset this value if they're "Presently working"

			if (!$this->form_validation->run())
				return $this->form_validation->invalid_response();
		}

		$position = $this->input->post('position');
		$location = $this->input->post('location');
		$company = $this->input->post('company');

		// keys in items are the tables
		$items = array(
			'locations' => $location,
			'positions' => $position,
			'companies' => $company
		);

		$this->data_model->add_items($items);

		$data = array(
			'id' => $this->input->post('item-id'),
			'user_id' => $this->user_id,
			'company' => $company,
			'position' => $position,
			'location' => $location,
			'start_month' => ($times['start_month'] ? date('F', mktime(0,0,0,$times['start_month'])) : ''),
			'start_year' => $times['start_year'],
			'end_month' => ($times['end_month'] ? date('F', mktime(0,0,0,$times['end_month'])) : ''),
			'end_year' => $times['end_year'],
			'description' => $this->input->post('description'),
		);

		if ($data['id'])
		{
			// Check to make sure that item-id hasn't been manually changed
			$this->db->select('id, user_id, order')
					 ->from("user_$diff_key")
					 ->where('id', $data['id']);

			 $row = $this->db->get()->row();
			 $current_user_id = $row->user_id;
			 $order = $row->order;			
		}
		else
		{
			$new_order = $this->db->select_max('order', 'max_order')
								  ->where('user_id', $this->user_id)
								  ->get("user_$diff_key")
								  ->row_array();

			$order = $new_order['max_order'] + 1;			
		}
		$data['order'] = $order;

		if (!isset($current_user_id) || $current_user_id == $this->user_id)
		{
			return $this->replace_row("user_$diff_key", $data);
		}
		else
		{
			return $this->form_validation->response(STATUS_UNKNOWN_ERROR);
		}
	}

	function update_multiple($type, $items)
	{
		$responses = array();
		foreach ($items as $item)
		{
			$update_function = "update_$type";

			$responses[] = $this->$update_function($item);
		}

//		var_export($responses);

		foreach($responses as $response)
		{
			if (!$response['success'])
			{
				return $this->form_validation->response(STATUS_UNKNOWN_ERROR);
			}
		}

		return $this->form_validation->response();
	}

	function delete_item($table, $item_id)
	{		
		$this->db->where('user_id', $this->user_id)
				 ->where('id', $item_id)
				 ->delete($table);

		return $this->db->affected_rows();
	}

	function get_stored_values($user_id, $requested, $type = 'rows')
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
					->where($where, $user_id);

			if ($type == 'rows')
			{
				$returned = $this->db->get()->row_array();

				$stored[$table] = $returned;
				foreach($rows as $row)
				{
					if (!isset($stored[$table][$row]))
						$stored[$table][$row] = '';
				}				
			}
			elseif ($type == 'results')
			{
				$returned = $this->db->order_by('order')->get()->result_array();
				$stored[$table] = $returned;	
			}

		}

		return $stored;
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
}

/* End of file profile_model.php */
/* Location: ./application/models/profile_model.php */