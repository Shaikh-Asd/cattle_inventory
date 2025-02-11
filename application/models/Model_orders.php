<?php

class Model_orders extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the orders data */
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

	// get the orders item data
	public function getOrdersItemData($order_id = null)
	{
		if (!$order_id) {
			return false;
		}

		$sql = "SELECT * FROM orders_item WHERE order_id = ?";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	public function create()
	{
		$product_names = $this->input->post('product');
		$quantities = $this->input->post('qty');

		// Ensure that product names and quantities are arrays of the same length
		if (count($product_names) !== count($quantities)) {
			return false; // or handle the error as needed
		}

		$data = array(
			'customer_name' => $this->input->post('taken_by'),
			'date_time' => strtotime(date('Y-m-d h:i:s a')),
			'user_id' => $this->input->post('taken_by'),
			'qty' => implode(',', $quantities), // Store quantities as a string
			'medicine_id' => implode(',', $product_names) // Store product IDs as a string
		);

		$insert = $this->db->insert('orders', $data);
		$order_id = $this->db->insert_id();

		$this->load->model('model_products');

		$count_product = count($product_names);
		for ($x = 0; $x < $count_product; $x++) {
			if ($quantities[$x] > 0) { // Check if quantity is greater than zero
				$customer_id = $this->input->post('taken_by');
				$product_id = $product_names[$x];
				$existing_item = $this->db->get_where('orders_item', array('customer_id' => $customer_id, 'product_id' => $product_id))->row_array();
				// print_r($existing_item);
				// exit;
				if ($existing_item) {
					// Update the existing item quantity
					$new_qty = $existing_item['qty'] + $quantities[$x];
					$this->db->where('id', $existing_item['id']);
					$this->db->update('orders_item', array('qty' => $new_qty));
				} else {
					$items = array(
						'order_id' => $order_id,
						'product_id' => $product_id,
						'qty' => $quantities[$x],
						'customer_id' => $customer_id
					);
					// insert the order item stock
					$this->db->insert('orders_item', $items);
				}

				// now decrease the stock from the medicine stock
				$product_data = $this->model_products->getMedicineId($product_names[$x]);
				if ($product_data['qty'] > 0) {
					$qty = (int) $product_data['qty'];
				} else {
					$qty = 0;
				}

				$qty =  $qty - (int) $quantities[$x];
				$update_product = array('qty' => $qty);
				$this->model_products->updateMedicineStock($update_product, $product_names[$x]);
			}
		}

		return ($order_id) ? $order_id : false;
	}

	public function countOrderItem($order_id)
	{
		if ($order_id) {
			$sql = "SELECT * FROM orders_item WHERE order_id = ?";
			$query = $this->db->query($sql, array($order_id));
			return $query->num_rows();
		}
	}

	public function update($id)
	{
		if ($id) {
			$user_id = $this->session->userdata('id');
			// fetch the order data 

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
			$update = $this->db->update('orders', $data);

			// now the order item 
			// first we will replace the product qty to original and subtract the qty again
			$this->load->model('model_products');
			$get_order_item = $this->getOrdersItemData($id);
			foreach ($get_order_item as $k => $v) {
				$product_id = $v['product_id'];
				$qty = $v['qty'];
				// get the product 
				$product_data = $this->model_products->getProductData($product_id);
				$update_qty = $qty + $product_data['qty'];
				$update_product_data = array('qty' => $update_qty);

				// update the product qty
				$this->model_products->update($update_product_data, $product_id);
			}

			// now remove the order item data 
			$this->db->where('order_id', $id);
			$this->db->delete('orders_item');

			// now decrease the product qty
			$count_product = count($this->input->post('product'));
			for ($x = 0; $x < $count_product; $x++) {
				$items = array(
					'order_id' => $id,
					'product_id' => $this->input->post('product')[$x],
					'qty' => $this->input->post('qty')[$x],
					'rate' => $this->input->post('rate_value')[$x],
					'amount' => $this->input->post('amount_value')[$x],
				);
				$this->db->insert('orders_item', $items);

				// now decrease the stock from the product
				$product_data = $this->model_products->getProductData($this->input->post('product')[$x]);
				$qty = (int) $product_data['qty'] - (int) $this->input->post('qty')[$x];

				$update_product = array('qty' => $qty);
				$this->model_products->update($update_product, $this->input->post('product')[$x]);
			}

			return true;
		}
	}



	public function remove($id)
	{
		if ($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('orders');

			$this->db->where('order_id', $id);
			$delete_item = $this->db->delete('orders_item');
			return ($delete == true && $delete_item) ? true : false;
		}
	}

	public function countTotalPaidOrders()
	{
		$sql = "SELECT * FROM orders WHERE paid_status = ?";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}

	public function countTotalOrders()
	{
		$sql = "SELECT * FROM orders";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	// public function countTotalmedineTaken()
	// {
	// 	$sql = "SELECT * FROM medicine_stock"; // Updated to fetch all records
	// 	$query = $this->db->query($sql);
	// 	return $query->result_array(); // Return all data as an array
	// }
	public function count_total_medicine_given()
	{
		$sql = "SELECT ms.*, c.name AS customer_name, m.name AS medicine_name 
	        FROM orders_item ms
	        JOIN customers c ON ms.customer_id = c.id
	        JOIN medicines m ON ms.product_id = m.id"; // Adjust table and column names as necessary
		$query = $this->db->query($sql);
		return $query->result_array(); // Return all data as an array
	}
}
