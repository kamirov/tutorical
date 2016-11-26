<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('signup_model');
		$this->tank_auth->bounce_if_unlogged();
	
		$this->load->helper(array('form'));
		$this->load->library(array('security', 'form_validation'));
	}

	function index()
	{
		// This is rerouted to /auth/register in routes.php
	}

	/**
	 * General
	 *
	 * @return void
	 */
	function step_1()
	{
		$this->_bounce_if_profile_made();
		$latest_step = $this->_bounce_if_not_seen_step(1);

		$data['meta'] = $this->config->item('signup-step-1');

		$this->signup_model->set_user_redirect('signup/step-1');
		$this->session->set_userdata('current_step', 'step_1');

		if ($this->signup_model->is_valid('general'))
		{
			$post = $this->input->post();
			$post['in_signup'] = TRUE;
			$post['username'] = $this->tank_auth->make_username($post['display-name']);

			if ($this->signup_model->update_step_1($post))
			{
				$this->session->set_userdata('username', $post['username']);
				$this->session->set_userdata('display_name', $post['display-name']);

				redirect('signup/step-2');
			}
		}

		$process_data = array(
			'active' => 'general',
			'latest_step' => $latest_step
		);

		$data['next_step_text'] = '» Save and Proceed';
		$data['signup_process'] = $this->load->view('components/signup/process', $process_data, TRUE);
		$data['signup_notices'] = $this->load->view('components/signup/notices', NULL, TRUE);
		$data['view_type'] = 'signup';
		$data['field_values'] = $this->signup_model->get_field_values('general');

		$this->load->page('signup/step-1', $data);
	}

	/**
	 * Subjects
	 *
	 * @return void
	 */
	function step_2()
	{
		$this->_bounce_if_profile_made();
		$latest_step = $this->_bounce_if_not_seen_step(2);
		$this->signup_model->set_user_redirect('signup/step-2');
		$this->session->set_userdata('current_step', 'step_2');

		$data['meta'] = $this->config->item('signup-step-2');

		if ($this->signup_model->is_valid('subjects'))
		{
			$post = $this->input->post();
			$post['in_signup'] = TRUE;

			if ($this->signup_model->update_step_2($post))
			{
				redirect('signup/step-3');
			}

		}

		$this->load->model('subjects_model');
		$data['all_subjects'] = $this->subjects_model->get_all_subjects(FALSE);
		
       		$process_data = array(
			'active' => 'subjects',
			'latest_step' => $latest_step
		);
		$data['next_step_text'] = '» Save and Proceed';
		$data['signup_process'] = $this->load->view('components/signup/process', $process_data, TRUE);
		$data['signup_notices'] = $this->load->view('components/signup/notices', NULL, TRUE);

		$data['view_type'] = 'signup';

		$data['field_values'] = $this->signup_model->get_field_values('subjects');

//		var_dump($data['field_values']);
		$this->load->page('signup/step-2', $data);
	}

	/**
	 * Availability
	 *
	 * @return void
	 */
	function step_3()
	{
		$this->_bounce_if_profile_made();
		$latest_step = $this->_bounce_if_not_seen_step(3);	

		$data['meta'] = $this->config->item('signup-step-3');

		$this->signup_model->set_user_redirect('signup/step-3');
		$this->session->set_userdata('current_step', 'step_3');

		if ($this->signup_model->is_valid('availability'))
		{
			$post = $this->input->post();
			$post['in_signup'] = TRUE;

			if ($this->signup_model->update_step_3($post))
			{
				// check whether user wants to proceed to step 4 (check for regular submit button click)
				if ($this->input->post('submit-availability'))
				{
					redirect('signup/step-4');
				}
				else
				{
					$redirect = 'account';
					if ($this->signup_model->set_user_redirect($redirect)
						&& $this->signup_model->complete_profile())
					{
						$this->session->set_flashdata('from_signup_profile_made_without_optionals', TRUE);
						redirect($redirect);
					}
					else
					{
						echo 'Something terrible happened! Sorry! Please try refreshing the page.';
					}
				}
				redirect('signup/step-4');
			}
		}

		$process_data = array(
			'active' => 'availability',
			'latest_step' => $latest_step
		);
		$data['next_step_text'] = '» Save and Proceed';
		$data['signup_process'] = $this->load->view('components/signup/process', $process_data, TRUE);
		$data['signup_notices'] = $this->load->view('components/signup/notices', NULL, TRUE);

		$data['view_type'] = 'signup';

		$data['field_values'] = $this->signup_model->get_field_values('availability');

		$data['availability'] = $this->load->view('components/availability', $data['field_values'], TRUE);

		$this->load->page('signup/step-3', $data);			
	}

	/**
	 * About
	 *
	 * @return void
	 */
	function step_4()
	{
		$this->_bounce_if_profile_made();
		$latest_step = $this->_bounce_if_not_seen_step(4);

		$data['meta'] = $this->config->item('signup-step-4');

		$this->signup_model->set_user_redirect('signup/step-4');
		$this->session->set_userdata('current_step', 'step_4');

		if ($this->signup_model->is_valid('about'))
		{
			$post = $this->input->post();
			$post['in_signup'] = TRUE;
			$redirect = 'account';

			if ($this->signup_model->update_step_4($post)
				&& $this->signup_model->set_user_redirect($redirect)
				&& $this->signup_model->complete_profile())
			{
				$this->session->set_flashdata('from_signup_profile_made', TRUE);
				redirect($redirect);
			}
			else
			{
				echo "error!";
			}
		}

		$process_data = array(
			'active' => 'about',
			'latest_step' => $latest_step
		);
		$data['next_step_text'] = '» Save and Proceed';
		$data['signup_process'] = $this->load->view('components/signup/process', $process_data, TRUE);
		$data['signup_notices'] = $this->load->view('components/signup/notices', NULL, TRUE);

		$data['view_type'] = 'signup';

		$data['field_values'] = $this->signup_model->get_field_values('about');

		$this->load->page('signup/step-4', $data);			
	}

	function _bounce_if_profile_made()
	{
		if ($this->signup_model->is_signup_complete())
		{
			$this->session->set_flashdata('profile_already_made', TRUE);
			redirect('account');
		}
	}
}

/* End of file signup.php */
/* Location: ./application/controllers/signup.php */ 