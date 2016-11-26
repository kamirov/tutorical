<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		if (!$this->input->is_ajax_request())
		{
			show_404();
		}

		$this->load->helper(array('form'));
		$this->load->library(array('security', 'form_validation'));
	}

	function index()
	{
		$this->form_validation->set_rules('type', '', 'trim|strip_tags|required|xss_clean');
		$this->form_validation->set_rules('id', '', 'trim|strip_tags|required|xss_clean');		
		$this->form_validation->set_rules('message', 'Reason for Report', 'trim|strip_tags|required|xss_clean');

		if (!$this->form_validation->run())	
		{
			$response = $this->form_validation->invalid_response();
			echo json_encode($response);
			return;
		}

		$type = $this->input->post('type');
		$id_or_username = $this->input->post('id');
		$message = $this->input->post('message');

		if ($type == REPORT_TYPE_TUTOR)
		{
			// Check if user exists with that name. If not, then return error
			if ($this->users->is_username_available($id_or_username))
			{
				$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
				echo json_encode($response);
				return;
			}
		}
		elseif ($type == REPORT_TYPE_REVIEW 
				|| $type == REPORT_TYPE_EXTERNAL_REVIEW
				|| $type == REPORT_TYPE_REQUEST)
		{
			// Non-integer review IDs can't exist. If not numeric, then foul play is afoot.
			if (!is_numeric($id_or_username))
			{
				$response = $this->form_validation->response(STATUS_UNKNOWN_ERROR);
				echo json_encode($response);
				return;
			}
		}

		$this->load->model('report_model');		

		if ($this->report_model->add_report($type, $id_or_username, $message))
		{
			$response = $this->form_validation->response();
			echo json_encode($response);
			return;
		}
	}

}

/* End of file report.php */
/* Location: ./application/controllers/report.php */