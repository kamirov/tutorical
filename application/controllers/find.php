<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Find extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('security', 'form_validation', 'geography', 'paginator'));
		$this->load->model(array('data_model'));
		
	}

	function index()
	{
		$data = array();
		$search_subject = $this->input->post('search-subject');
/*
		// Only check for new subject IF the passed subject is not the one stored in the session
		if (!$session_subject || $search_subject != $session_subject['name'])
		{

			$subject = $this->subjects_model->get_subject('name', $search_subject);	// returns no id, "All Subjects" if nothing found

			var_dump($subject); return;
			$this->session->set_userdata('search-subject', $subject);
			$session_subject = $this->session->userdata('search-subject');
		}
*/

		$subject_query = ae_urlencode($search_subject);
		$search_group_full = explode('-', $this->input->post('search-group'));

		if ($search_group_full[0])
		{
			$search_domain = $search_group_full[0];
			$search_group = $search_group_full[1];
		}		
		else
		{
			$search_domain = $search_group = '';
		}

		if ($search_domain == 'distance')
		{
			if ($subject_query)
				redirect("find/$search_domain/$search_group/$subject_query");
			else
				redirect("find/$search_domain/$search_group");
		}

		$readable_location = $this->input->post('search-location');

		$current_location = $this->session->userdata('search-location');
		if ($current_location)
		{
			$current_location = $current_location['readable'];
		}

		// If no location passed (also occurs if someone visits /find directly), show default page
		if (!$readable_location)
		{
			$data['meta'] = $this->config->item('find');
			$this->load->page('find/default', $data);
			return;
		}

		$loc_from = $this->input->post('loc-from');
		$geocoder_status = $this->input->post('geocoder-status');

		if ($geocoder_status == 'OVER_QUERY_ON_BOTH')
		{
			$data['readable_location'] = $readable_location;
			$data['current_subject_id'] = $session_subject['id'];
			$data['meta'] = $this->config->item('find-over-query');
	
			$this->load->page('find/over-query', $data);
			return;
		}

		// Do this before the loc-from check because: if 2 windows open, 'a' searches for HOOGALABOOGALA and 'b' searches for Toronto. If 'a' then presses back, and searches HOOGALABOOGALA, it would attempt to DB check it
		if ($geocoder_status == 'ZERO_RESULTS' || $geocoder_status == 'OVER_QUERY_ON_CLIENT')
		{
			$location = array(
				'lat' => NULL,
				'lon' => NULL,
				'status' => $geocoder_status,
				'place_changed' => TRUE
			);			
		}
		// if readable_location exists, then loc_from has to be set (since it would come from the form; direct access would send them straight to the /find/location/subject page)
		elseif ($loc_from == LOC_FROM_SESSION)
		{
			$session_location = $this->session->userdata('search-location');

			// If location is the same (even if case changed), we use the session's lat-lon vars BUT change the readable location (this solves the 'toRONTO' in session, 'Toronto' entered)
			// Also, we do this check here again in case of malicious input
			if (strcasecmp($readable_location, $session_location['readable']) === 0)
			{		
				$location = array(
					'lat' => $session_location['lat'],
					'lon' => $session_location['lon'],
					'status' => $session_location['status'],
					'place_changed' => FALSE
				);
			}
			// This is needed in case someone makes a search, then opens a 2nd tab and makes a different loc search, then makes a search with the first window again; the first window still returns LOC_FROM_SESSION, though the session lat-lon vars have changed; the result would be a location name with incorrect lat-lons saved to the session
			else
			{
				$location = $this->_get_location_from_db($readable_location);
			}
		}
		elseif ($loc_from == LOC_FROM_DB)
		{
			$location = $this->_get_location_from_db($readable_location);
		}
		else 	// $loc_from == LOC_FROM_GEOCODE
		{
			if ($geocoder_status == 'OK')
			{
				$location = array(
					'lat' => $this->input->post('lat'),
					'lon' => $this->input->post('lon'),
					'status' => LOC_STATUS_FOUND,
					'place_changed' => TRUE
				);

				$location_for_db = array(
					'name' => $readable_location,
					'lat' => $location['lat'],
					'lon' => $location['lon'],
					'occurrences' => 1,
					'status' => ITEM_STATUS_PENDING
				);
				$this->data_model->add_item($location_for_db, 'locations');
			}
			else
			{
				$location = array(
					'lat' => $this->input->post('lat'),
					'lon' => $this->input->post('lon'),
					'status' => $geocoder_status,
					'place_changed' => TRUE
				);

			}
		}

		$location['readable'] = $readable_location;
		$this->session->set_userdata('search-location', $location);

		$location_query = ae_urlencode($readable_location);

		if ($search_group == 'subjects')
		{
			redirect("find/$search_domain/$search_group/$location_query");
		}
		else
		{
			// This needed in case someone manually changes the group value
			if ($search_group != 'tutors')
				$search_group = 'requests';

			redirect("find/$search_domain/$search_group/$location_query/$subject_query");
		}
	}

	function local($search_group, $location_query, $subject_query = '')
	{
		if (!in_array($search_group, array('tutors', 'requests', 'subjects')))
		{
			show_404();
		}

		$subject_query = str_replace('_', '-', $subject_query);
		$location_query = str_replace('_', '-', $location_query);

		if (!$location_query)
		{
			redirect('find');
		}
		// Get plural and singular of group (requests or tutors)
		$readable_search_groups = str_replace('-', ' ', $search_group);
		$readable_search_group = substr($search_group, 0, -1); 
		$data['groups'] = $readable_search_groups;
		$data['group'] = $readable_search_group;

		$this->session->set_userdata('search-group', $search_group);
		$this->session->set_userdata('search-domain', 'local');

		$canonical_url = "http://tutorical.com/find/local/$search_group/$location_query";

		if ($search_group != 'subjects')
		{
			if ($subject_query)
			{
				$canonical_url .= "/$subject_query";
			}

			// Meta values are modified near the end of this function (need to get user data for them)
			$readable_subject = ae_urldecode($subject_query);
			$subject = $this->session->userdata('search-subject');

			if (!$subject || strcasecmp($readable_subject, $subject['name']) !== 0)	// no session == direct URL with no prior searches
			{
				$subject = $this->subjects_model->get_subject('name', $readable_subject);	// returns no id, "All Subjects" if nothing found
				$this->session->set_userdata('search-subject', $subject);
				$subject = $this->session->userdata('search-subject');

				// If subject we don't offer was requested, send to no-subject-found page. We do this check again here because subject was just changed. Also, in session we keep "All Subjects" to update the form
				if (strcasecmp($readable_subject, $subject['name']) !== 0)	
				{	
					$data['meta'] = $this->config->item('find-no-subject');
					$data['readable_subject'] = ae_urldecode($subject_query);
					$data['groups'] = $readable_search_groups;
					$data['group'] = $readable_search_group;

					foreach($data['meta'] as $key => $val)
					{
						$data['meta'][$key] = str_replace('{SUBJECT}', $data['readable_subject'], $data['meta'][$key]);
						$data['meta'][$key] = str_replace('{GROUP}', $readable_search_group, $data['meta'][$key]);
					}
					$data['extra_make_tutor_request'] = TRUE;
						
					$this->load->page('find/no-subject', $data);
					return;	
				}
			}
		}
		else
		{
			$subject = $this->subjects_model->get_subject('name', '');
		}

		$readable_location = ae_urldecode($location_query);
		$location = $this->session->userdata('search-location');

		// If location in session (search form used) and location query is for session location, then we look at geocoder results
		if ($location && strcasecmp($readable_location, $location['readable']) === 0)
		{
			if ($location['status'] == 'ZERO_RESULTS')
			{
				$data['meta'] = $this->config->item('find-location-not-found');
				$data['meta']['canonical'] = $canonical_url;
				$data['readable_location'] = $readable_location;

				foreach($data['meta'] as $key => $val)
				{
					$data['meta'][$key] = str_replace('{LOCATION}', $data['readable_location'], $data['meta'][$key]);
				}
		
				$this->load->page('find/location-not-found', $data);
				return;	
			}
			elseif ($location['status'] == 'OVER_QUERY_ON_CLIENT')
			{
				$geocoded_location = $this->geography->geocode($readable_location);
//				$geocoded_location['status'] = 'OVER_QUERY_LIMIT';

				// If OK, then server-side geocoding worked
				if ($geocoded_location['status'] == 'OK')
				{
					$location = $geocoded_location;
					$location['place_changed'] = TRUE;
					$this->session->set_userdata('search-location', $location);

					$location_for_db = array(
						'name' => $readable_location,
						'lat' => $location['lat'],
						'lon' => $location['lon'],
						'occurrences' => 1,
						'status' => ITEM_STATUS_PENDING
					);
					$this->data_model->add_item($location_for_db, 'locations');
				}
				// If OVER_QUERY_LIMIT, then both client and server are over limit, so show over query problem page
				elseif ($geocoded_location['status'] == 'OVER_QUERY_LIMIT')
				{
					$data['readable_location'] = $readable_location;
					$data['current_subject_id'] = $subject['id'];
					$data['meta'] = $this->config->item('find-over-query');

					$this->load->page('find/over-query', $data);
					return;
				}
				// Otherwise, geocoded response is either REQUEST_DENIED, INVALID_REQUEST, or ZERO_RESULTS; in all cases, we treat it as no location found
				else
				{
					$data['meta'] = $this->config->item('find-location-not-found');
					$data['meta']['canonical'] = $canonical_url;
					$data['readable_location'] = $readable_location;

					foreach($data['meta'] as $key => $val)
					{
						$data['meta'][$key] = str_replace('{LOCATION}', $data['readable_location'], $data['meta'][$key]);
					}
			
					$this->load->page('find/location-not-found', $data);
					return;	
				}
			}
		}

		// If no location session, or if the location query is for a different location than in session, then is direct URL access -> serverside geocode
		if (!$location || strcasecmp($readable_location, $location['readable']) !== 0)
		{
			$location = $this->_get_location_from_db($readable_location);

			if ($location['status'] == LOC_STATUS_NEEDS_SERVERSIDE_GEOCODE)
			{
				$geocoded_location = $this->geography->geocode($readable_location);
//				$geocoded_location['status'] = 'OVER_QUERY_LIMIT';

				// If OK, then server-side geocoding worked
				if ($geocoded_location['status'] == 'OK')
				{
					$location = $geocoded_location;
					$location['place_changed'] = TRUE;
					$this->session->set_userdata('search-location', $location);

					$location_for_db = array(
						'name' => $readable_location,
						'lat' => $location['lat'],
						'lon' => $location['lon'],
						'occurrences' => 1,
						'status' => ITEM_STATUS_PENDING
					);
					$this->data_model->add_item($location_for_db, 'locations');

				}
				// If OVER_QUERY_LIMIT, try to interstitial client-side geocode (only relevant if person entered URL directly AND server-side geocoding didn't work)
				elseif ($geocoded_location['status'] == 'OVER_QUERY_LIMIT')
				{
					$data = array(
						'readable_location' => $readable_location,
						'current_subject_id' => $subject['id']
					);

//					echo "Over query problem";
//					return;	

					$this->load->view('find/interstitial', $data);
					return;	
				}
				// Otherwise, geocoded response is either REQUEST_DENIED, INVALID_REQUEST, or ZERO_RESULTS; in all cases, we treat it as no location found
				else
				{
					$data['meta'] = $this->config->item('find-location-not-found');
					$data['meta']['canonical'] = $canonical_url;
					$data['readable_location'] = $readable_location;

					foreach($data['meta'] as $key => $val)
					{
						$data['meta'][$key] = str_replace('{LOCATION}', $data['readable_location'], $data['meta'][$key]);
					}
			
					$this->load->page('find/location-not-found', $data);
					return;	
				}
			}
			else
			{
				$this->session->set_userdata('search-location', $location);
			}
		}

		// At this point, $location should be set with something

		if (!$this->session->userdata('prefs'))
			$this->session->set_userdata('prefs', $this->tank_auth->get_prefs());

		$sort = $this->input->get('sort');

		if ($distance = $this->input->get('distance'))
		{
			$needs_distance_increments = FALSE;
			// Would be more condensed to do a if !$distance..., but this is clearer
		}
		elseif ($location['place_changed'])
		{
			$distance = 5;
			$needs_distance_increments = TRUE;
		}
		else
		{
			$needs_distance_increments = FALSE;
		}

		$get_prefs = array(
			'units' => $this->input->get('units'),
			'sort' => $sort,
			'distance' => $distance,
			'readable-location' => $readable_location
		);

		$this->tank_auth->set_prefs($get_prefs);

		$data = array();		
		$prefs = $this->session->userdata('prefs');
		
		$data['distance'] = $prefs['distance'];
		$data['sort'] = $prefs['sort'];
		$data['units'] = $prefs['units'];

		switch($data['sort'])
		{
			case 'price':
				if ($search_group == 'tutors')
					$data['readable_sort'] = 'Lowest Priced';
				else
					$data['readable_sort'] = 'Highest Paying';					
				break;
			case 'rating':
				if ($search_group == 'tutors')
					$data['readable_sort'] = 'Highest Rated';
				else
					$data['readable_sort'] = 'Closest';
				break;
			case 'new':
				$data['readable_sort'] = 'Newest';
				break;
			default:
				$data['readable_sort'] = 'Closest';
		}

		$data['readable_subject'] = ae_urldecode($subject_query);
		$data['readable_location'] = $readable_location;
		$data['location_query'] = $location_query;
		$data['subject_query'] = $subject_query;

		$args = array(
			'lat' => $location['lat'],
			'lon' => $location['lon'],
			'subject_id' => $subject['id'],
			'distance' => $data['distance'],
			'units' => $data['units']
		);

		$this->load->model('find_model');

/*
		This needs a little more refinement (problem with same location but new subject)

		if ($needs_distance_increments)
		{
			$args['distance'] = 0;

			do
			{
				$args['distance'] += 5;
				$num_of_results = $this->find_model->count($search_group, $args);

			} while(!$num_of_results && $args['distance'] < 30);

			$data['distance'] = $args['distance'];
		}
		else
		{
			$num_of_results = $this->find_model->count($search_group, $args);
		}
*/

		$num_of_results = $this->find_model->count('local', $search_group, $args);

		$this->paginator->items_total = $num_of_results;  
		$this->paginator->paginate();

		if ($this->paginator->num_pages > 1)
			$data['page_list'] = $this->paginator->display_pages();  
		else
			$data['page_list'] = '';

		$args = array(
			'lat' => $location['lat'],
			'lon' => $location['lon'],
			'subject_id' => $subject['id'],
			'sort' => $data['sort'],
			'distance' => $data['distance'],
			'units' => $data['units'],
			'limit_from' => $this->paginator->low,
			'limit_count' => $this->paginator->items_per_page
		);
		if ($num_of_results > 0)
		{
			$find_results = $this->find_model->find('local', $search_group, $args);
		}
		else
		{
			$find_results = NULL;
			$data['extra_make_tutor_request'] = TRUE;
		}

		//echo $num_of_results;
		
//		echo nl2br($this->db->last_query());

		if ($data['distance'] == 1)
		{
			$data['mile_unit'] = 'mile';

			if ($data['units'] == 'miles')
				$data['readable_units'] = 'mile';
			else
				$data['readable_units'] = $data['units'];
		}
		else
		{
			$data['mile_unit'] = 'miles';	
			$data['readable_units'] = $data['units'];
		}

		$data['readable_distance'] = $data['distance'].' ';

		if ($data['distance'] == 1 && $data['units'] == 'miles')
		{
			$data['readable_distance'] .= 'mile';			
		}
		else
		{
			$data['readable_distance'] .= $data['units'];	
		}

		if ($search_group == 'subjects')
		{
			if ($find_results)
			{
				$data['subjects_and_categories'] = $find_results['items'];			
				
				$data['category_columns'] = array(
					array(),
					array(),
					array()
				);

				$count = count($find_results['items']);
				$keys = array_keys($find_results['items']);

				for ($i = 0; $i < $count ; $i += 3)
				{
					for ($j = 0; $j < 3; $j++)
					{
						$key = array_shift($keys);
						if ($key)
						{
							$data['category_columns'][$j][$key] = array_shift($find_results['items']);	
						}
					}
				}

				$data['num_of_items'] = $find_results['count'];				
			}
			else
			{
				$data['num_of_items'] = 0;								
			}

			$data['meta'] = $this->config->item('find-local-subjects');
			foreach($data['meta'] as $key => $val)
			{
				$data['meta'][$key] = str_replace('{LOCATION}', $readable_location, $data['meta'][$key]);
			}
			$data['search_domain'] = 'local';

			$data['groups'] = $readable_search_groups;
			$data['group'] = $readable_search_group;

			$this->load->page('find/subjects', $data);
			return;
		}

//		var_dump($find_results);
//		return;

		if (!$find_results)
		{
			$data['num_of_items'] = 0;
			$data['meta'] = $this->config->item('find-none-found');
			foreach($data['meta'] as $key => $val)
			{
				$data['meta'][$key] = str_replace('{SUBJECT}', $data['readable_subject'], $data['meta'][$key]);
				$data['meta'][$key] = str_replace('{LOCATION}', $readable_location, $data['meta'][$key]);
				$data['meta'][$key] = str_replace('{GROUP}', ucfirst($readable_search_groups), $data['meta'][$key]);
			}
		}
		else
		{
			$data['items'] = $find_results['items'];
			$data['num_of_items'] = $num_of_results;
			$data['items_low'] = $this->paginator->low + 1;	// +1 to avoid zero-index
			$data['items_high'] = $this->paginator->high + 1;
			$data['num_of_pages'] = $this->paginator->num_pages;
			$data['item_lats'] = $find_results['lats'];
			$data['item_lons'] = $find_results['lons'];
			$data['lat'] = $location['lat'];
			$data['lon'] = $location['lon'];
		
			$data['item_markers'] = array();
			$data['item_markers']['positions_string'] = "";
			
			for ($i = 0; $i < $find_results['current_page_count']; $i++)
			{
				$lat = $data['item_lats'][$i];
				$lon = $data['item_lons'][$i];
				unset($data['item_lats'][$i], $data['item_lons'][$i]);

				// Only need to check for lat, since lat-lons would be the same for a problem to arise 
				if (in_array($lat, $data['item_lats']))
				{
					$nudge_val = mt_rand(-7000,7000) / 10000000;
//					var_dump($lat, $nudge_val);
					$lat += $nudge_val;
					$lon += $nudge_val;
				}

				$data['item_markers']['positions_string'] .= "new gm.LatLng($lat,$lon),";
			}
			
			// Cut off trailing comma (god damn IE)
			$data['item_markers']['positions_string'] = substr($data['item_markers']['positions_string'], 0, -1);

			$this->session->set_flashdata('previous_page', this_url_with_query());

			$data['meta'] = $this->config->item('find-local-tutors-requests');
			$data['meta']['canonical'] = $canonical_url;
			foreach($data['meta'] as $key => $val)
			{
				$data['meta'][$key] = str_replace('{SUBJECT}', $data['readable_subject'], $data['meta'][$key]);
				$data['meta'][$key] = str_replace('{LOCATION}', $readable_location, $data['meta'][$key]);
				$data['meta'][$key] = str_replace('{GROUP}', ucfirst($readable_search_groups), $data['meta'][$key]);
			}			
		}

		$data['groups'] = $readable_search_groups;
		$data['group'] = $readable_search_group;

		if ($data['sort'] == 'price' && $search_group == 'request')
		{
			$data['readable_sort'] = 'Highest Paying';
		}

		if ($data['num_of_items'] > 0)
			$data['type_suffix'] = 'map';
	
		$data['results'] = $this->load->view('components/find/results', $data, TRUE);

		$this->load->page('find/local', $data);
	}

	function fetch_results()
	{
		if (!($this->input->is_ajax_request()))
		{
			show_404();
		}

		$page = $this->input->post('page');
		if (!$page)
		{
			$page = 1;
		}

		$this->load->view('components/find/results', $data, TRUE);
	}

	function distance($search_group, $subject_query = "")
	{
		if (!in_array($search_group, array('tutors', 'requests', 'subjects')))
		{
			show_404();
		}

		$subject_query = str_replace('_', '-', $subject_query);

		// Get plural and singular of group (requests or tutors)
		$readable_search_groups = str_replace('-', ' ', $search_group);
		$readable_search_group = substr($search_group, 0, -1); 

		$data['groups'] = $readable_search_groups;
		$data['group'] = $readable_search_group;

		$this->session->set_userdata('search-group', $search_group);
		$this->session->set_userdata('search-domain', 'distance');

		$canonical_url = "http://tutorical.com/find/distance/$search_group";

		if ($search_group != 'subjects')
		{
			if ($subject_query)
			{
				$canonical_url .= "/$subject_query";
			}

			// Meta values are modified near the end of this function (need to get user data for them)
			$readable_subject = ae_urldecode($subject_query);
/*
			echo $subject_query;
			echo '<br><br>';
			echo $readable_subject;
			return;
*/
			$subject = $this->session->userdata('search-subject');

			if (!$subject || strcasecmp($readable_subject, $subject['name']) !== 0)	// no session == direct URL with no prior searches
			{
				$subject = $this->subjects_model->get_subject('name', $readable_subject);	// returns no id, "All Subjects" if nothing found
				$this->session->set_userdata('search-subject', $subject);
				$subject = $this->session->userdata('search-subject');

				// If subject we don't offer was requested, send to no-subject-found page. We do this check again here because subject was just changed. Also, in session we keep "All Subjects" to update the form
				if (strcasecmp($readable_subject, $subject['name']) !== 0)	
				{	
					$data['meta'] = $this->config->item('find-none-found-distance');
					$data['readable_subject'] = ae_urldecode($subject_query);

					foreach($data['meta'] as $key => $val)
					{
						$data['meta'][$key] = str_replace('{SUBJECT}', $data['readable_subject'], $data['meta'][$key]);
						if ($search_group == 'tutors')
						{
							$group_text = 'tutors teaching';
						}
						else
						{
							$group_text = 'requests for';
						}
						$data['meta'][$key] = str_replace('{GROUP_TEXT}', $group_text, $data['meta'][$key]);
					}
					
					$data['extra_make_tutor_request'] = TRUE;
					$data['groups'] = $search_group;
					$this->load->page('find/none-distance', $data);
					return;	
				}
			}
		}
		else
		{
			$subject = $this->subjects_model->get_subject('name', '');
		}

		if (!$this->session->userdata('prefs'))
			$this->session->set_userdata('prefs', $this->tank_auth->get_prefs());

		$sort = $this->input->get('sort');

		$get_prefs = array(
			'sort' => $sort
		);

		$this->tank_auth->set_prefs($get_prefs);

		$data = array();		
		$prefs = $this->session->userdata('prefs');
		
		$data['sort'] = $prefs['sort'];

		if ($data['sort'] == 'distance')
			$data['sort'] = 'rating';

		switch($data['sort'])
		{
			case 'price':
				if ($search_group == 'tutors')
					$data['readable_sort'] = 'Lowest Priced';
				else
					$data['readable_sort'] = 'Highest Paying';
				break;
			case 'rating':
				if ($search_group == 'tutors')
					$data['readable_sort'] = 'Highest Rated';
				else
					$data['readable_sort'] = 'Newest';
				break;
			default:
				$data['readable_sort'] = 'Newest';
		}

		$data['readable_subject'] = ae_urldecode($subject_query);
		$data['subject_query'] = $subject_query;

		$args = array(
			'subject_id' => $subject['id'],
		);

		$this->load->model('find_model');

		$num_of_results = $this->find_model->count('distance', $search_group, $args);
		$this->paginator->items_total = $num_of_results;  
		$this->paginator->paginate();

		if ($this->paginator->num_pages > 1)
			$data['page_list'] = $this->paginator->display_pages();  
		else
			$data['page_list'] = '';

		$args = array(
			'subject_id' => $subject['id'],
			'sort' => $data['sort'],
			'limit_from' => $this->paginator->low,
			'limit_count' => $this->paginator->items_per_page
		);

		if ($num_of_results > 0)
		{
			$find_results = $this->find_model->find('distance', $search_group, $args);
		}
		else
		{
			$find_results = NULL;
			$data['extra_make_tutor_request'] = TRUE;
		}

		if ($search_group == 'subjects')
		{
			if ($find_results)
			{
				$data['subjects_and_categories'] = $find_results['items'];			
				$data['category_columns'] = array(
					array(),
					array(),
					array()
				);

				$count = count($find_results['items']);
				$keys = array_keys($find_results['items']);

				for ($i = 0; $i < $count ; $i += 3)
				{
					for ($j = 0; $j < 3; $j++)
					{
						$key = array_shift($keys);
						if ($key)
						{
							$data['category_columns'][$j][$key] = array_shift($find_results['items']);	
						}
					}
				}

				$data['num_of_items'] = $find_results['count'];				
			}
			else
			{
				$data['num_of_items'] = 0;								
			}

			$data['meta'] = $this->config->item('find-distance-subjects');
			$data['search_domain'] = 'distance';

			$this->load->page('find/subjects', $data);
			return;
		}

		if (!$find_results)
		{
			$data['num_of_items'] = 0;
			$data['meta'] = $this->config->item('find-none-found-distance');
			foreach($data['meta'] as $key => $val)
			{
				$data['meta'][$key] = str_replace('{SUBJECT}', $data['readable_subject'], $data['meta'][$key]);

				if ($search_group == 'tutors')
				{
					$group_text = 'tutors teaching';
				}
				else
				{
					$group_text = 'requests for';
				}
	
				$data['meta'][$key] = str_replace('{GROUP_TEXT}', $group_text, $data['meta'][$key]);
			}

			$data['groups'] = $search_group;

			$this->load->page('find/none-distance', $data);
			return;
		}
		else
		{
			$data['items'] = $find_results['items'];
			$data['num_of_items'] = $num_of_results;
			$data['items_low'] = $this->paginator->low + 1;	// +1 to avoid zero-index
			$data['items_high'] = $this->paginator->high + 1;
			$data['num_of_pages'] = $this->paginator->num_pages;
					
			for ($i = 0; $i < $find_results['current_page_count']; $i++)
			{
				$data['items'][$i]['currency_sign'] = get_currency_sign($data['items'][$i]['currency']);
			}
			
			$this->session->set_flashdata('previous_page', this_url_with_query());

			$data['meta'] = $this->config->item('find-distance-tutors-requests');
			$data['meta']['canonical'] = $canonical_url;
			foreach($data['meta'] as $key => $val)
			{
				$data['meta'][$key] = str_replace('{SUBJECT}', $data['readable_subject'], $data['meta'][$key]);
				$data['meta'][$key] = str_replace('{GROUP}', ucfirst($search_group), $data['meta'][$key]);

				if ($search_group == 'tutors')
				{
					$group_text = 'tutors teaching online';
				}
				else
				{
					$group_text = 'distance tutor requests';
				}

				$data['meta'][$key] = str_replace('{GROUP_TEXT}', $group_text, $data['meta'][$key]);

			}			
		}

		// Fix this occuring a second time
		$data['groups'] = $search_group;
		$data['group'] = substr($data['groups'], 0, -1);

		$data['type_suffix'] = 'distance';
		$this->load->page('find/distance', $data);
	}

	function location_exists_in_db()
	{
		if (!$this->input->is_ajax_request())
		{
			show_404();
		}

		$this->form_validation->set_rules('location', '', 'trim|strip_tags|required|xss_clean');
		if (!$this->form_validation->run())	
		{
			return;
		}

		$readable_location = $this->input->post('location');
		if ($this->data_model->item_exists('name', $readable_location, 'locations'))
		{
			echo TRUE;
		}
	}

	function _get_location_from_db($readable_location)
	{
		$db_location = $this->data_model->get_item('name', $readable_location, 'locations');

		if (empty($db_location))	// session changed and location not in DB
		{
			$location = array(
				'lat' => NULL,
				'lon' => NULL,
				'readable' => NULL,
				'status' => LOC_STATUS_NEEDS_SERVERSIDE_GEOCODE,
				'place_changed' => TRUE
			);
		}
		else
		{
			$location = array(
				'lat' => $db_location['lat'],
				'lon' => $db_location['lon'],
				'readable' => $readable_location,
				'status' => LOC_STATUS_FOUND,
				'place_changed' => TRUE
			);
			$this->data_model->increment_occurrences('name', $readable_location, 'locations');
		}

		return $location;
	}
}

/* End of file find.php */
/* Location: ./application/controllers/find.php */