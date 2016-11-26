<?  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AE_Session extends CI_Session {

    /**
     * Update an existing session
     *
     * @access    public
     * @return    void
    */
    function sess_update() {
       // skip the session update if this is an AJAX call! This is a bug in CI; see:
       // https://github.com/EllisLab/CodeIgniter/issues/154
       // http://codeigniter.com/forums/viewthread/102456/P15

        $ci =& get_instance();

       if ( !($ci->input->is_ajax_request()) ) {

           parent::sess_update();
       }
    }
}