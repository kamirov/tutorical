<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Text Model
 *
 * This model sets and retrieves data for text pages. 
 *
 */

class Text_model extends CI_Model {

	private $table = 'text_pages';

	function get_page($wheres)
	{
		$this->db->select('*')
				 ->from($this->table);

		foreach ($wheres as $key => $value)
		{
			$this->db->where($key, $value);
		}

		return $this->db->get()->row_array();
	}


	function get_page_by_slug($slug)
	{
		$this->db->select('*')
				 ->from($this->table)
				 ->where('slug', $slug);

		return $this->db->get()->row_array();
	}

}