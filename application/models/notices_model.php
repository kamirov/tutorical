<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Notices Model
 *
 * This model represents notice data. It operates the following tables:
 * - notices
 *
 */
class Notices_model extends CI_Model
{
	private $table_name			= 'notices';

	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
	}
	
	function get_notices($type = 'all', $user_id = ALL_USERS_ID)
	{
		$this->db->where('user_id', $user_id);

		if ($type !== 'all' || empty($type))
		{
			$this->db->where('type', $type);
		}

		$query = $this->db->get($this->table_name);
		if ($query->num_rows > 0) return $query->result();
		
		return NULL;
	}
	
	/**
	 * Get notice by id
	 *
	 * @param	int
	 * @return	object
	 */
	function get_notice_by_id($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get($this->table_name);
		
		if ($query->num_rows === 1) return $query->row();
		
		return NULL;
	}

}

/* End of file notices_model.php */
/* Location: ./application/models/notices_model.php */