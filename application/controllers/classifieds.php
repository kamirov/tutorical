<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Classifieds extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function template($type, $username)
	{

		$username = str_replace('_', '-', $username);

		$data = array();

		$data['tutor'] = $this->tutor_model->get_tutor($username, 'username', array('usage' => 'classifieds'));

		if (!$data['tutor'])
		{
		      show_404();
		}
		
		$data['currency_sign'] = get_currency_sign($data['tutor']['currency']);

		$this->load->view("classifieds-templates/$type", $data);
	}

}

/* End of file classifieds.php */
/* Location: ./application/controllers/classifieds.php */