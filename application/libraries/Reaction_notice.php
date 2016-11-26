<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reaction_notice
{
	public $is_set;

	function __construct()
	{
		$this->ci =& get_instance();
		$this->is_set = FALSE;
	}
	
	function set($text, $type = 'warning', $timeout = 0, $priority = 0)
	{
		$reaction_notice = array(
			'text' => $text,
			'type' => $type,
			'timeout' => $timeout,
			'priority' => $priority
		);

		$this->ci->session->set_flashdata('reaction_notice', $reaction_notice);
		$this->is_set = TRUE;
	}

	function set_quick($text, $type = 'success', $priority = 0)
	{
		$timeout = 2500;
		$this->set($text, $type, $timeout, $priority);
	}

	function get()
	{
		$this->is_set = FALSE;
		return $this->ci->session->flashdata('reaction_notice');
	}
}

/* End of file Reaction_notice.php */
/* Location: ./application/libraries/Reaction_notice.php */