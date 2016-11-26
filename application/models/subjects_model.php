<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Subjects Model
 *
 *  
 *
 */
class Subjects_model extends CI_Model
{
	private $user_id;
	private $table = 'subjects';
	private $min_occurences_for_default = 5;

	function __construct()
	{
		parent::__construct();
		//$ci =& get_instance();
		$this->user_id = $this->session->userdata('user_id');
	}

	function get_all_subjects($include_category = TRUE)
	{
		$this->db->select('s.id, s.name name')
				 ->from('subjects s')
				 ->where_in('s.status', array(ITEM_STATUS_ACTIVE));

		if ($include_category)
		{
			$this->db->select('sc.name category')
					 ->join('subject_categories sc', 'sc.id = s.subject_category_id');
		}
		
		$this->db->order_by('s.name');
		
		// If includes category, then returns assoc array; otherwise flatten to 1D array
		if ($include_category)
		{
			return $this->db->get()->result_array();								
		}
		else
		{
			return combine_subarrays($this->db->get()->result_array(), 'name');	
		}
	}

	// Gets a subject, adds it if it doesn't exist
	function get_subject($by, $val)
	{
//		echo $by.' '.$val;

		if ($val)
		{
			$subject = $this->db->select('id, name')
					 ->from('subjects')
					 ->where($by, $val)
					 ->get()->row_array();
		}

		if (!$val)
		{
			$subject = array(
				'id' => '',
				'name' => ''
			);
		}
		elseif ($by == 'name' && empty($subject))
		{
			$id = $this->add_subject($val);
			$subject = array(
				'id' => $id,
				'name' => $val
			);
		}
		return $subject;
	}

	function get_existing_subjects($subject_names)
	{
		if (!empty($subject_names))
		{
			$this->db
					->select('name')
					->from($this->table)
					->where_in('name', $subject_names);		
			return $this->db->get()->result_array();					
		}
		return NULL;
	}

	function get_default_subjects()
	{
		$this->db
				->select('name')
				->from($this->table)
				->where_in('s.status', array(ITEM_STATUS_ACTIVE, ITEM_STATUS_PENDING));

		return $this->db->get()->result_array();
	}

	function get_subject_categories()
	{
		$this->db->select('name')
				 ->from('subject_categories');

		$subject_categories = $this->db->get()->result_array();
		return combine_subarrays($subject_categories, 'name');
	}

	function parse_subjects_string($subjects_string, $limit = NULL)
	{
		$this->load->helper('text');

		$inputted_subjects = clean_string_and_explode($subjects_string);

		if ($limit && count($inputted_subjects) > $limit)
		{
			return $this->form_validation->response(STATUS_UNKNOWN_ERROR);
		}

		// Only proceed if there are still any elements left
		if (count($inputted_subjects) == 0)
			return $this->form_validation->response(STATUS_UNKNOWN_ERROR);

		$new_subjects = $this->get_new_subjects($inputted_subjects);		
		$this->add_subjects($new_subjects);

		$subject_ids_as_array = $this->db->select("id subject_id")
									  	 ->where_in('name', $inputted_subjects)
									  	 ->get('subjects')->result_array();

		$subject_ids_as_array = combine_subarrays($subject_ids_as_array, 'subject_id');

		return $subject_ids_as_array;
	}

	function add_subjects($subjects)
	{
		if (!empty($subjects))
		{
			$data = array();
			foreach ($subjects as $subject)
				$data[] =  array('name' => $subject);
			$this->db->insert_batch('subjects', $data);
		}
	}

	function add_subject($subject)
	{
		$this->db->insert('subjects', array('name' => $subject));
		return $this->db->insert_id();
	}

	function get_new_subjects($inputted_subjects)
	{
		$subjects_in_database = combine_subarrays($this->subjects_model->get_existing_subjects($inputted_subjects), 'name');
		$new_subjects = array_udiff($inputted_subjects, $subjects_in_database, 'strcasecmp');

		// Title case each subject
		$subjects_in_database = array_map('title_case', $subjects_in_database);
		$new_subjects = array_map('title_case', $new_subjects);

		$new_subjects = array_iunique($new_subjects);

		return $new_subjects;
	}

	function approve_subject($subject_id, $value, $category)
	{
		if (!$category)
			return NULL;

		$this->db->trans_start();

		$category_id = $this->get_category_id($category);

		$this->db->where('id', $subject_id)
				 ->update('subjects', array(
				 	'status' => ITEM_STATUS_ACTIVE, 
				 	'name' => $value,
				 	'subject_category_id' => $category_id
				 	)
				 );

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	function get_category_id($category_name)
	{
		$this->db->select('id')
				 ->from('subject_categories')
				 ->where('name', $category_name);

		$category = $this->db->get()->row_array();

		if ($category)
			return $category['id'];

		// If here, then category doesn't exist; make a new category and get its id

		$this->db->insert('subject_categories', array('name' => $category_name));

		return $this->db->insert_id();
	}

	function reject_subject($subject_id)
	{
		$this->db->where('id', $subject_id)
				 ->update('subjects', array('status' => ITEM_STATUS_INACTIVE));

		return $this->db->affected_rows() > 0;
	}

}

/* End of file subjects_model.php */
/* Location: ./application/models/subjects_model.php */