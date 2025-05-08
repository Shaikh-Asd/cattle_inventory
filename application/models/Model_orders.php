<?php

/**
 * Model_orders
 * 
 * Handles all database operations related to orders including:
 * - Order creation and management
 * - Order item tracking
 * - Order statistics and reporting
 * 
 * @package     Cattle Inventory
 * @subpackage  Models
 * @category    Order Management
 * @author      Your Name
 * @link        http://your-website.com
 */
class Model_orders extends CI_Model
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
	 * Get Orders Data
	 * 
	 * Retrieves order data by ID or all orders
	 * 
	 * @param int|null $id Order ID
	 * @return array
	 */
	public function getOrdersData($id = null)
	{
		if ($id) {
			$sql = "SELECT * FROM orders WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM orders ORDER BY id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 * Get Orders Item Data
	 * 
	 * Retrieves order items for a specific order
	 * 
	 * @param int|null $order_id Order ID
	 * @return array|bool
	 */
	public function getOrdersItemData($order_id = null)
	{
		if (!$order_id) {
			return false;
		}

		$sql = "SELECT * FROM orders_item WHERE order_id = ?";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	/**
	 * Create Order
	 * 
	 * Creates a new order with items and updates stock
	 * 
	 * @return int|bool Order ID if successful, false otherwise
	 */
	public function create()
	{
		$product_names = $this->input->post('product');
		$quantities = $this->input->post('qty');

		if (count($product_names) !== count($quantities)) {
			return false;
		}

		$data = array(
			'customer_name' => $this->input->post('taken_by'),
			'date_time' => strtotime(date('Y-m-d h:i:s a')),
			'user_id' => $this->input->post('taken_by'),
			'qty' => implode(',', $quantities),
			'medicine_id' => implode(',', $product_names)
		);

		if ($this->db->insert('orders', $data)) {
			$order_id = $this->db->insert_id();
			$this->load->model('model_products');

			foreach ($product_names as $index => $product_id) {
				if ($quantities[$index] > 0) {
					$customer_id = $this->input->post('taken_by');
					$quantity = $quantities[$index];

					// Update or create order item
					$existing_item = $this->db->get_where('orders_item', 
						array('customer_id' => $customer_id, 'product_id' => $product_id)
					)->row_array();

					if ($existing_item) {
						$new_qty = $existing_item['qty'] + $quantity;
						$this->db->where('id', $existing_item['id']);
						$this->db->update('orders_item', array('qty' => $new_qty));
					} else {
						$items = array(
							'order_id' => $order_id,
							'product_id' => $product_id,
							'qty' => $quantity,
							'customer_id' => $customer_id
						);
						$this->db->insert('orders_item', $items);
					}

					// Update medicine stock
					$product_data = $this->model_products->getMedicineId($product_id);
					$current_qty = $product_data['qty'] ?? 0;
					$new_qty = $current_qty - $quantity;
					
					$update_product = array('qty' => $new_qty);
					$this->model_products->updateMedicineStock($update_product, $product_id);
				}
			}
			return $order_id;
		}
		return false;
	}

	/**
	 * Count Order Items
	 * 
	 * Counts items in a specific order
	 * 
	 * @param int $order_id Order ID
	 * @return int
	 */
	public function countOrderItem($order_id)
	{
		if ($order_id) {
			$sql = "SELECT * FROM orders_item WHERE order_id = ?";
			$query = $this->db->query($sql, array($order_id));
			return $query->num_rows();
		}
		return 0;
	}

	/**
	 * Update Order
	 * 
	 * Updates an existing order and its items
	 * 
	 * @param int $id Order ID
	 * @return bool
	 */
	public function update($id)
	{
		if ($id) {
			$user_id = $this->session->userdata('id');
			$data = array(
				'customer_name' => $this->input->post('customer_name'),
				'customer_address' => $this->input->post('customer_address'),
				'customer_phone' => $this->input->post('customer_phone'),
				'gross_amount' => $this->input->post('gross_amount_value'),
				'service_charge_rate' => $this->input->post('service_charge_rate'),
				'service_charge' => ($this->input->post('service_charge_value') > 0) ? $this->input->post('service_charge_value') : 0,
				'vat_charge_rate' => $this->input->post('vat_charge_rate'),
				'vat_charge' => ($this->input->post('vat_charge_value') > 0) ? $this->input->post('vat_charge_value') : 0,
				'net_amount' => $this->input->post('net_amount_value'),
				'discount' => $this->input->post('discount'),
				'paid_status' => $this->input->post('paid_status'),
				'user_id' => $user_id
			);

			$this->db->where('id', $id);
			if ($this->db->update('orders', $data)) {
				$this->load->model('model_products');
				$order_items = $this->getOrdersItemData($id);

				// Restore product quantities
				foreach ($order_items as $item) {
					$product_data = $this->model_products->getProductData($item['product_id']);
					$update_qty = $item['qty'] + $product_data['qty'];
					$update_product_data = array('qty' => $update_qty);
					$this->model_products->update($update_product_data, $item['product_id']);
				}

				// Remove existing order items
				$this->db->where('order_id', $id);
				$this->db->delete('orders_item');

				// Add new order items
				$products = $this->input->post('product');
				$quantities = $this->input->post('qty');
				$rates = $this->input->post('rate_value');
				$amounts = $this->input->post('amount_value');

				foreach ($products as $index => $product_id) {
					$items = array(
						'order_id' => $id,
						'product_id' => $product_id,
						'qty' => $quantities[$index],
						'rate' => $rates[$index],
						'amount' => $amounts[$index]
					);
					$this->db->insert('orders_item', $items);

					// Update product stock
					$product_data = $this->model_products->getProductData($product_id);
					$qty = (int)$product_data['qty'] - (int)$quantities[$index];
					$update_product = array('qty' => $qty);
					$this->model_products->update($update_product, $product_id);
				}
				return true;
			}
		}
		return false;
	}

	/**
	 * Remove Order
	 * 
	 * Deletes an order and its items
	 * 
	 * @param int $id Order ID
	 * @return bool
	 */
	public function remove($id)
	{
		if ($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('orders');

			$this->db->where('order_id', $id);
			$delete_item = $this->db->delete('orders_item');
			
			return ($delete && $delete_item);
		}
		return false;
	}

	/**
	 * Count Total Paid Orders
	 * 
	 * Counts total number of paid orders
	 * 
	 * @return int
	 */
	public function countTotalPaidOrders()
	{
		$sql = "SELECT * FROM orders WHERE paid_status = ?";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}

	/**
	 * Count Total Orders
	 * 
	 * Counts total number of orders
	 * 
	 * @return int
	 */
	public function countTotalOrders()
	{
		$sql = "SELECT * FROM orders";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	/**
	 * Count Total Medicine Given
	 * 
	 * Retrieves medicine distribution data with customer and medicine details
	 * 
	 * @return array
	 */
	public function count_total_medicine_given()
	{
		$sql = "SELECT ms.*, c.name AS customer_name, m.name AS medicine_name 
				FROM orders_item ms
				JOIN customers c ON ms.customer_id = c.id
				JOIN medicines m ON ms.product_id = m.id";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
}
