<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report_model extends CI_Model {

	function add_report($type, $id_or_username, $message)
	{
		$this->db->trans_start(IS_TEST_MODE);

		$data = array(
			'type' => $type,
			'username_or_item_id' => $id_or_username,
			'message' => $message,
			'ip' => $_SERVER['REMOTE_ADDR']
		);

		// We replace the row, making sure that if the same type, item_id, and ip are given, then the old report is erased (this is to prevent someone from reporting something multiple times)
		$this->db->replace('reports', $data);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}
}