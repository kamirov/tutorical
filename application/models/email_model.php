<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Email Model
 *
 * Handles queuing and checks if we can send a message (i.e. we are not over hourly limit)
 *
 */
class Email_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$ci =& get_instance();
	}

	function get_key($type)
	{
		if ($type == 'hourly')
		{
			$key = 'hourly_emails_remaining';
		}
		else
		{
			$key = 'backup_hourly_emails_remaining';			
		}
		return $key;
	}

	function decrement_emails_remaining($amount = 1, $type = 'hourly')
	{	
		$key = $this->get_key($type);

		$this->db->set('value', "value - $amount", FALSE)
				 ->where('key', $key)
				 ->update('globals');

		if ($this->db->affected_rows() > 0)
			return TRUE;
	}

	function decrement_backup_emails_remaining($amount = 1)
	{
		return $this->decrement_emails_remaining($amount, $type = 'backup');
	}

	function reset_emails_remaining()
	{
		$emails_remaining = $this->get_emails_remaining();

		if ($emails_remaining['hourly'] != HOURLY_EMAILS_MAX)
		{
			$this->db->set('value', HOURLY_EMAILS_MAX)
					 ->where('key', 'hourly_emails_remaining')
					 ->update('globals');

			if ($this->db->affected_rows() == 0)
			{
				return FALSE;
			}
		}

		if ($emails_remaining['backup'] != BACKUP_HOURLY_EMAILS_MAX)
		{
			$this->db->set('value', BACKUP_HOURLY_EMAILS_MAX)
	 				 ->where('key', 'backup_hourly_emails_remaining')
	 				 ->update('globals');

			if ($this->db->affected_rows() == 0)
			{
				return FALSE;
			}
		}
		return TRUE;
	}

	function get_emails_remaining()
	{
		$emails_remaining = $this->db->select('key, value')
				 					 ->get('globals')
				 					 ->result_array();
		$emails_remaining = key_values_to_assoc($emails_remaining);

		$emails_remaining['hourly'] = $emails_remaining['hourly_emails_remaining'];
		$emails_remaining['backup'] = $emails_remaining['backup_hourly_emails_remaining'];
		unset($emails_remaining['hourly_emails_remaining']);
		unset($emails_remaining['backup_hourly_emails_remaining']);
		
		return $emails_remaining;
	}

	function get_queued_emails()
	{
		$emails = $this->db->get('queued_emails')->result_array();

		var_dump($emails);

		foreach($emails as &$email)
		{
			$id = $email['id'];
			$priority = $email['priority'];

			$email = (array) json_decode($email['email_data']);
			$email['id'] = $id;
			$email['priority'] = $priority;
//			$email['bcc'] = json_decode($email['bcc']);
		}
		unset($email);

		return $emails;
	}

	function queue($email)
	{		
		if (!isset($email['bcc']))
			$email['bcc'] = '';

		if (!isset($email['to']))
			$email['to'] = '';

		$priority = (isset($email['priority']) ? $email['priority'] : 0);
		unset($email['priority']);

		unset($email['id']);

		$db_email = array(
			'email_data' => json_encode($email),
			'priority' => $priority
		);

		$this->db->insert('queued_emails', $db_email);

		return $this->db->affected_rows() > 0;
//		echo 'queued<br>';
	}

	function delete_queued_emails($ids)
	{
		if (!$ids)
			return TRUE;

		$this->db->where_in('id', $ids)
				 ->delete('queued_emails');

		 if ($this->db->affected_rows() > 0)
		 	return TRUE;
	}
}

/* End of file email_model.php */
/* Location: ./application/models/email_model.php */