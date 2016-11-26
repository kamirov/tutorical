<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tutors extends CI_Controller {

	function __construct() 
      {
		parent::__construct();
		$this->load->helper(array('form'));
		$this->load->library(array('security', 'form_validation'));
	}

	function index() 
      {
            redirect('');
	}

	function profile($username) 
      {
            $username = str_replace('_', '-', $username);

            $data = array();
		$data['tutor'] = $this->tutor_model->get_tutor($username, 'username');

            if (!$data['tutor'])
            {
                  show_404();
            }
            elseif ($data['tutor']['profile_made'] == FALSE)
            {
                  if ($this->session->userdata('user_id') == $data['tutor']['id'])
                  {
                        $this->load->page('tutors/finish-making-profile', $data);
                        return;
                  }
                  else
                  {
                        show_404();
                  }
            }
            elseif ($data['tutor']['is_active'] == FALSE)
            {
                  if ($this->session->userdata('user_id') != $data['tutor']['id'])
                  {
                        $this->load->page('tutors/hidden-profile', $data);                        
                        return;
                  }
            }
            $data['currency_sign'] = get_currency_sign($data['tutor']['currency']);
            $num_of_reviews = count($data['tutor']['reviews']);

            if ($this->session->flashdata('readable_subject'))
            {
                  $data['readable_subject'] = $this->session->flashdata('readable_subject');
                  $this->session->keep_flashdata('readable_subject');
            }
            else
            {
                  $data['readable_subject'] = '';
            }

            if ($this->session->flashdata('previous_page')) 
            {
                  $data['previous_page'] = $this->session->flashdata('previous_page');
            }

            if ($this->session->flashdata('readable_loc')) 
            {
                  $data['readable_loc'] = $this->session->flashdata('readable_loc');
                  $this->session->keep_flashdata('readable_loc');
            }

            $userdata = $this->session->userdata('userdata');
            
            if ($userdata)
            {
                  $favourited_tutor_ids = $userdata['favourited_tutor_ids'];

                  if (in_array($data['tutor']['id'], $favourited_tutor_ids))
                  {
                        $data['favourited'] = TRUE;
                  }
                  else
                  {
                        $data['favourited'] = FALSE;                  
                  }                  
            }
            else
            {
                  $data['favourited'] = FALSE;                  
            }

            // Have to do meta at the end as we need the tutor values

            if ($main_subject = $data['tutor']['main_subject']['name'])
            {
                  $data['meta'] = $this->config->item('tutors-profile-with-main');
                  $data['meta']['title'] = str_replace('{SUBJECT}', $main_subject, $data['meta']['title']);
            }
            else
            {
                  $data['meta'] = $this->config->item('tutors-profile');                  
            }
      
            $data['meta']['title'] = str_replace('{NAME}', $data['tutor']['display_name'], $data['meta']['title']);
            $data['meta']['title'] = str_replace('{LOCATION}', $data['tutor']['city'].', '.$data['tutor']['country'], $data['meta']['title']);

            $data['meta']['description'] = $data['tutor']['snippet'].' | '.$data['tutor']['about'].' | Tutorical - Find your perfect local tutor';

		$this->load->page('tutors/profile', $data);
	}
}

/* End of file tutors.php */
/* Location: ./application/controllers/tutors.php */