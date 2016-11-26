<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Profile Notices Model
 *
 * This model represents notice data. It operates the following tables:
 * - notices
 *
 */
class Profile_notices_model extends CI_Model
{
	private $replacements;

	function __construct()
	{
		parent::__construct();

		$this->replacements = array(
			'{ACCOUNT_SETTINGS_LINK}' => base_url('account/settings'),
			'{ACCOUNT_PROFILE}' => base_url('account/profile'),
			'{ACCOUNT_STUDENTS}' => base_url('account/students'),
			'{ACCOUNT_TUTORS}' => base_url('account/tutors'),
			'{ACCOUNT_REQUESTS}' => base_url('account/requests'),
			'{ACCOUNT_MARKETING}' => base_url('account/marketing'),
			'{TUTOR_PROFILE}' => base_url('tutors/'.$this->session->userdata('username')),
			'{TUTOR_EMAIL}' => $this->session->userdata('email'),
			'{TUTOR_EMAIL_DOMAIN}' => end(explode('@', $this->session->userdata('email'))),
		);
	}

	function get_notices_count($user_id)
	{
		$count = $this->db->select('notices_count')
						  ->from('users')
						  ->where('id', $user_id)
						  ->get()->row_array();

		$count = $count['notices_count'];

		return $count;
	}

	function get_all_notices($user_id)
	{
		$this->db->select('pn.content, pn.type, pn.is_sticky, pn.title, tpn.id AS tpn_id, tpn.is_new, UNIX_TIMESTAMP(tpn.posted) posted')
				 ->from('tutors_profile_notices tpn')
				 ->join('profile_notices pn', 'pn.id = tpn.profile_notice_id')
				 ->join('users u', 'u.id = tpn.user_id')
				 ->where('u.id', $user_id)
				 ->order_by('posted', 'DESC');

		$query = $this->db->get();

		if ($query->num_rows > 0) 
			return $this->replace_templates($query->result_array());
		return NULL;
	}
	
	function delete_notice($notice_id, $user_id)
	{
		$where_criteria = array(
			'user_id' => $user_id,
			'profile_notice_id' => $notice_id
		);

		$this->db->delete('tutors_profile_notices', $where_criteria);

		return $this->db->affected_rows();
	}

	function delete_notice_by_id($tpn_id)
	{
		$data = array(
			'id' => $tpn_id
		);

		$this->db->delete('tutors_profile_notices', $data);

		return $this->db->affected_rows();
	}

	function confirm_ownership($tpn_id, $user_id)
	{
		$query = $this->db->select('1', FALSE)
				 	  	  ->from('tutors_profile_notices')
				 		  ->where('id', $tpn_id)
			 			  ->where('user_id', $user_id)
					 	  ->get();

		return $query->num_rows() == 1;
	}

	function add_notice($notice_id, $user_id = ALL_USERS_ID, $order = 0)
	{
		// if user_id = ALL_USERS_ID, then add a notice to all users
		if ($user_id == ALL_USERS_ID) 
		{

			$this->db->trans_start(IS_TEST_MODE);

			$data = array();

			//Get all ids
			$query = $this->db->select('id')->get('users');

			foreach($query->result_array() as $row)
			{
				$data_row = array(
					'profile_notice_id' => $notice_id,
					'user_id' => $row['id'],
					'is_new' => 1
//					'order' => $order
				);
				array_push($data, $data_row);
			}
			$this->db->insert_batch('tutors_profile_notices', $data);

			$this->db->trans_complete();

			return $this->db->trans_status();
		}

		// if order = 0 and user_id is not ALL_USERS_ID, then add notice with order that is $order_change higher than the highest order row
/*
		if (!$order) {
			$order_change = 100;

			$sql = "INSERT INTO `tutors_profile_notices` (`user_id`, `profile_notice_id`, `order`)
						SELECT $user_id, $notice_id, $order_change + MAX(`order`) FROM `tutors_profile_notices` WHERE `user_id` = $user_id";
			$this->db->query($sql);

			return $this->db->affected_rows();
		}
*/

		$this->db->trans_start(IS_TEST_MODE);

		$data = array (
			'profile_notice_id' => $notice_id,
			'user_id' => $user_id,
			'is_new' => 1
//			'order' => $order
		);

		$this->db->insert('tutors_profile_notices', $data);

		$this->increment_notices_count($user_id);	

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	function increment_notices_count($user_id)
	{
		$this->db->set('notices_count', 'notices_count+1', FALSE)
				 ->where('id', $user_id)
				 ->update('users');
	}

	function decrement_notices_count($user_id, $decrement_value = 1)
	{
		$this->db->set("notices_count", "notices_count-$decrement_value", FALSE)
				 ->where('id', $user_id)
				 ->update('users');
	}

	function replace_templates($results)
	{
		$replaced_results = array();

		foreach($results as $result) {
			$replaced_result = str_replace(array_keys($this->replacements), array_values($this->replacements), $result);
			array_push($replaced_results, $replaced_result);
		}

		return $replaced_results;
	}

	function new_to_old($user_id)
	{
		
		$this->db->update('tutors_profile_notices', array('is_new' => FALSE), array('user_id' => $user_id));

		$affected_rows = $this->db->affected_rows();

		$this->decrement_notices_count($user_id, $affected_rows);
		
	}
}
/* End of file profile_notices_model.php */
/* Location: ./application/models/profile_notices_model.php */