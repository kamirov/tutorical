<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{

		/*
		echo $_SERVER['REMOTE_ADDR'].'<br><br>';
		
		echo var_export(unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR'])));
		return;
		*/
		
		$data = array(
			'student_name' => 'Jackson',
			'student_profile_path' => base_url('students/andrei-k'),
			'request_subjects' => 'Math, Science',
			'request_id' => 12
		);
//		$this->load->view('email/invited-to-request', $data);
//		return;

//		$val = "Montreal, Québec";
//		echo iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $val);

		$data = array();
		$data['meta'] = $this->config->item('home');
		$data['meta']['canonical'] = 'http://tutorical.com';

		if ($this->session->flashdata('account_deleted'))
		{
			$data['account_deleted'] = TRUE;
		}
		// Only show logged out if account HAS NOT been deleted
		elseif ($this->session->flashdata('logged_out'))
		{
			$data['logged_out'] = TRUE;
		}

		if ($this->session->flashdata('need_login'))
		{
			$data['need_login'] = TRUE;
		}
		if ($this->session->flashdata('already_registered'))
		{
			$data['already_registered'] = TRUE;
		}
		if ($this->session->flashdata('password_reset'))
		{
			$data['password_reset'] = TRUE;
		}
		
		if ($this->session->flashdata('previous_page'))
		{
			$data['previous_page'] = base_url($this->session->flashdata('previous_page'));
		}
		else
		{
			$data['previous_page'] = '';
		}

//			$data['logged_out'] = TRUE;

		$this->load->page('home', $data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */