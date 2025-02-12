<?php 

class Model_history extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the brand data */
	public function getHistoryData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM used_history where id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM used_history ORDER BY id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	public function countTotalHistory()
	{
		$sql = "SELECT * FROM used_history";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
}