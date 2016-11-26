<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Error extends CI_Controller
{
	function index()
	{
	    $data['meta'] = $this->config->item('error');
		$this->load->page('error/general', $data);
	}

	function error_404()
	{
	    $data['meta'] = $this->config->item('error-404');
		$this->load->page('error/404', $data);
	}

}

/* End of file error.php */
/* Location: ./application/controllers/error.php */