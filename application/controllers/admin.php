<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model(array('admin_model', 'data_model'));

		if (!$this->session->userdata('init') || !$this->input->is_ajax_request())
		{
			show_404();
			return FALSE;
		}

	}
       
	function share()
	{
		$id = $this->input->post('id');
		$type = $this->input->post('type');
		$action = $this->input->post('action');

		if ($action == FALSE)
		{
			$processed = $this->admin_model->set_shared_to_true($type, $id);
		}
		else
		{
			if ($type == 'tutor')
			{
				$data = array(
					'id' => $id,
					'username' => $this->input->post('username'),
					'name' => $this->input->post('name'),
					'city' => $this->input->post('city'),
					'country' => $this->input->post('country')
				);
			}
			else
			{
				$data = array(
					'id' => $id,
					'subject' => $this->input->post('subject'),
					'city' => $this->input->post('city'),
					'country' => $this->input->post('country')
				);
			}

			$processed = $this->admin_model->share($type, $data);			
		}

		if ($processed)
			$response = $this->form_validation->response();
		else
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);

		echo json_encode($response);
	}


	function share_tutor()
	{
		if ($this->admin_model->reject_student($st_id))
			$response = $this->form_validation->response();
		else
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);

		echo json_encode($response);

	}

	function clear_tutor_contact()
	{
		$st_id = $this->input->post('st-id');

		if ($this->admin_model->clear_tutor_contact($st_id))
			$response = $this->form_validation->response();
		else
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);

		echo json_encode($response);
	}

	function clear_contact()
	{
		$row_id = $this->input->post('row-id');
		
		if ($this->admin_model->clear_contact($row_id))
		{
			echo json_encode($this->form_validation->response());
		}
		else
		{
			echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));			
		}
	}

	function delete_report()
	{
		$row_id = $this->input->post('row-id');
		
		if ($this->admin_model->delete_report($row_id))
		{
			echo json_encode($this->form_validation->response());
		}
		else
		{
			echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));			
		}
	}

	function approve_data_item()
	{
		$item_id = $this->input->post('item-id');
		$table = $this->input->post('table');
		$value = $this->input->post('value');

		if ($this->data_model->approve_data_item($item_id, $table, $value))
		{
			echo json_encode($this->form_validation->response());
		}
		else
		{
			echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));			
		}
	}

	function delete_data_item()
	{
		$item_id = $this->input->post('item-id');
		$table = $this->input->post('table');

		if ($this->data_model->delete_data_item($item_id, $table))
		{
			echo json_encode($this->form_validation->response());
		}
		else
		{
			echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));			
		}
	}

	function hide_data_item()
	{
		$item_id = $this->input->post('item-id');
		$table = $this->input->post('table');

		if ($this->data_model->hide_data_item($item_id, $table))
		{
			echo json_encode($this->form_validation->response());
		}
		else
		{
			echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));			
		}
	}

	function approve_subject()
	{
		$subject_id = $this->input->post('subject-id');
		$value = $this->input->post('value');
		$category = $this->input->post('category');

		if ($this->subjects_model->approve_subject($subject_id, $value, $category))
		{
			echo json_encode($this->form_validation->response());
		}
		else
		{
			echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));			
		}
	}

	function reject_subject()
	{
		$subject_id = $this->input->post('subject-id');

		if ($this->subjects_model->reject_subject($subject_id))
		{
			echo json_encode($this->form_validation->response());
		}
		else
		{
			echo json_encode($this->form_validation->response(STATUS_UNKNOWN_ERROR));			
		}
	}

	function make_imported_tutor_account()
	{
		$import_email = $this->input->post('import-email');
		$import_url = $this->input->post('import-url');

		if ($import_email && $import_url) 
		{	
			$account_data = array(
				'role' =>  ROLE_TUTOR,
				'email' => $import_email,
				'new-password' => 'password',
				'first-name' => 'Tutor',
				'last-name' => 'Name'
			);

			$import_data = array(
				'import-sections' => array('display_name', 'photo', 'subjects', 'price', 'gender', 'availability', 'location', 'travel_notes', 'about', 'reviews'),
				'import-for-about' => 'experience',
				'import-url' => $import_url,
				'import-type' => 'universitytutor',
			);

			$this->session->set_userdata('admin-import-account-data', $account_data);
			$this->session->set_userdata('admin-import-profile-data', $import_data);
			
			echo json_encode($this->tank_auth->attempt_signup(FALSE));
		}
	}

	function url_check()
	{
		$this->form_validation->set_rules('import-url', 'URL', 'trim|strip_tags|required|valid_url|xss_clean|is_unique[data_universitytutor_urls.name]');

		if (!$this->form_validation->run())
		{
			echo json_encode($this->form_validation->invalid_response());
			return;
		}

		$import_url = $this->input->post('import-url');

		if ($this->admin_model->add_ut_url($import_url))
		{
			$response = $this->form_validation->response();
		}
		else
		{
			$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
		}
		echo json_encode($response);
	}

}


/* End of file admin.php */
/* Location: ./application/controllers/admin.php */