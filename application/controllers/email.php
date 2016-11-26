<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Email extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		if (!$this->session->userdata('init'))
		{
			show_404();
			return FALSE;
		}

	}


	function index($template, $type = 'html')
	{
		
		$data = array(
			'tutor_name' => 'Jackson',
			'tutor_profile_path' => base_url('students/andrei-k'),
			'tutor_email' => 'jack@son.com',
			'price' => 20,
			'currency_sign' => '$',
			'currency' => 'CAD',
			'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam ut tellus eget sem adipiscing scelerisque. Phasellus egestas orci quis tortor ullamcorper, non semper elit varius. Morbi non turpis eu diam sollicitudin ullamcorper ornare quis nibh.

Phasellus scelerisque placerat urna, id adipiscing est molestie ac. Donec vitae lobortis nisi, sed porta urna. Fusce ac felis vitae justo viverra suscipit. Praesent aliquam nulla at felis tempor pretium. Praesent auctor ante nec volutpat semper. Nulla varius rutrum ullamcorper. Suspendisse sit amet enim libero. Maecenas lacinia elementum odio. Suspendisse ac est sollicitudin, mattis lectus ac, pharetra nisi. Aenean interdum purus felis, ac pellentesque lorem porta at. Nulla at ligula non sem ullamcorper sodales a at quam.

Vestibulum sed ante nisi. Aliquam porta',
			'subject' => 'Engineering Science',
			'student_response' => 'Too costly',
			'request_id' => 12
		);
		$data['message'] = 'Tiny';

		$message = $this->make_message($template, $data, $type);
		echo $message;
	}

	function make_message($template, $data, $type = 'html')
	{
	    $data = (array) $data;
	    $data['heading_style'] = $this->load->view("components/email/heading-style", NULL, TRUE);
	    $data['header'] = $this->load->view("components/email/header-$type", NULL, TRUE);
	    $data['footer'] = $this->load->view("components/email/footer-$type", NULL, TRUE);

	    if ($type == 'html')
		    $message = $this->load->view("email/$template-$type", $data, TRUE);
		else
		    $message = nl2br($this->load->view("email/$template-$type", $data, TRUE));
	    
	    return $message;
	}


	function _remap($method)
	{
	  $param_offset = 2;

	  // Default to index
	  if ( ! method_exists($this, $method))
	  {
	    // We need one more param
	    $param_offset = 1;
	    $method = 'index';
	  }

	  // Since all we get is $method, load up everything else in the URI
	  $params = array_slice($this->uri->rsegment_array(), $param_offset);

	  // Call the determined method with all params
	  call_user_func_array(array($this, $method), $params);
	} 
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */