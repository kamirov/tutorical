<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Data Model
 *
 * This model gets and sets misc data (e.g. autocomplete locations, education fields, positions, etc.). 
 *
 */

class Data_model extends CI_Model 
{

	private $table_prefix = 'data_';

	function get_data_like($term, $table)
	{
		$this->db->select('name')
				 ->from($this->table_prefix.$table)
				 ->like('name', $term)
				 ->where('status', ITEM_STATUS_ACTIVE)
				 ->order_by('occurrences DESC, name ASC');

		$data = $this->db->get()->result_array();
		$data = combine_subarrays($data, 'name');

//		var_export($this->db->last_query());

		return $data;
	}

	function get_all_items($table, $status = ITEM_STATUS_PENDING)
	{
		return $this->db->where('status', $status)->get($this->table_prefix.$table)->result_array();
	}

	function get_item($by, $val, $table)
	{
		$item = $this->db->where($by, $val)
				 			 ->get($this->table_prefix.$table)->row_array();

		return $item;
	}

	function item_exists($by, $val, $table)
	{
		$this->db->select('1', FALSE);
		$this->db->where($by, $val);

		if ($this->db->get($this->table_prefix.$table)->row_array())
		{
			return TRUE;
		}
	}

	function increment_occurrences($by, $val, $table)
	{
		$this->db->set('occurrences', 'occurrences+1', FALSE)
				 ->where($by, $val)
				 ->update($this->table_prefix.$table);

		return $this->db->affected_rows() > 0;
	}

	function add_items($data_and_tables)
	{
		foreach($data_and_tables as $table => $name)
		{
			$data = array(
				'name' => $name,
				'occurrences' => 1,
				'status' => ITEM_STATUS_PENDING
			);
			$this->add_item($data, $table);
		}
	}

	function add_item($data, $table)
	{
		// We use the shorthand of $data = $name in some cases
		if (!is_array($data))
		{
			$data = array(
				'name' => $data,
				'occurrences' => 1,
				'status' => ITEM_STATUS_PENDING
			);
		}

		if (!$data['name'])
			return;

		// Check if same location name exists. If it does, then likely we're getting a query for a pending item or some malicious input, which means we accept it for their session, but don't put it in the DB
		// We always check the name because that's where the UNIQUE index is
		$this->db->select('1', FALSE)
			 	 ->where('name', $data['name']);
		$query = $this->db->get($this->table_prefix.$table);
		
		if ($query->num_rows() > 0)
		{
			$this->increment_occurrences('name', $data['name'], $table);
			return $this->db->affected_rows() > 0;
		}

		$this->db->insert($this->table_prefix.$table, $data);
		return $this->db->affected_rows() > 0;
	}

	function approve_data_item($item_id, $table, $value)
	{
		if ($table == 'locations')
		{
			$this->load->library('geography');

/*
			$location = $this->db->select('name')
				 	 		 	 ->where('id', $item_id)
				 	 			 ->get($this->table_prefix.$table)->row_array();
			$location = $location['name'];
*/

			$location = $value;

			$location = $this->geography->geocode($location);

			if ($location['status'] == 'OK')
			{
				if (!($location['lat'] && $location['lon']))
				{
					return FALSE;
				}
				
				$data = array(
					'id' => $item_id,
					'name' => $location['readable'],
					'lat' => $location['lat'],
					'lon' => $location['lon'],
					'occurrences' => 1,
					'status' => ITEM_STATUS_ACTIVE
				);
				$this->db->replace($this->table_prefix.$table, $data);
				return $this->db->affected_rows() > 0;
			}
			else
			{
				return FALSE;
			}
		}	

		$this->db->where('id', $item_id)
				 ->update($this->table_prefix.$table, array('status' => ITEM_STATUS_ACTIVE, 'name' => $value));
		return $this->db->affected_rows() > 0;
	}

	function hide_data_item($item_id, $table)
	{
		$this->db->where('id', $item_id)
				 ->update($this->table_prefix.$table, array('status' => ITEM_STATUS_HIDDEN));
		return $this->db->affected_rows() > 0;
	}

	function delete_data_item($item_id, $table)
	{
		$this->db->delete($this->table_prefix.$table, array('id' => $item_id));
		return $this->db->affected_rows() > 0;
	}
}