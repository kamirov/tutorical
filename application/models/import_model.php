<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Import_model extends CI_Model {

	function import($import_data)
	{
		$admin_import_data = $this->session->userdata('admin-import-profile-data');
		
		if (isset($import_data['photo']))
			$responses['import_responses']['photo'] = $this->profile_model->update_photo($import_data['photo']);

		if (isset($import_data['display_name']))
			$responses['import_responses']['display_name'] = $this->profile_model->update_display_name($import_data['display_name']);

		if (isset($import_data['gender']))
			$responses['import_responses']['gender'] = $this->profile_model->update_gender($import_data['gender']);

		if (isset($import_data['price_and_currency']))
			$responses['import_responses']['price_and_currency'] = $this->profile_model->update_price($import_data['price_and_currency']);

		if (isset($import_data['location']))
			$responses['import_responses']['location'] = $this->profile_model->update_location($import_data['location']);

		if (isset($import_data['about']))
		{
			$responses['import_responses']['about'] = $this->profile_model->update_about($import_data['about']);

			if ($admin_import_data)
			{
				$snippet = explode('.', $import_data['about']);
				$snippet = explode("\n", $snippet[0]);
				$snippet = $snippet[0];

				if (strlen($snippet) > SNIPPET_MAX_LENGTH)
				{
					$snippet = substr($snippet, 0, SNIPPET_MAX_LENGTH - 3).'...';
				}
//				var_dump($snippet);

				$responses['import_responses']['snippet'] = $this->profile_model->update_snippet($snippet);
//				var_dump($responses['import_responses']['snippet']);
			}
		}

		if (isset($import_data['travel_notes']))
			$responses['import_responses']['travel_notes'] = $this->profile_model->update_travel_notes($import_data['travel_notes']);

		if (isset($import_data['availability']))
			$responses['import_responses']['availability'] = $this->profile_model->update_availability($import_data['availability']);

		if (isset($import_data['can_meet']))
			$responses['import_responses']['can_meet'] = $this->profile_model->update_can_meet($import_data['can_meet']);

		if (isset($import_data['subjects']))
			$responses['import_responses']['subjects'] = $this->profile_model->update_subjects($import_data['subjects']);

		// These items come in groups

		if (isset($import_data['education']))
			$responses['import_responses']['education'] = $this->profile_model->update_multiple('education', $import_data['education']);

		if (isset($import_data['reviews']))
			$responses['import_responses']['reviews'] = $this->profile_model->update_multiple('external_review', $import_data['reviews']);

		$overall_response = $this->form_validation->response();

		foreach($responses['import_responses'] as $import_response)
		{
			if (!$import_response['success'])
			{
				$overall_response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
				break;
			}
		}

		// Only update avatar url if we're not admin importing
		if (!$admin_import_data)
		{
			// If all went well and we don't have a "Profile made!" notice queued, then show the Import Successful notice 
			if ($overall_response['success'] && !$this->reaction_notice->is_set)
			{
				$this->reaction_notice->set_quick("<b>Import successful!</b>");

				// Update session to handle caching problem
				$avatar_url = $this->session->userdata('avatar_url').'?'.time();
				$this->session->set_userdata('avatar_url', $avatar_url);
			}			
		}
		else
		{

			if (isset($responses['import_responses'], $responses['import_responses']['display_name']))
			{
				$overall_response['data'] = array(
					'username' => $responses['import_responses']['display_name']['data']['username']
				);				
			}
		}

//		var_export($responses);

		return $overall_response;
	}
}