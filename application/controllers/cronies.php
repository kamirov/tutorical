<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cronies extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$is_cron_job = TRUE;		// Temporary

		if (!$is_cron_job)
		{
			show_404();
		}
	}

	function delete_old_sessions()
	{
		$interval = 'INTERVAL 14 DAY';

		$this->db->where("last_activity < UNIX_TIMESTAMP(DATE_SUB(NOW(), $interval))")		// Get all results that are less than current time - x days (== older than x days ago)
		 		 ->delete('ci_sessions');
	}

	function close_old_requests()
	{
		$interval = 'INTERVAL '.REQUEST_DAYS_UNTIL_EXPIRED.' DAY';
//		$interval = 'INTERVAL 100 DAY';	// Temporary

		$this->db
				->distinct()
				->select("
					r.id, r.user_id, r.num_of_applications, u.display_name, u.email,
					s.name as subject
				")
				->from('requests r')
				->join('users u', 'r.user_id = u.id')
				->join('requests_subjects rs', 'r.id = rs.request_id')
				->join('subjects s', 's.id = rs.subject_id')
				->where('r.status', REQUEST_STATUS_OPEN)
				->where('r.user_id !=', 0)
				->where("r.posted + $interval < NOW()");

		$result = $this->db->get()->result_array();

		$requests_ids = array();
		$emails = array();

		foreach($result as $item)
		{
//			var_dump($item);

			$requests_ids[] = $item['id'];

			$email = array(
				'to' => $item['email'],
				'subject' => 'Your '.$item['subject'].' request has expired',
				'data' => array(
					'request_id' => $item['id'],
					'subject' => $item['subject']
				)
			);

			if ($item['num_of_applications'])
			{
				// Send with-application email
				$email['template'] = 'request_expired_with_applications';
			}
			else
			{
				// Send no-application email
				$email['template'] = 'request_expired_no_applications';
			}

			$emails[] = $email;
//			var_dump($email);
		}

		if ($requests_ids)
		{
			$this->requests_model->batch_toggle_request($requests_ids, REQUEST_STATUS_EXPIRED);
			$this->email->process_emails($emails);
			var_dump($emails, $requests_ids);
		}
	}

	function reset_and_process_queued_emails()
	{
		if ($this->email->reset_emails_remaining())
		{
			return $this->email->process_queued_emails();			
		}
	}

	function backup_database()
	{
	
		// Load the DB utility class
		$this->load->dbutil();

		// This ignores the ci_sessions table, which gets WAAAAYY too large, and is also unnecessary
		$prefs = array(
			'ignore' => 'ci_sessions',
			'format' => 'txt'
		);

		// Backup your entire database and assign it to a variable
		$backup =& $this->dbutil->backup($prefs);

		// Load the file helper and write the file to your server
		$this->load->helper('file');
		$fname = 'tutorical ('.date('Y-m-d').').sql';
		write_file("backup/$fname", $backup);
	}

}

/* End of file cronies.php */
/* Location: ./application/controllers/cronies.php */