<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Import extends CI_Controller
{
	private $all_times;
	private $all_days;
	private $nav_data;
	private $empty_availability;

	function __construct()
	{
		parent::__construct();

		$this->tank_auth->bounce_if_unlogged();

		$this->all_days = array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');
		$this->all_times = array('6am','7am','8am','9am','10am','11am','12pm','1pm','2pm','3pm','4pm','5pm','6pm','7pm','8pm','9pm','10pm','11pm','12am','1am','2am','3am','4am','5am');
		$this->empty_availability = array(
			'6am' => array(),
			'7am' => array(),
			'8am' => array(),
			'9am' => array(),
			'10am' => array(),
			'11am' => array(),
			'12pm' => array(),
			'1pm' => array(),
			'2pm' => array(),
			'3pm' => array(),
			'4pm' => array(),
			'5pm' => array(),
			'6pm' => array(),
			'7pm' => array(),
			'8pm' => array(),
			'9pm' => array(),
			'10pm' => array(),
			'11pm' => array(),
			'12am' => array(),
			'1am' => array(),
			'2am' => array(),
			'3am' => array(),
			'4am' => array(),
			'5am' => array()
		);
	}

	function index()
	{
		if ($import_profile_data = $this->session->userdata('admin-import-profile-data'))
		{
			$_POST = $import_profile_data;
			// We unset this afterwards
		}

/*
		$_POST['import-photo-manipulation'] = 'crop';
//		$_POST['import-url'] = 'http://www.acadam.com/eng/3017/montreal-tutor-in-mathematics-and-french';
		$_POST['import-url'] = 'http://toronto.universitytutor.com/tutors/213314';
//		$_POST['import-url'] = 'http://www.acadam.com/eng/5336/montreal-tutor-in-mathematics--statistics-and-financial-mathematics';
//		$_POST['import-url'] = 'www.acadam.com/eng/9829/toronto-tutor-in-mathematics-and-arithmetic';
		$_POST['import-type'] = 'universitytutor';
		$_POST['import-sections'] = array('display_name');
*/
		// "import-" prefixed to names to not clash with later post vars. Later, change this by caching post variables at the beginning of each function

		$this->form_validation->set_rules('import-type', '', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('import-url', 'Profile URL/Address', 'trim|strip_tags|required|valid_url');
		$this->form_validation->set_rules('import-sections', 'the import sections', 'required|xss_clean');
		$this->form_validation->set_rules('import-for-about', '', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('import-photo-manipulation', '', 'trim|strip_tags|xss_clean');

//		var_dump($_POST);

//		echo $_POST['import-url'].'<br><br>';
		if (!$this->form_validation->run())
		{
			echo json_encode($this->form_validation->invalid_response());
//			var_dump($_POST);
//			var_dump($this->form_validation->invalid_response());
			return;
		}	

//		echo $_POST['import-url'].'<br><br>';

		$type = $this->input->post('import-type');
		$url = $this->input->post('import-url');
		$import_sections = $this->input->post('import-sections');

//		$import_sections = array('display_name', 'price', 'gender', 'location', 'about', 'can_meet', 'photo', 'education', 'reviews', 'availability', 'subjects');

		if ($type == 'universitytutor')
		{
			$a_site_name = 'a UniversityTutor';
		}
		elseif ($type == 'acadam')
		{
			$a_site_name = 'an Acadam';
		}
		else 	// Type is linkedin
		{
			$a_site_name = 'a LinkedIn';
		}

		// Add http://, if needed
		if (!(substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://'))
			$url = "http://$url";

		$domain = parse_url($url, PHP_URL_HOST);		

		if (in_array($type, array('universitytutor', 'acadam', 'linkedin'))
			&& strpos($domain, $type) !== FALSE)
		{
			// Check if URL even exists
			if (!@file_get_contents($url))
			{
				$errors = array("import-url" => $this->form_validation->make_error("Sorry, we can't access that website. Please check the URL you provided."));
				$response = $this->form_validation->response(STATUS_VALIDATION_ERROR, array('errors' => $errors));
				echo json_encode($response);
				return;
			}

			// Get HTML as simple_html_dom object
			$this->load->library('Simple_html_dom');
			$html = file_get_html($url);
			if ($type == 'universitytutor')
			{
				$is_profile = $html->find('#tutor_vcard');
			}
			elseif ($type == 'acadam')
			{
				$is_profile = $html->find('#user_avatar');
			}
			else 	// Type is linkedin
			{
				$is_profile = $html->find('.profile');
			}

			if (!$is_profile)
			{
				$errors = array("import-url" => $this->form_validation->make_error("Sorry, we can't find $a_site_name profile there. Please check the URL you provided."));
				$response = $this->form_validation->response(STATUS_VALIDATION_ERROR, array('errors' => $errors));
				echo json_encode($response);
				return;
			}

			$import_data = array(
				'import_url' => $url,
				'domain' => $domain
			);

			// Scrape appropriate site and save photo on our server
			$scrape_function = "_scrape_$type";
			$import_data = $import_data + $this->$scrape_function($html, $import_sections);
			$import_data['type'] = $type;

			if (isset($import_data['photo']))
			{
				$import_data['photo']['src'] = $this->_save_remote_photo($import_data['photo']['remote_url']);

				// Crop any Acadam photos, if crop selected
				if ($type == 'acadam'
					&& 	$this->input->post('import-photo-manipulation') == 'crop')
				{
					$this->_crop_photo($import_data['photo']['src']);
				}
			}

			if (isset($import_data['reviews']))
			{
				foreach($import_data['reviews'] as &$review)
				{
					$review['url'] = $url;
				}
				unset($review);
			}

			// Import the profile
			$this->load->model('import_model');
			$response = $this->import_model->import($import_data);
//			$response = $this->form_validation->response();
//			$response = 5;

//			var_dump($import_data);
		}
		else
		{
			$errors = array("import-url" => $this->form_validation->make_error("Sorry, that's not $a_site_name page. Please check the URL you provided."));
			$response = $this->form_validation->response(STATUS_VALIDATION_ERROR, array('errors' => $errors));
		}

		$this->session->unset_userdata('admin-import-account-data');
		$this->session->unset_userdata('admin-import-profile-data');

		echo json_encode($response);
	}

	// Currently inactive
/*
	function _scrape_linkedin($html, $import_sections)
	{
		$import_data = array();

		// Photo
		$photo_element = $html->find('.photo', 0);


		if ($photo_element)
		{
			$import_data['photo'] = array(
				'remote_url' => $html->find('.photo', 0)->src 
			);
			$image_size = getimagesize($import_data['photo']['remote_url']);


			$import_data['photo']['width'] = $image_size[0];
			$import_data['photo']['height'] = $image_size[1];
		}
		else
		{
			$import_data['photo'] = NULL;
		}

		// Name
		if ($first_name = $html->find('.given-name', 0))
		{
			$import_data['first_name'] = trim($first_name->plaintext);
			$import_data['last_name'] = trim($html->find('.family-name', 0)->plaintext);			
		}
		else 	// First/last aren't tagged, so just go with full name
		{
			$import_data['display_name'] = trim($html->find('.full-name', 0)->plaintext);
		}

		// Experience
		$experience = $html->find('#profile-experience', 0);
		if ($experience)
		{
			$experience_items = $experience->find('.position');
			$import_data['experience'] = array();

			foreach ($experience_items as $item)
			{
				$cur_experience = array(
					'position' => trim($item->find('.title', 0)->plaintext),
					'company' => trim($item->find('.summary', 0)->plaintext)
				);
				
				$period = $item->find('.period', 0);
				$start_data = trim($period->find('.dtstart', 0)->plaintext);

				if (is_numeric($start_data))	// Only year given
				{
					$cur_experience['start_year'] = $start_data;
				}
				else 	// Month and year
				{
					$start_data = explode(' ', $start_data);
					$cur_experience['start_month'] = trim($start_data[0]);
					$cur_experience['start_year'] = trim($start_data[1]);
				}					

				$end_data = $period->find('.dtend', 0);
				if (!$end_data)
				{
					$cur_experience['end_year'] = 0;	// 0 refers to 'Present'
				}
				else
				{
					$end_data = $end_data->plaintext;

					if(is_numeric($end_data))	// Only year given
					{
						$cur_experience['end_year'] = $end_data;
					}
					else 	// Month and year
					{
						$end_data = explode(' ', $end_data);
						$cur_experience['end_month'] = trim($end_data[0]);
						$cur_experience['end_year'] = trim($end_data[1]);
					}					
				}

				$location = $item->find('.location', 0);
				if ($location)
				{
					$cur_experience['location'] = trim($location->plaintext);
				}

				$description = $item->find('.description', 0);
				if ($description)
				{
					$cur_experience['description'] = trim($description->plaintext);
				}
				$import_data['experience'][] = $cur_experience;
			}
		}

		// Volunteering
		$volunteering = $html->find('#profile-volunteering', 0);
		if ($volunteering)
		{
			$volunteering_items = $volunteering->find('.position');
			$import_data['volunteering'] = array();

			foreach ($volunteering_items as $item)
			{
				$cur_volunteering = array(
					'position' => trim($item->find('.title', 0)->plaintext),
					'company' => trim($item->find('.summary', 0)->plaintext)
				);
				
				$period = $item->find('.period', 0);
				$start_data = trim($period->find('.dtstart', 0)->plaintext);

				if(is_numeric($start_data))	// Only year given
				{
					$cur_volunteering['start_year'] = $start_data;
				}
				else 	// Month and year
				{
					$start_data = explode(' ', $start_data);
					$cur_volunteering['start_month'] = trim($start_data[0]);
					$cur_volunteering['start_year'] = trim($start_data[1]);
				}					

				$end_data = $period->find('.dtend', 0);
				if (!$end_data)
				{
					$cur_volunteering['end_year'] = 0;	// 0 refers to 'Present'
				}
				else
				{
					$end_data = $end_data->plaintext;

					if(is_numeric($end_data))	// Only year given
					{
						$cur_volunteering['end_year'] = $end_data;
					}
					else 	// Month and year
					{
						$end_data = explode(' ', $end_data);
						$cur_volunteering['end_month'] = trim($end_data[0]);
						$cur_volunteering['end_year'] = trim($end_data[1]);
					}					
				}

				$location = $item->find('.location', 0);
				if ($location)
				{
					$cur_volunteering['location'] = trim($location->plaintext);
				}

				$description = $item->find('.description', 0);
				if ($description)
				{
					$cur_volunteering['description'] = trim($description->plaintext);
				}
				$import_data['volunteering'][] = $cur_experience;
			}
		}

		$about = $html->find('#profile-summary', 0);
		if ($about)
		{
			$import_data['about'] = $about->find('.summary', 0)->plaintext;
		}
		else
		{
			$import_data['about'] = NULL;
		}

		return $import_data;
	}
*/
	function _scrape_acadam($html, $import_sections)
	{
		$header = $html->find('.header_information_second', 0);
		$content = $html->find('.content', 0);
		
		$city_and_country = $header->find('ul', 0)->find('li', 1)->plaintext;
		$city_and_country = explode(',', $city_and_country);
		$country = trim($city_and_country['2']);

		$import_data = array();

		// Photo
		if (in_array('photo', $import_sections))
		{
			$import_data['photo'] = array(
				'remote_url' => 'http://acadam.com/'.$html->find('#user_avatar', 0)->src 
			);

			// Don't download default Acadam images (male, female, or no-gender)
			if (strpos($import_data['photo']['remote_url'], 'no_gender.png') !== FALSE
				|| strpos($import_data['photo']['remote_url'], 'men.png') !== FALSE
				|| strpos($import_data['photo']['remote_url'], 'woman.png') !== FALSE)
			{
				$import_data['photo'] = NULL;
			}
			else
			{
				$image_size = getimagesize($import_data['photo']['remote_url']);
	
				$manipulation = $this->input->post('import-photo-manipulation');

				if ($manipulation == 'crop')
				{
					$side_length = min($image_size[0], $image_size[1]);
					$import_data['photo']['width'] = $side_length;
					$import_data['photo']['height'] = $side_length;

				}
				else
				{
					$import_data['photo']['width'] = $image_size[0];
					$import_data['photo']['height'] = $image_size[1];
				}
			}
		}

		// Display Name
		if (in_array('display_name', $import_sections))
		{
			$import_data['display_name'] = $header->find('ul', 0)->find('li', 0)->plaintext;
		}

		// Gender
		if (in_array('gender', $import_sections))
		{
			$import_data['gender'] = $html->find('.item_list_profile', 0)
										  ->find('li', 0);
			if ($import_data['gender'])
				$import_data['gender'] = $import_data['gender']->find('span', 1);
			if ($import_data['gender'])
				$import_data['gender'] = trim($import_data['gender']->plaintext);

			switch ($import_data['gender'])
			{
				case 'Male':
					$import_data['gender'] = 'm';
					break;
				case 'Female':
					$import_data['gender'] = 'f';
					break;
				default:
					$import_data['gender'] = 'u';
					break;
			}
		}

		// Location
		if (in_array('location', $import_sections))
		{
			$import_data['location'] = array();
			$import_data['location']['lat'] = $html->find('#user_latitude', 0)->plaintext;
			$import_data['location']['lon'] = $html->find('#user_longitude', 0)->plaintext;
			$import_data['location']['specific'] = '';

			$import_data['location']['city'] = trim($city_and_country['0']);

			// Acadam lists the city, region, and country in the same line, so need to use [2] for country
			$import_data['location']['country'] = $country;

			$import_data['location']['name'] = $import_data['location']['city'].', '.$import_data['location']['country'];
		}

		// Price and currency
		if (in_array('price', $import_sections))
		{
			$prices = $html->find('.price-tag', 0)->plaintext;
			$import_data['price_and_currency'] = array();

			$prices = explode(' - ', $prices);

			$import_data['price_and_currency']['hourly_rate'] = floatval(filter_var($prices[0], FILTER_SANITIZE_NUMBER_INT) / 100);
			$import_data['price_and_currency']['hourly_rate_high'] = floatval(filter_var($prices[1], FILTER_SANITIZE_NUMBER_INT) / 100);

			if ($country == 'Canada')
				$import_data['price_and_currency']['currency'] = 'CAD';
			else
				$import_data['price_and_currency']['currency'] = 'USD';
		}

		// Subjects
		if (in_array('subjects', $import_sections))
		{
			$import_data['subjects'] = array();
			$subjects = $content->find('.section-container', 4)
								->find('a');

			foreach($subjects as $subject)
			{
				$subject_name = $subject->plaintext;
				if (!in_array($subject_name, $import_data['subjects']))
					$import_data['subjects'][] = $subject_name;
			}
		}

		// About
		if (in_array('about', $import_sections))
		{
			$import_data['about'] = $content->find('.section-container', 3)
											->find('p', 0);

			if ($import_data['about'])
				$import_data['about'] = $import_data['about']->plaintext;
			else
				$import_data['about'] = NULL;
		}

		// Can Meet
		if (in_array('can_meet', $import_sections))
		{
			$can_meet = $content->find('.section-container', 2)->find('.item_list_profile', 0);
			
			$import_data['can_meet'] = array(
				'students_home' => ($can_meet->find('li', 0)->class == 'switch_1' ? TRUE : FALSE),
				'tutors_home' => ($can_meet->find('li', 1)->class == 'switch_1' ? TRUE : FALSE),
				'online_local' => ($can_meet->find('li', 2)->class == 'switch_1' ? TRUE : FALSE),
				'centre' => ($can_meet->find('li', 3)->class == 'switch_1' ? TRUE : FALSE)
			);
		}

		// Education
		if (in_array('education', $import_sections))
		{
			$education_items = $content->find('.listDegrees');
	//var_dump($education_items);
			$import_data['education'] = array();

//			var_dump($education_items);

			foreach($education_items as $education_item)
			{
				$education_location = $education_item->find('li', 1)->plaintext;
				$import_education_item = array(
					'school' => trim(str_replace('Location: ', '', $education_location)),
					'field' => '',
					'notes' => ''
				);

				$education_degree_end_year = $education_item->find('li', 0)->plaintext;

				$import_education_item['degree'] = preg_replace('/(\(Still pursuing\)|\(Completed in \d{4}\)) /', '', $education_degree_end_year);
				$import_education_item['degree'] = trim(str_replace('Degree: ', '', $import_education_item['degree']));

				preg_match('/[0-9]{4}/', $education_degree_end_year, $import_education_item['end-year']);
				
				if ($import_education_item['end-year'])	// has a completed value
				{
					$import_education_item['end-year'] = (int) $import_education_item['end-year'][0];
					$import_education_item['start-year'] = $import_education_item['end-year'] - 4;
				}
				else
				{
					$import_education_item['end-year'] = 0; // Still pursuing
					$import_education_item['start-year'] = (int) (date('Y')) - 4;

				}

				$import_data['education'][] = $import_education_item;

//				var_dump($import_education_item);
			}
		}

		// Reviews
		if (in_array('reviews', $import_sections))
		{
			$import_data['reviews'] = array();
			$review_elements = $html->find('.comment');

			if ($review_elements)
			{
				foreach($review_elements as $element)
				{		
					$is_edit = $element->find('form');
					if ($is_edit)
					{
						continue;
					}

					$review = array();
					
					// Rating is 5 inputs, with 1 of them checked. The index+1 of the checked is the rating
					$rating = $element->find('.stars_box input');
					$count = count($rating);

					for ($i = 0; $i < $count; $i++)
					{
						$review['rating'] = 1;

						if ($rating[$i]->checked)
						{
							$review['rating'] = $i+1;
							break;
						}
					}

					$review['reviewer'] = $element->find('.review_name', 0)->plaintext;
					$review['content'] = $element->find('.commentFixed', 0)->plaintext;
					$review['content'] = preg_replace('/" (.+) "(.+)/', '$1', $review['content']);


					$import_data['reviews'][] = $review;
				}
			}
		}

		// Availability
		if (in_array('availability', $import_sections))
		{
			$availability = $html->find('#availabilities', 0);

			if ($availability)
			{
				$availability = str_split($availability->value, 7);

				// Acadam's weeks start on Sunday, so have to move it from end to beginning of all_days
				$acadam_all_days = $this->all_days;
				$sunday = array_pop($acadam_all_days);
				array_unshift($acadam_all_days, $sunday);

				foreach($availability as &$time_group)
				{
					$time_group = str_split($time_group);
					$time_group_count = count($time_group);

					for ($i = 0; $i < $time_group_count; $i++)
					{
						if ($time_group[$i])
						{
							$time_group[$i] = $acadam_all_days[$i];
						}
						else
						{
							unset($time_group[$i]);
						}
					}
				}
				unset($time_group);

				$times_count = count($this->all_times);
				$import_data['availability'] = array();

				$current_time_group = 0;
				// 6am-11pm; -6 to cover overnight in next section (all time groups are groups of 3, except overnight)
				for ($i = 0; $i < $times_count; $i+=3)
				{
					for ($j = $i; $j < $i+3; $j++)
					{
						$import_data['availability'][$this->all_times[$j]] = $availability[$current_time_group];
					}
					if ($current_time_group < 6)
						$current_time_group++;
				}
			}
		}

		return $import_data;
	}

	function _scrape_universitytutor($html, $import_sections)
	{
		$profile = $html->find('.tutor-profile', 0)->find('table', 0);
		$location_and_availability = $html->find('.location-and-availability', 0);

		$import_data = array();

		// Photo
		if (in_array('photo', $import_sections))
		{
			$import_data['photo'] = array(
				'remote_url' => $html->find('.photo', 0)->src,
				'width' => 100,
				'height' => 100
			);

			// Don't download default UT gif
			if (strpos($import_data['photo']['remote_url'], 'unknown_profile.gif') !== FALSE)
			{
				$import_data['photo'] = NULL;
			}
		}

		// Display Name
		if (in_array('display_name', $import_sections))
		{
			$import_data['display_name'] = $html->find('h2.fn', 0)->plaintext;
		}

		// Gender
		if (in_array('gender', $import_sections))
		{
			$import_data['gender'] = $profile->find('tr', 1)
											 ->find('td', 1)
											 ->plaintext;
			$import_data['gender'] = ($import_data['gender'] == 'Male' ? 'm' : 'f');
		}

		// Price and currency
		if (in_array('price', $import_sections))
		{
			$price_and_currency = $html->find('td.price', 0)->plaintext;
			$import_data['price_and_currency'] = array();

			$import_data['price_and_currency']['hourly_rate'] = filter_var($price_and_currency, FILTER_SANITIZE_NUMBER_INT);
			$import_data['price_and_currency']['hourly_rate_high'] = '';

			if (strpos($price_and_currency,'$') !== FALSE)
				$currency = 'USD';
			elseif (strpos($price_and_currency,'&pound;') !== FALSE)
				$currency = 'GBP';
			elseif (strpos($price_and_currency,'&yen;') !== FALSE)
				$currency = 'JPY';
			elseif (strpos($price_and_currency,'&euro;') !== FALSE)
				$currency = 'EUR';
			else
				$currency = substr($price_and_currency, 0, 3);

			$import_data['price_and_currency']['currency'] = $currency;
		}

		// Location
		if (in_array('location', $import_sections))
		{
			$import_data['location'] = array();
			$import_data['location']['lat'] = $html->find('.latitude', 0)->first_child()->title;
			$import_data['location']['lon'] = $html->find('.longitude', 0)->first_child()->title;
			$import_data['location']['specific'] = trim($html->find('.postal-code', 0)->plaintext);

			$city_and_country = $html->find('.adr', 0)->plaintext;
			$city_and_country = explode(',', $city_and_country);
			$import_data['location']['city'] = $city_and_country['0'];
			$import_data['location']['country'] = trim($city_and_country['1']);

			if (strlen($import_data['location']['country']) == 2)	// UT uses 2-letter state codes for in-USA tutors
				$import_data['location']['country'] = 'United States';

			$import_data['location']['name'] = $import_data['location']['city'].', '.$import_data['location']['specific'].', '.$import_data['location']['country'];
		}

		// Subjects
		if (in_array('subjects', $import_sections))
		{
			$import_data['subjects'] = array();
			$subject_elements = $profile->find('tr', 2)
										->find('td', 1)
										->find('a');

			foreach($subject_elements as $element)
			{
				$import_data['subjects'][] = $element->plaintext;
			}
		}

		// About
		if (in_array('about', $import_sections))
		{
			$for_about = $this->input->post('import-for-about');

			switch ($for_about)
			{
				case 'education':
					$row_num = 3;
					break;
				case 'experience':
					$row_num = 4;
					break;
				default:	// hobbies
					$row_num = 5;
			}

			$import_data['about'] = $profile->find('tr', $row_num)
									  	 	->find('td', 1)
									  	 	->plaintext;
		}

		// Reviews
		if (in_array('reviews', $import_sections))
		{
			$import_data['reviews'] = array();
			$review_elements = $html->find('.tutor-profile-reviews', 0);

			if ($review_elements)
			{
				$review_elements = $review_elements->find('tr');

				foreach($review_elements as $element)
				{
					// Sometimes a review has no content
					$description = $element->find('.description', 0);

					if (!$description)
						continue;

					$review = array();
					$review['content'] = $element->find('.description', 0)->plaintext;
					$review['rating'] = $element->find('.rating', 0)->plaintext;
					$review['reviewer'] = $element->find('.reviewer', 0)->plaintext;

					$import_data['reviews'][] = $review;
				}
			}
		}

		// Travel Notes
		if (in_array('travel_notes', $import_sections))
		{
			$import_data['travel_notes'] = $location_and_availability->find('tr', 3)
						 										  ->find('td', 1)
																  ->plaintext;
			$import_data['travel_notes'] = trim($import_data['travel_notes']);
		}

		// Availability
		if (in_array('availability', $import_sections))
		{
			$availability = $location_and_availability->find('tr', 1)
													  ->find('td', 1)
													  ->innertext;
			$availability = array_filter(explode('<br/>', $availability));

			$days = array_map(function($avail_string)
			{
				return strtok($avail_string, " ");
			}, $availability);

			$availability_count = count($availability);

			$import_data['availability'] = $this->empty_availability;

			for($i = 0; $i < $availability_count; $i++)
			{
				$days = $this->_get_ut_days($availability[$i]);
	//			var_dump($days);

				// for all times
				if (strpos($availability[$i], 'any time') !== FALSE)
				{
					foreach($import_data['availability'] as &$time)
					{
						foreach ($this->all_days as $day)
						{
							if (in_array($day, $days))
								if (!in_array($day, $time))
									$time[] = $day;
						}
					}
					unset($time);
				}
				else
				{
					if (strpos($availability[$i], 'morning') !== FALSE)
					{
						$times = array('8am', '9am', '10am', '11am');
					}
					elseif (strpos($availability[$i], 'afternoon') !== FALSE)
					{
						$times = array('12pm', '1pm', '2pm', '3pm');	
					}
					elseif (strpos($availability[$i], 'evening') !== FALSE)
					{
						$times = array('4pm', '5pm', '6pm', '7pm');	
					}
					else
					{
						$times = $this->_get_ut_after_times($availability[$i]);					
					}

					foreach($times as $time)
					{
						foreach ($this->all_days as $day)
						{
							if (in_array($day, $days))
								if (!in_array($day, $import_data['availability'][$time]))
									$import_data['availability'][$time][] = $day;
						}
					}
				}
			}
		}

		return $import_data;
	}

	// Convert UT syntax to array of days
	function _get_ut_days($avail_string)
	{
		$days = array();
		$day = strtok($avail_string, " ");

		if ($day == 'Any')
		{
			$days = array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');
		}
		elseif ($day == 'Weekdays')
		{
			$days = array('mon', 'tue', 'wed', 'thu', 'fri');
		}
		elseif ($day == 'Weekends')
		{
			$days = array('sat', 'sun');
		}
		elseif ($day == 'Mon/Wed/Fri')
		{
			$days = array('mon', 'wed', 'fri');
		}
		elseif ($day == 'Tues/Thurs')
		{
			$days = array('tue', 'thu');
		}
		else
		{
			$days = array();			
			
			switch ($day)
			{
				case 'Monday':
					$days[] = 'mon';
					break;
				case 'Tuesday':
					$days[] = 'tue';
					break;
				case 'Wednesday':
					$days[] = 'wed';
					break;
				case 'Thursday':
					$days[] = 'thu';
					break;
				case 'Friday':
					$days[] = 'fri';
					break;
				case 'Saturday':
					$days[] = 'sat';
					break;
				case 'Sunday':
					$days[] = 'sun';
					break;
			}
		}

//		var_dump($day);
		return $days;
	}

	function _get_ut_after_times($avail_string)
	{
		$after_time = $this->_parse_ut_after_time($avail_string);
		
		// last time is at 9pm. Any "after x" go to 9pm. Exception is 9pm, which goes to 10pm
		$last_time = ($after_time == '9pm' ? '10pm': '9pm');
		$last_time_index = array_search($last_time, $this->all_times);

		$after_time_index = array_search($after_time, $this->all_times);
		$times = array();

		for ($i = $after_time_index; $i < $last_time_index; $i++)
		{
			$times[] = $this->all_times[$i];
		}

		return $times;
	}

	function _parse_ut_after_time($avail_string)
	{
		preg_match('/[0-9].+/', $avail_string, $after_time);
		$after_time = strtolower($after_time[0]);
		$after_time = str_replace(' noon', 'pm', $after_time);
		return $after_time;
	}

	function _save_remote_photo($photo_url)
	{
		$photo = @file_get_contents($photo_url);
		
		if ($photo)
		{
			$folder_path = "assets/uploads/tmp/";
			$target_path = $folder_path.md5($_SERVER['REQUEST_TIME']) . '.jpg';;

			if (file_put_contents($target_path, $photo))
			{
				return $target_path;
			}
		}
	}

	function _crop_photo($src)
	{
		// Get image object
		$image_info = getimagesize($src);
		$image_type = $image_info[2];
		if ($image_type == IMAGETYPE_JPEG) 
		   $image = @imagecreatefromjpeg($src);
		elseif($image_type == IMAGETYPE_GIF) 
		   $image = @imagecreatefromgif($src);
		elseif($image_type == IMAGETYPE_PNG)
		   $image = @imagecreatefrompng($src);

		$width = imagesx($image);
		$height = imagesy($image);

		// We want to crop a square of maximum size; so we take the min dimension as the side length
		$side_length = min($width, $height);

//		var_dump($width, $height, $side_length);


		$canvas = imagecreatetruecolor($side_length, $side_length);

		imagecopy($canvas, $image, 
					0, 0,
					0-($side_length-$width)/2, 0-($side_length-$height)/2,
					$width, $height);

		// Save as JPG, don't need to deal with PNGs or GIFs
		imagejpeg($canvas, $src, 100);
	}
}