<?php 

/**
 * Model_products
 * 
 * Handles all database operations related to products including:
 * - Managing product data and stock
 * - CRUD operations for products
 * - Product statistics and analytics
 * 
 * @package     Cattle Inventory
 * @subpackage  Models
 * @category    Product Management
 * @author      Your Name
 * @link        http://your-website.com
 */
class Model_products extends CI_Model
{
	/**
	 * Constructor
	 * 
	 * Initializes the model
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get Product Data
	 * 
	 * Retrieves product data by ID or all products
	 * 
	 * @param int|null $id Product ID
	 * @return array
	 */
	public function getProductData($id = null)
	{
		if ($id) {
			$sql = "SELECT * FROM products WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM products ORDER BY id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 * Get Active Product Data
	 * 
	 * Retrieves all active products
	 * 
	 * @return array
	 */
	public function getActiveProductData()
	{
		$sql = "SELECT * FROM products WHERE availability = ? ORDER BY id DESC";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/**
	 * Create Product
	 * 
	 * Creates a new product record
	 * 
	 * @param array $data Product data
	 * @return bool
	 */
	public function create($data)
	{
		if ($data) {
			return $this->db->insert('products', $data);
		}
		return false;
	}

	/**
	 * Create Stock
	 * 
	 * Creates a new stock record
	 * 
	 * @param array $data Stock data
	 * @return bool
	 */
	public function createStock($data)
	{
		if ($data) {
			return $this->db->insert('medicine_stock', $data);
		}
		return false;
	}

	/**
	 * Update Medicine Stock
	 * 
	 * Updates medicine stock data
	 * 
	 * @param array $data Stock data
	 * @param int $id Medicine ID
	 * @return bool
	 */
	public function updateMedicineStock($data, $id)
	{
		if ($data && $id) {
			$this->db->where('medicine_id', $id);
			return $this->db->update('medicine_stock', $data);
		}
		return false;
	}

	/**
	 * Update Product
	 * 
	 * Updates an existing product record
	 * 
	 * @param array $data Product data
	 * @param int $id Product ID
	 * @return bool
	 */
	public function update($data, $id)
	{
		if ($data && $id) {
			$this->db->where('id', $id);
			return $this->db->update('products', $data);
		}
		return false;
	}

	/**
	 * Remove Product
	 * 
	 * Deletes a product record
	 * 
	 * @param int $id Product ID
	 * @return bool
	 */
	public function remove($id)
	{
		if ($id) {
			$this->db->where('id', $id);
			return $this->db->delete('products');
		}
		return false;
	}

	/**
	 * Count Total Products
	 * 
	 * Counts total number of products
	 * 
	 * @return int
	 */
	public function countTotalProducts()
	{
		$sql = "SELECT * FROM products";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	//added by asad

	/**
	 * Get Medicine Stock
	 * 
	 * Retrieves medicine stock data
	 * 
	 * @param int $medicine_id Medicine ID
	 * @return array|null
	 */
	public function getMedicineStck($medicine_id)
	{
		$this->db->where('id', $medicine_id);
		$query = $this->db->get('medicines');
		return $query->row_array();
	}

	/**
	 * Get Medicine ID
	 * 
	 * Retrieves medicine data by ID
	 * 
	 * @param int $medicine_id Medicine ID
	 * @return array|null
	 */
	public function getMedicineId($medicine_id)
	{
		$this->db->where('medicine_id', $medicine_id);
		$query = $this->db->get('medicine_stock'); // Assuming 'medicine_stock' is the name of your stock table
		return $query->row_array(); // Return a single row as an associative array
	}

	/**
	 * Get Stock By Medicine ID
	 * 
	 * Retrieves stock data for a specific medicine and customer
	 * 
	 * @param int $medicine_id Medicine ID
	 * @param int $customer_id Customer ID
	 * @return array|null
	 */
	public function getStockByMedicineId($medicine_id, $customer_id)
	{
		$this->db->where('medicine_id', $medicine_id);
		$this->db->where('customer_id', $customer_id);
		$query = $this->db->get('medicine_stock'); // Assuming 'medicine_stock' is the name of your stock table
		return $query->row_array(); // Return a single row as an associative array
	}

	/**
	 * Update Medicines Stock
	 * 
	 * Updates medicine stock quantity
	 * 
	 * @param int $medicine_id Medicine ID
	 * @param int $new_qty New quantity
	 * @return bool
	 */
	public function updateMedicinesStock($medicine_id, $new_qty)
	{
		$this->db->where('id', $medicine_id);
		$this->db->update('medicines', array('stock' => $new_qty));
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Update Stock
	 * 
	 * Updates stock quantity for a medicine
	 * 
	 * @param int $medicine_id Medicine ID
	 * @param int $new_qty New quantity
	 * @return bool
	 */
	public function updateStock($medicine_id, $new_qty)
	{
		$this->db->where('medicine_id', $medicine_id);
		// $this->db->where('customer_id', $customer_id);
		$this->db->update('medicine_stock', array('qty' => $new_qty)); // Update the quantity
		return $this->db->affected_rows() > 0; // Return true if the update was successful
	}

	/**
	 * Count Total Medicine Taken
	 * 
	 * Retrieves medicine stock data with customer and medicine names
	 * 
	 * @return array
	 */
	public function count_total_medicine_taken()
	{
		$sql = "SELECT ms.*, c.name AS customer_name, m.name AS medicine_name 
				FROM medicine_stock ms
				JOIN customers c ON ms.customer_id = c.id
				JOIN medicines m ON ms.medicine_id = m.id"; 
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 * Get Most Ordered Product
	 * 
	 * Retrieves the top 3 most ordered products by order count
	 * 
	 * @return array
	 */
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

	/**
	 * Get Most Ordered Product By Quantity
	 * 
	 * Retrieves the top 3 most ordered products by total quantity
	 * 
	 * @return array
	 */
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