<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Notice
 *
 * Site-wide notifications
 *
 * @version		1.0
 */
class Notice
{

	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->model('notices_model', 'notices');
	}
	
	/**
	 * Displays notice with passed text
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function make_notice($type = 'info', $content)
	{
		$this->notices->make_notice();
	}
	
	/**
	 * Shows notice, given its id
	 * (if attempts to login is being counted)
	 *
	 * @param	int
	 * @return	int
	 */
	function show_notices($type = 'all', $user_id = ALL_USERS_ID)
	{
		if ($notices = $this->ci->notices->get_notices($type, $user_id))
		{
			$data['notices'] = '';
			foreach($notices as $notice)
			{
				$data['notices'] .= $this->ci->load->view('components/notices/notice', $notice, TRUE);
			}

			$this->ci->load->view('components/notices/general-container', $data);
		}
		else
		{
			return NULL;
		}

	}
	
	/**
	 * Shows notices notices 
	 * (if attempts to login is being counted)
	 *
	 * @param	int
	 * @return	int
	 */
	function show_notice($nid)
	{
		$notice_data = $this->ci->notices->get_notice_by_id($nid);
		$this->ci->load->view('components/notice', $notice_data);
	}



}

/* End of file Notice.php */
/* Location: ./application/libraries/Notice.php */