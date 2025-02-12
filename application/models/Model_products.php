<?php 

class Model_products extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the brand data */
	public function getProductData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM products where id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM products ORDER BY id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getActiveProductData()
	{
		$sql = "SELECT * FROM products WHERE availability = ? ORDER BY id DESC";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function create($data)
{
    if ($data) {
        $insert = $this->db->insert('products', $data);
        return ($insert == true) ? true : false;
    }
}
	public function createStock($data)
	{
		if ($data) {
			$insert = $this->db->insert(' medicine_stock', $data);
			return ($insert == true) ? true : false;
		}
	}
	public function updateMedicineStock($data, $id)
	{
		if ($data && $id) {
			$this->db->where('medicine_id', $id);
			$update	 = $this->db->update(' medicine_stock', $data);
			return ($update == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('products', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('products');
			return ($delete == true) ? true : false;
		}
	}

	public function countTotalProducts()
	{
		$sql = "SELECT * FROM products";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}


	public function countTotalbrands()
	{
		$sql = "SELECT * FROM brands";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	public function countTotalcategory()
	{
		$sql = "SELECT * FROM categories";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}


	public function countTotalattribures()
	{
		$sql = "SELECT * FROM attributes";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	//added by asad
	public function getMedicineId($medicine_id)
	{
		$this->db->where('medicine_id', $medicine_id);
		$query = $this->db->get('medicine_stock'); // Assuming 'medicine_stock' is the name of your stock table
		return $query->row_array(); // Return a single row as an associative array
	}
	public function getStockByMedicineId($medicine_id, $customer_id)
	{
		$this->db->where('medicine_id', $medicine_id);
		$this->db->where('customer_id', $customer_id);
		$query = $this->db->get('medicine_stock'); // Assuming 'medicine_stock' is the name of your stock table
		return $query->row_array(); // Return a single row as an associative array
	}

	public function updateStock($medicine_id, $new_qty)
	{
		$this->db->where('medicine_id', $medicine_id);
		// $this->db->where('customer_id', $customer_id);
		$this->db->update('medicine_stock', array('qty' => $new_qty)); // Update the quantity
		return $this->db->affected_rows() > 0; // Return true if the update was successful
	}

	public function count_total_medicine_taken()
	{
		$sql = "SELECT ms.*, c.name AS customer_name, m.name AS medicine_name 
				FROM medicine_stock ms
				JOIN customers c ON ms.customer_id = c.id
				JOIN medicines m ON ms.medicine_id = m.id"; 
		$query = $this->db->query($sql);
		return $query->result_array();
	}


	public function get_most_ordered_product() {
        // Join the orders and medicines table to get the product name
        $this->db->select('orders_item.product_id, COUNT(orders_item.product_id) AS order_count, medicines.name');
        $this->db->from('orders_item');
        $this->db->join('medicines', 'medicines.id = orders_item.product_id');
        $this->db->group_by('orders_item.product_id');
        $this->db->order_by('order_count', 'DESC');
        $this->db->limit(3);

        $query = $this->db->get();
        return $query->result_array();  
    }

	public function get_most_ordered_product_by_quantity() {

		$this->db->select('orders_item.product_id, SUM(orders_item.qty) AS total_quantity, medicines.name');
		$this->db->from('orders_item');
		$this->db->join('medicines', 'medicines.id = orders_item.product_id');
		$this->db->group_by('orders_item.product_id');
		$this->db->order_by('total_quantity', 'DESC');
		$this->db->limit(3);
	
		$query = $this->db->get();
		return $query->result_array();
	}
	
}