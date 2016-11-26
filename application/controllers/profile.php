<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller
{
	private $user_id;

	function __construct()
	{
		parent::__construct();

		$this->load->model('profile_model');
		$this->load->library(array('security', 'form_validation'));

		$this->user_id = $this->session->userdata('user_id');

		if (!($this->input->is_ajax_request()
			|| !$this->user_id))
			redirect('');
	}

	function update()
	{	
		if ($key = $this->input->post('update-key'))
		{
			$update_method = "update_$key";

//			var_dump($update_method);

			$response = $this->profile_model->$update_method();
			echo json_encode($response);
		}
		return;
	}

	function update_order()
	{	
//		echo json_encode($this->form_validation->response());
		$this->form_validation->set_rules('type', 'Type', 'trim|strip_tags|required');
		$this->form_validation->set_rules('items', 'Items', 'trim|strip_tags|required');	

		if (!$this->form_validation->run())	
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}

		$type = $this->input->post('type');

		parse_str($this->input->post('items'), $items);

		$response = $this->profile_model->update_order($type, $items);

		echo json_encode($response);

	}

	function delete_item()
	{	
		if (($item_id = $this->input->post('item_id'))
			&& ($type = $this->input->post('type')))
		{
			switch ($type)
			{
				case 'education':
					$table = 'user_educations';
					break;
				case 'experience':
					$table = 'user_experiences';
					break;
				case 'volunteering':
					$table = 'user_volunteerings';
					break;
				case 'link':
					$table = 'user_links';
					break;
				case 'er':
					$table = 'user_external_reviews';
					break;
			}

			if ($this->profile_model->delete_item($table, $item_id))
			{
				echo json_encode($this->form_validation->response());
				return;
			}
		}
		echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));
		return;
	}


	/* AJAX functions */

	function toggle_profile()
	{
		if ($this->profile_model->profile_made()
			&& $this->tutor_model->toggle_profile())
		{
			echo 'good';
		}
		else
		{
			echo 'bad';
		}
	}
/*
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
*/
}

/* End of file account.php */
/* Location: ./application/controllers/account.php */