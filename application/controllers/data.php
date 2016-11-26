<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Data extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('data_model');
	}

	function index($table)
	{
		$term = $this->input->get('term');
		$terms = $this->data_model->get_data_like($term, $table);
		echo json_encode($terms);
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

/* End of file data.php */
/* Location: ./application/controllers/data.php */