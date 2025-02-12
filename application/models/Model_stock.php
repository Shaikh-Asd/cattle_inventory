<?php 

class Model_stock extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the brand data */
	public function getStockData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM used_stock where id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM used_stock ORDER BY id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	public function countTotalStock()
	{
		$sql = "SELECT * FROM used_stock";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
}