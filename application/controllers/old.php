<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Old extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->view('old');
	}
}

/* End of file old.php */
/* Location: ./application/controllers/old.php */