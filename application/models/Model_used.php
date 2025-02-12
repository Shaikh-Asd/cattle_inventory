<?php 

class Model_used extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the brand data */
	public function getUsedData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM used where id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM used ORDER BY id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getActiveProductData()
	{
		$sql = "SELECT * FROM used WHERE availability = ? ORDER BY id DESC";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function create($data)
	{
		if ($data) {
			$this->db->insert('used', $data);
			return $this->db->affected_rows() > 0;
		}
	}

	public function createStock($data)
	{
		if ($data) {
			$this->db->insert('used_stock', $data);
			return $this->db->affected_rows() > 0;
		}
	}

	public function updateMedicineStock($data, $id)
	{
		if ($data && $id) {
			$this->db->where('medicine_id', $id);
			$update	 = $this->db->update('used_stock', $data);
			return ($update == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if ($data && $id) {
			$this->db->where('id', $id);
			$this->db->update('used', $data);
			return $this->db->affected_rows() > 0;
		}
	}

	public function remove($id)
	{
		if ($id) {
			$this->db->where('id', $id);
			$this->db->delete('used');
			return $this->db->affected_rows() > 0;
		}
	}

	public function countTotalUsed()
	{
		$sql = "SELECT * FROM used";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	//added by asad
	public function getMedicineId($medicine_id)
	{
		$this->db->where('medicine_id', $medicine_id);
		$query = $this->db->get('used_stock'); // Assuming 'medicine_stock' is the name of your stock table
		return $query->row_array(); // Return a single row as an associative array
	}

	public function getStockByMedicineId($medicine_id, $customer_id)
	{
		$this->db->where('medicine_id', $medicine_id);
		$this->db->where('customer_id', $customer_id);
		$query = $this->db->get('used_stock'); // Assuming 'medicine_stock' is the name of your stock table
		return $query->row_array(); // Return a single row as an associative array
	}

	public function updateStock($medicine_id, $customer_id, $new_qty)
	{
		$this->db->where('medicine_id', $medicine_id);
		$this->db->where('customer_id', $customer_id);
		$this->db->update('used_stock', array('qty' => $new_qty)); // Update the quantity
		return $this->db->affected_rows() > 0; // Return true if the update was successful
	}

	public function countTotalMedicineTaken()
	{
		$sql = "SELECT ms.*, c.name AS customer_name, m.name AS medicine_name 
				FROM used_stock ms
				JOIN customers c ON ms.customer_id = c.id
				JOIN medicines m ON ms.medicine_id = m.id"; 
		$query = $this->db->query($sql);
		return $query->result_array();
	}	
}