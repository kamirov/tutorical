<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class AE_Loader extends CI_Loader 
{
    private $ci;
    private $subjects;

    function __construct()
    {
        parent::__construct();
        $this->ci =& get_instance();
    }

    public function page($page_name, $vars = array(), $type = GENERAL_PAGE, $return = FALSE)
    {
        $vars['page'] = $this->ci->router->fetch_class();
        $vars['type_classes'] = ' main-type-'.$vars['page'].' ';

        if (isset($vars['type_suffix']))
        {
            $vars['type_classes'] .= ' sub-type-'.$vars['type_suffix'].' ';
        }

        $vars['is_modal'] = TRUE;

        $this->subjects = $this->ci->subjects_model->get_all_subjects();
        $vars['all_subjects'] = combine_subarrays($this->subjects, 'name');
        $vars['currencies_for_selects'] = $this->ci->config->item('currencies_for_selects');

        if ($this->ci->tank_auth->is_logged_in())
        {
            $vars['logged_in'] = TRUE;
            $vars['user_id'] = $this->ci->tank_auth->get_user_id();

            // If logged in with 2 sessions, account might be deleted in session 1, but things continue in session 2. To avoid this, we must always check if user is logged in and account doesn't exist. If so, logout in this session
            if (!$this->ci->tank_auth->user_exists($vars['user_id']))
            {
                redirect('logout');
                return;
            }

            $vars['notices_count'] = $this->ci->profile_notices_model->get_notices_count($vars['user_id']);
//            $vars['notices_count'] = 90;
            $signup_tutor = '';
            $signup_student = '';
            $login = '';
            $vars['display_name'] = $this->ci->session->userdata('display_name');
            
            $vars['role'] = $this->ci->session->userdata('role');
            $vars['profile_made'] = $this->ci->session->userdata('account_profile_made');
            $vars['has_tutors'] = $this->ci->student_model->has_tutors($vars['user_id']);
            $vars['has_students'] = ($vars['role'] == ROLE_STUDENT ? FALSE : $this->ci->tutor_model->has_students($vars['user_id']));

            if (!$this->ci->tank_auth->is_activated())
            {
                $vars['needs_activation'] = TRUE;
                $vars['email_domain'] = end(explode('@', $this->ci->session->userdata('email')));
            }
        }
        else
        {
            $vars['logged_in'] = FALSE;
            $vars['notices_count'] = 0;
            $signup_tutor = $this->view('signup/tutor', $vars, TRUE);
            $signup_student = $this->view('signup/student', $vars, TRUE);
            $login = $this->view('login', $vars, TRUE);
        }
        $contact = $this->view('contact', $vars, TRUE);
        $request = $this->view('requests/make', $vars, TRUE);

        $recovery = $this->view('recovery', $vars, TRUE);
        $vars['is_modal'] = FALSE;

        $vars['reveal'] = $this->input->get('reveal');

        if ($vars['reveal'] == 'login' && $vars['logged_in'])
        {
            redirect('login');
        }

        $vars['reaction_notice'] = $this->reaction_notice->get();

        $header_url = 'components/header-'.ENV;
        $footer_url = 'components/footer-'.ENV;
        $vars['search_form'] = $this->get_search_form($vars);
        $content = $this->view($header_url, $vars, TRUE);
        $content .= $this->view($page_name, $vars, TRUE);
        $content .= $signup_tutor;
        $content .= $signup_student;
        $content .= $login;
        $content .= $contact;
        $content .= $recovery;
        $content .= $request;
        $content .= $this->view($footer_url, $vars, TRUE);

        if ($return)
        {
            return $content;
        }
        else
        {
            
            echo $content;
 //           var_dump($this->ci->session->all_userdata());
        }
    }

    public function get_search_form()
    {
        $data = array();

        $data['current_search_domain'] = $this->session->userdata('search-domain');
        if (!$data['current_search_domain'])
            $data['current_search_domain'] = 'local';

        $data['current_search_group'] = $this->session->userdata('search-group');
        if (!$data['current_search_group'])
            $data['current_search_group'] = 'tutors';

        $data['current_subject_id'] = $this->session->userdata('search-subject');
        if ($data['current_subject_id'])
            $data['current_subject_id'] = $data['current_subject_id']['id'];
        else
            $data['current_subject_id'] = '';

        $data['readable_location'] = $this->session->userdata('search-location');
        if ($data['readable_location'])
            $data['readable_location'] = $data['readable_location']['readable'];
        else
            $data['readable_location'] = '';

        $data['subject_options'] = array();

        $data['subject_options'][''] = 'All Subjects';
        
        if (!empty($this->subjects)) {
            foreach($this->subjects as $subject) {
/*
                $val_string = '{"id":"'.$subject["id"].'","name":"'.$subject['name'].'"}';
                $val_string = form_prep($val_string);
*/
                $val_string = $subject['id'];
                if (isset($data['subject_options'][$subject['category']]))
                    $data['subject_options'][$subject['category']][$val_string] = $subject['name'];
                else
                    $data['subject_options'][$subject['category']] = array($val_string => $subject['name']);
            }

            ksort($data['subject_options']);

            if (isset($data['subject_options']['Other']))
            {
                // Get 'Other' category to the end of the subjects list
                $tmp = $data['subject_options']['Other'];
                unset($data['subject_options']['Other']);
                $data['subject_options']['Other'] = $tmp;
            }
        }
        
        $search_form = $this->view('components/search_form', $data, TRUE);

        return $search_form;
    }

}