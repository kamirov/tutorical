<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chosen extends CI_Controller
{
	// Index represents the dashboard
	function index()
	{
//		$this->load->view('chosen-2');
		$this->load->page('chosen');

	}
}

/* End of file account.php */
/* Location: ./application/controllers/account.php */