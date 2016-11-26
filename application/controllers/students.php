<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Students extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library(array('security', 'form_validation'));
	}

	function index() {
            redirect('');
	}

	function profile($username) {

            $username = str_replace('_', '-', $username);

            $data = array();
		$data['user'] = $this->student_model->get_student($username, 'username');
            
            if (!$data['user'])
            {
                  show_404();
            }

            $created = new DateTime($data['user']['created']);
            $data['user']['joined'] = $created->format('F d, Y');
            $data['user']['num_of_reviews'] = count($data['user']['reviews']);

            // Have to do meta at the end as we need the student values
            $data['meta'] = $this->config->item('students-profile');
            $data['meta']['title'] = str_replace('{NAME}', $data['user']['display_name'], $data['meta']['title']);

		$this->load->page('students/profile', $data);
	}
}

/* End of file students.php */
/* Location: ./application/controllers/students.php */