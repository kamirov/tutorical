<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Find Model
 *
 * This model retrieves tutors and requests for the find page. 
 *
 */
class Find_model extends CI_Model {

	function __construct() {
		parent::__construct();
		//$ci =& get_instance();
		$this->load->model('tank_auth/users');
	}

	function count($search_domain, $search_group, $args) 
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

		// Quit if local and no lat/lon
		if (!($lat && $lon) && $search_domain == 'local')
		{
			return NULL;
		}

		if ($opts['units'] == 'km')
		{
			$mult = 6371;	// km multiplier
		}
		else
			$mult = 3959;	// miles multiplier

		// Count locals
		if ($search_domain == 'local')
		{
			if ($search_group == 'tutors')
			{
				$subjects_clause = ($opts['subject_id'] ? " AND us.subject_id = {$opts['subject_id']}" : '');

				$sql = "
					SELECT COUNT(*) AS item_count FROM
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
			}
			elseif ($search_group == 'requests')
			{
				$subjects_clause = ($opts['subject_id'] ? " AND rs.subject_id = {$opts['subject_id']}" : '');

				$sql = "
					SELECT COUNT(*) AS item_count FROM
					(
					SELECT DISTINCT r.id, 
						($mult
						* acos
						( 
						cos(radians($lat)) 
						* cos(radians(r.location_lat)) 
						* cos(radians(r.location_lon) - radians($lon)) 
						+ sin(radians($lat)) 
						* sin(radians(r.location_lat))
						) 
						) AS distance
						FROM `requests` r
						JOIN `requests_subjects` rs ON `r`.`id` = `rs`.`request_id`
						WHERE `r`.`status` = ".REQUEST_STATUS_OPEN." 
							AND r.user_id != 0
							AND (r.type = ".REQUEST_TYPE_LOCAL." OR r.type = ".REQUEST_TYPE_BOTH.")
						$subjects_clause
						HAVING `distance` <= {$opts['distance']}
					) AS tmp
				";			
			}
			else 	// subjects
			{
				$sql = "
					SELECT COUNT(*) AS item_count FROM
					(
					SELECT DISTINCT us.subject_id,
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
						HAVING `distance` <= {$opts['distance']}
					) AS tmp
				";			
			}				
		}
		else 	// Count distance
		{
			if ($search_group == 'tutors')
			{
				$subjects_clause = ($opts['subject_id'] ? " AND us.subject_id = {$opts['subject_id']}" : '');
				$sql = "
					SELECT COUNT(*) AS item_count FROM
					(
					SELECT DISTINCT tp.id
						FROM `tutor_profiles` tp
						JOIN `users_subjects` us ON `tp`.`user_id` = `us`.`user_id`
						WHERE `tp`.`is_active` = 1
							AND tp.can_meet_online_distant = 1
							$subjects_clause
					) AS tmp
				";
			}
			elseif ($search_group == 'requests')
			{
				$subjects_clause = ($opts['subject_id'] ? " AND rs.subject_id = {$opts['subject_id']}" : '');
				$sql = "
					SELECT COUNT(*) AS item_count FROM
					(
					SELECT DISTINCT r.id
						FROM `requests` r
						JOIN `requests_subjects` rs ON `r`.`id` = `rs`.`request_id`
						WHERE `r`.`status` = ".REQUEST_STATUS_OPEN." 
							AND r.user_id != 0
							AND (r.type = ".REQUEST_TYPE_DISTANCE." OR r.type = ".REQUEST_TYPE_BOTH.")
						$subjects_clause
					) AS tmp
				";			
			}
			else 	// subjects
			{
				$sql = "
					SELECT COUNT(*) AS item_count FROM
					(
					SELECT DISTINCT us.subject_id
						FROM `tutor_profiles` tp
						JOIN `users_subjects` us ON `tp`.`user_id` = `us`.`user_id`
						WHERE `tp`.`is_active` = 1
							AND tp.can_meet_online_distant = 1
					) AS tmp
				";
			}
		}

//		var_export(nl2br($sql));

		$query = $this->db->query($sql);

		$count = $query->row()->item_count;

//		var_dump($count);
		return $count;
	}

	function find($search_domain, $search_group, $args) 
	{	
		$defaults = array(
			'lat' => NULL,
			'lon' => NULL,
			'subject_id' => NULL,
			'sort' => 'distance', 
			'distance' => DEFAULT_FIND_DISTANCE,
			'units' => 'km',
			'limit_from' => 0,
			'limit_count' => 5,
			'include_userdata' => FALSE,
			'include_logged_user' => TRUE,
			'exclude_ids' => NULL 	// Currently only works for local requests. Should also be in count function above. Why is it not? Laziness.
		);

		$opts = array_merge($defaults, $args);

//		var_dump($opts);

		$lat = $opts['lat'];
		$lon = $opts['lon'];

		// Quit if local and no lat/lon
		if (!($lat && $lon) && $search_domain == 'local')
		{
			return NULL;
		}

		if ($opts['units'] == 'km')
		{
			$mult = 6371;	// km multiplier
		}
		else
			$mult = 3959;	// miles multiplier

		if ($opts['include_userdata'])
		{
			$userdata_select = 'u.userdata,';
		}
		else
		{
			$userdata_select = '';
		}

		// To exclude the logged user, we get their ID and use it later on
		if (!$opts['include_logged_user'])
		{
			$logged_user_id = $this->session->userdata('user_id');
		}

		$find_results = array(
			'lats' => array(),
			'lons' => array(),
			'items' => array()
		);

		if ($opts['sort'] == 'rating')
		{
			if ($search_group == 'requests')
			{
				$opts['sort'] = 'new';
			}

			$averages = $this->db->select('AVG(num_of_reviews) AS num_of_reviews, AVG(average_rating) AS average_rating')
					 			 ->from('tutor_profiles')
					 			 ->where('num_of_reviews > 0')
					 			 ->get()->row_array();

			$avg_rating = $averages['average_rating'];
			$avg_rating = 2.5;
			$avg_num_of_reviews = $averages['num_of_reviews'];

			// "If addition" is to rank low-rankers above no-rankers

			$additional_select = "(
									( 
										($avg_num_of_reviews * $avg_rating)
										+ (tp.num_of_reviews * tp.average_rating)
									)
									/ ($avg_num_of_reviews + tp.num_of_reviews)
								  ) AS bayesian_rating";
/*
			$additional_select = "(
									IF (tp.num_of_reviews > 0, 1, 0)
									*
									( 
										($avg_num_of_reviews * $avg_rating)
										+ (tp.num_of_reviews * tp.average_rating)
									)
									/ ($avg_num_of_reviews + tp.num_of_reviews)
								  ) AS bayesian_rating";
*/
/*
			$min_reviews_needed = 1;
			$additional_select = "
								(
									(tp.num_of_reviews / (tp.num_of_reviews + $min_reviews_needed))
									* tp.average_rating
									+ ($min_reviews_needed / (tp.num_of_reviews + $min_reviews_needed))
									* $avg_rating
								) as bayesian_rating
			";
*/
		}
		else
		{
			$additional_select = '';
		}

		// Find locals
		if ($search_domain == 'local')
		{
			if ($search_group == 'tutors')
			{
				$this->db
						->distinct()
						->select("
							tp.*,
							up.price_type, up.price, up.price_high, up.currency, up.notes AS price_notes, $userdata_select
							u.username, u.display_name, u.email, u.avatar_path, UNIX_TIMESTAMP(u.created) posted,
							ul.user_id, ul.lat, ul.lon,
							$additional_select,
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
						", FALSE)
						->from('tutor_profiles tp')
						->join('user_locations ul', 'tp.user_id = ul.user_id')
						->join('user_prices up', 'up.user_id = ul.user_id')
						->join('users u', 'up.user_id = u.id')
						->join('users_subjects us', 'us.user_id = u.id')
						->where('tp.is_active', TRUE)
						->having('distance <= '.$opts['distance']);
				if ($opts['limit_count'])
					$this->db->limit($opts['limit_count'], $opts['limit_from']);

				if ($opts['subject_id'])
					$this->db->where('us.subject_id', $opts['subject_id']);
			}
			elseif ($search_group == 'requests')
			{
				$this->db
						->distinct()
						->select("
							r.id, r.user_id, r.details, r.price, r.currency, r.location_lat lat, r.location_lon lon, UNIX_TIMESTAMP(r.posted) posted, r.num_of_applications, r.status,
							u.username, u.display_name, u.email, u.avatar_path,
							($mult
							 * acos
								( 
									  cos(radians($lat)) 
									* cos(radians(r.location_lat)) 
									* cos(radians(r.location_lon) - radians($lon)) 
									+ sin(radians($lat)) 
									* sin(radians(r.location_lat))
								) 
							) AS distance
						")
						->from('requests r')
						->join('users u', 'r.user_id = u.id')
						->join('requests_subjects rs', 'r.id = rs.request_id')
						->where('r.status', REQUEST_STATUS_OPEN)
						->where('r.user_id !=', 0)
						->where_in('r.type', array(REQUEST_TYPE_LOCAL, REQUEST_TYPE_BOTH))
						->having('distance <= '.$opts['distance']);

				if ($opts['exclude_ids'])
				{
					$this->db->where_not_in('r.id', $opts['exclude_ids']);
				}
				if (!$opts['include_logged_user'])
				{
					$this->db->where('r.user_id != '.$logged_user_id);
				}
				if ($opts['subject_id'])
					$this->db->where('rs.subject_id', $opts['subject_id']);
				if ($opts['limit_count'])
					$this->db->limit($opts['limit_count'], $opts['limit_from']);
			}
			elseif ($search_group == 'subjects')
			{
				$result = $this->db
						->query("
							SELECT DISTINCT s.name, sc.name as category, COUNT( s.name ) AS tutor_count FROM
							((
							SELECT DISTINCT u.id AS main_user_id, 
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
							FROM (`tutor_profiles` tp)
							JOIN `user_locations` ul ON `tp`.`user_id` = `ul`.`user_id`
							JOIN `users` u ON `ul`.`user_id` = u.id
							JOIN `users_subjects` us ON `us`.`user_id` = ul.user_id
							WHERE `tp`.`is_active` = 1
							HAVING `distance` <= ".$opts['distance']."
							) AS ids_and_distances)
							JOIN users_subjects us ON us.user_id = main_user_id
							JOIN subjects s ON s.id = us.subject_id
							JOIN subject_categories sc ON s.subject_category_id = sc.id
							WHERE s.status = ".ITEM_STATUS_ACTIVE."
							GROUP BY s.name
							ORDER BY category, s.name
						");
			}
		}
		else 	// find distance
		{
			if ($search_group == 'tutors')
			{
				$this->db
						->distinct()
						->select("
							tp.*,
							up.price_type, up.price, up.price_high, up.currency, up.notes AS price_notes,
							u.id, u.username, u.display_name, u.email, u.avatar_path, UNIX_TIMESTAMP(u.created) AS posted, $userdata_select 
							ul.country, ul.city,
							$additional_select,
						", FALSE)
						->from('tutor_profiles tp')
						->join('users u', 'tp.user_id = u.id')
						->join('user_prices up', 'up.user_id = u.id')
						->join('user_locations ul', 'tp.user_id = ul.user_id')
						->join('users_subjects us', 'us.user_id = u.id')
						->where('tp.is_active', TRUE)
						->where('tp.can_meet_online_distant', TRUE);
				if ($opts['limit_count'])
					$this->db->limit($opts['limit_count'], $opts['limit_from']);

				if ($opts['subject_id'])
					$this->db->where('us.subject_id', $opts['subject_id']);
			}
			elseif ($search_group == 'requests')
			{
				$this->db
						->distinct()
						->select("
							r.id, r.user_id, r.type, r.details, r.price, r.currency, r.location_lat lat, r.location_lon lon, UNIX_TIMESTAMP(r.posted) posted, r.num_of_applications, r.status, r.location_city city, r.location_country country,
							u.username, u.display_name, u.email, u.avatar_path
						")
						->from('requests r')
						->join('users u', 'r.user_id = u.id')
						->join('requests_subjects rs', 'r.id = rs.request_id')
						->where('r.status', REQUEST_STATUS_OPEN)
						->where('r.user_id !=', 0)
						->where_in('r.type', array(REQUEST_TYPE_DISTANCE, REQUEST_TYPE_BOTH));
				if ($opts['limit_count'])
					$this->db->limit($opts['limit_count'], $opts['limit_from']);

				if ($opts['subject_id'])
					$this->db->where('rs.subject_id', $opts['subject_id']);
			}
			else 	// subjects
			{
				$result = $this->db
						->query("
							SELECT DISTINCT s.name, sc.name as category, COUNT( s.name ) AS tutor_count FROM
							((
							SELECT DISTINCT u.id AS main_user_id
							FROM (`tutor_profiles` tp)
							JOIN `users` u ON `tp`.`user_id` = u.id
							JOIN `users_subjects` us ON `us`.`user_id` = tp.user_id
							WHERE `tp`.`is_active` = 1
								AND tp.can_meet_online_distant = 1
							) AS ids_and_distances)
							JOIN users_subjects us ON us.user_id = main_user_id
							JOIN subjects s ON s.id = us.subject_id
							JOIN subject_categories sc ON s.subject_category_id = sc.id
							WHERE s.status = ".ITEM_STATUS_ACTIVE."
							GROUP BY s.name
							ORDER BY category, s.name
						");
			}
		}

		if ($search_group != 'subjects')
		{
			if ($opts['sort'] == 'distance')
				$this->db->order_by('distance', 'ASC');
			elseif ($opts['sort'] == 'new')
				$this->db->order_by('posted', 'DESC');
			elseif ($opts['sort'] == 'price')
			{
				if ($search_domain == 'distance')
				{
					$second_order = '';
				}
				else
				{
					$second_order = ', distance ASC';
				}
				$asc_desc = ($search_group == 'tutors' ? 'ASC' : 'DESC');
				$this->db->order_by("price $asc_desc".$second_order); // second order can't be in the double quotes :(
			}
			else // sort == rating
			{
				if ($search_domain == 'distance')
				{
					$this->db->order_by("bayesian_rating DESC");						
				}
				else
				{
					$this->db->order_by("bayesian_rating DESC, distance ASC");
				}
			}

			$result = $this->db->get()->result_array();
		}
		else
		{
			$result = $result->result_array(); 
		}

//		var_export(nl2br($this->db->last_query()));
//		var_dump(count($result));

		if ($search_domain == 'distance')
		{
			foreach($result as $item) 
			{	
				if ($search_group != 'subjects')
				{
					$country_code = $this->get_country_code($item['country']);
					$item['flag_url'] = base_url("assets/images/flags/$country_code.gif");
					$item['currency_sign'] = get_currency_sign($item['currency']);
				}

				if ($search_group == 'tutors')
				{
					$item['avatar_url'] = base_url($item['avatar_path']);
				}
				elseif ($search_group == 'requests')
				{
					$item['subjects_string'] = $this->requests_model->get_requests_subjects_string($item['id']);					
				}				

				$find_results['items'][] = $item;
			}
			$find_results['current_page_count'] = count($find_results['items']);
		}
		elseif ($search_group != 'subjects')
		{
			$find_results['original_page_count'] = count($result);	// Used because we might remove some elements down below
			$cur_marker_letter = 'A';
			$fill_color = '6690BA';
			$label_color = '000000';
			$stroke_color = '000000';

			foreach($result as $item) 
			{	
				// Currently only for requests, so no need to check search group. Here we're removing any requests where the logged user applied
				if (!$opts['include_logged_user'])
				{
					$this->db->select('rt.tutor_id')
							 ->from('requests_tutors rt')
							 ->join('requests r', 'r.id = rt.request_id')
							 ->where('r.id', $item['id']);

					$applied_tutor_ids = combine_subarrays($this->db->get()->result_array(), 'tutor_id');
					
					if (in_array($logged_user_id, $applied_tutor_ids))
					{
						continue;
					}
				}

				$item['avatar_url'] = base_url($item['avatar_path']); 

				$item['currency_sign'] = get_currency_sign($item['currency']);

				$item['marker_url'] = 'http://www.google.com/mapfiles/marker'.$cur_marker_letter++.'.png';
	//			$item['marker_url'] = 'http://www.googlemapsmarkers.com/v1/'.$cur_marker_letter++.'/'.$fill_color.'/'.$label_color.'/'.$stroke_color.'/';
					
				$item['distance'] = round($item['distance'], 2);

				if ($item['distance'] < 0.1)
					$item['distance'] = 'Very close';
				elseif ($item['distance'] == 1 && $opts['units'] == 'miles')
					$item['distance'] .= ' mile away';				
				else
					$item['distance'] .= ' '.$opts['units'].' away';

				if ($search_group != 'tutors')
				{
					$item['subjects_string'] = $this->requests_model->get_requests_subjects_string($item['id']);
				}

				$find_results['items'][] = $item;
				$find_results['lats'][] = $item['lat'];
				$find_results['lons'][] = $item['lon'];
			}
			$find_results['current_page_count'] = count($find_results['items']);
		}

		if ($search_group == 'subjects')  // Group is subjects
		{
			$find_results = array();
			$find_results['items'] = array();
			$find_results['count'] = count($result);

			foreach($result as $item)
			{
				if (!isset($find_results['items'][$item['category']]))
				{
					$find_results['items'][$item['category']] = array();					
				}
				$find_results['items'][$item['category']][] = array(
					'name' => $item['name'],
					'tutor_count' => $item['tutor_count']
				);
			}

//			return $result;
		}
//		var_dump($find_results['current_page_count']);

		return $find_results;
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

/* End of file find_model.php */
/* Location: ./application/models/find_model.php */