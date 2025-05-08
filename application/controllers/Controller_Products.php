<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller_Products
 * 
 * Handles all product-related operations including:
 * - Product management
 * - Stock management
 * - Transaction history
 * - Product updates
 * 
 * @package     Cattle Inventory
 * @subpackage  Controllers
 * @category    Product Management
 * @author      Your Name
 * @link        http://your-website.com
 */
class Controller_Products extends Admin_Controller 
{
	

	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Manage Inward Medicines';

		$this->load->model('model_products');
		$this->load->model('model_customers');
		$this->load->model('model_medicines');
		$this->load->model('Medicine_model');
		$this->load->model('Transaction_history_model');
	}

	/**
	 * Index
	 * 
	 * Displays the product management page
	 * 
	//  * @return void
	 */
	public function index()
	{
		try {
			if(!in_array('viewProduct', $this->permission)) {
				redirect('dashboard', 'refresh');
			}

			$this->render_template('products/index', $this->data);
		} catch (Exception $e) {
			log_message('error', 'Error in index: ' . $e->getMessage());
			$this->session->set_flashdata('error', 'Failed to load product management page. Please try again.');
			redirect('dashboard');
		}
	}

	/**
	 * Fetch Product Data
	 * 
	 * Retrieves product data for datatable display
	 * 
	 * @return void
	 */
	public function fetchProductData()
	{
		try {
			$result = array('data' => array());
			$data = $this->model_products->getProductData();

			foreach ($data as $key => $value) {
				$buttons = '';
				if(in_array('updateProduct', $this->permission)) {
					$buttons .= '<a href="'.base_url('Controller_Products/update/'.$value['id']).'" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>';
				}

				$qty_status = '';
				if($value['qty'] <= 10) {
					$qty_status = '<span class="label label-warning">Low !</span>';
				} else if($value['qty'] <= 0) {
					$qty_status = '<span class="label label-danger">Out of stock !</span>';
				}

				// Fetch customer data
				$customer_data = $this->model_customers->getVendorDataById($value['customer_id']);
				$customer_name = isset($customer_data['name']) ? $customer_data['name'] : 'Unknown Customer';
				$namecustomer = '<a href="'.base_url('Transaction_history/customer_transactions/'.$value['customer_id']).'">'.$customer_name.'</a>';

				// Fetch medicine data
				$medicine_ids = explode(',', $value['medicine_id']);
				$medicine_names = [];
				foreach ($medicine_ids as $id) {
					$medicine_data = $this->model_medicines->getMedicinesDataById($id);
					$medicine_names[] = isset($medicine_data['name']) ? $medicine_data['name'] : 'Unknown Medicine';
				}
				$medicine_name_short = implode(', ', array_slice($medicine_names, 0, 3));

				// Process quantities
				$qty_arr = explode(',', $value['qty']);
				$qty_short = implode(', ', $qty_arr);

				$result['data'][$key] = array(
					$key + 1,
					$namecustomer,
					$medicine_name_short,
					$qty_short,
					$value['created_at'],
					$buttons
				);
			}

			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($result));
		} catch (Exception $e) {
			log_message('error', 'Error in fetchProductData: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Failed to fetch product data']));
		}
	}

	/**
	 * Create Product
	 * 
	 * Handles the creation of new products
	 * 
	 * @return void
	 */
	public function create()
	{
		try {
			if (!in_array('createProduct', $this->permission)) {
				redirect('dashboard', 'refresh');
			}

			// Set validation rules
			$this->form_validation->set_rules('customers', 'Vendor name', 'trim|required');
			$this->form_validation->set_rules('product_name[]', 'Medicine name', 'trim|required');
			$this->form_validation->set_rules('qty[]', 'Quantity', 'trim|required|numeric|greater_than[0]');

			if ($this->form_validation->run() == TRUE) {
				$customer_id = $this->input->post('customers');
				$product_names = $this->input->post('product_name');
				$quantities = $this->input->post('qty');

				$data = array(
					'customer_id' => $customer_id,
					'medicine_id' => implode(',', $product_names),
					'qty' => implode(',', $quantities),
				);

				$create = $this->model_products->create($data);
				
				if ($create) {
					$this->process_transaction($create, $customer_id, $product_names, $quantities);
					$this->session->set_flashdata('success', 'Successfully created');
					redirect('Controller_Products/', 'refresh');
				} else {
					throw new Exception('Failed to create product');
				}
			} else {
				$this->store_form_data();
				$this->load_create_view();
			}
		} catch (Exception $e) {
			log_message('error', 'Error in create: ' . $e->getMessage());
			$this->session->set_flashdata('error', 'Failed to create product: ' . $e->getMessage());
			redirect('Controller_Products/create', 'refresh');
		}
	}

	/**
	 * Process Transaction
	 * 
	 * Handles the creation of transaction records and updates stock
	 * 
	 * @param int $create_id The ID of the created product
	 * @param int $customer_id The ID of the customer
	 * @param array $product_names Array of product names
	 * @param array $quantities Array of quantities
	 * @return void
	 */
	private function process_transaction($create_id, $customer_id, $product_names, $quantities)
	{
		$transaction_data = array(
			'transaction_type' => 'inward',
			'reference_id' => $create_id,
			'customer_id' => $customer_id,
			'user_id' => $this->session->userdata('id'),
			'notes' => 'Medicine inward transaction'
		);
		
		$transaction_id = $this->Transaction_history_model->add_transaction($transaction_data);
		
		foreach ($product_names as $index => $medicine_id) {
			$existing_stock = $this->model_products->getMedicineStck($medicine_id);
			$current_stock = isset($existing_stock['stock']) ? (int)$existing_stock['stock'] : 0;
			$new_qty = $current_stock + (int)$quantities[$index];
			
			$this->model_products->updateMedicinesStock($medicine_id, $new_qty);
			
			$detail_data = array(
				'transaction_id' => $transaction_id,
				'medicine_id' => $medicine_id,
				'quantity' => $quantities[$index],
				'operation' => 'add',
				'previous_stock' => $current_stock,
				'new_stock' => $new_qty
			);
			
			$this->Transaction_history_model->add_transaction_details($detail_data);
		}
	}

	/**
	 * Store Form Data
	 * 
	 * Stores form data in session for repopulation
	 * 
	 * @return void
	 */
	private function store_form_data()
	{
		$this->session->set_flashdata('form_data', array(
			'customers' => $this->input->post('customers'),
			'product_name' => $this->input->post('product_name'),
			'qty' => $this->input->post('qty')
		));
		
		$this->session->set_flashdata('validation_errors', validation_errors());
	}

	/**
	 * Load Create View
	 * 
	 * Loads the create product view with necessary data
	 * 
	 * @return void
	 */
	private function load_create_view()
	{
		$this->data['customers'] = $this->model_customers->getCustomerData(2);
		$this->data['medicines'] = $this->Medicine_model->get_medicines();
		$this->render_template('products/create', $this->data);
	}

	/**
	 * Upload Image
	 * 
	 * Handles image upload for products
	 * 
	 * @return string|bool Returns error message on failure, true on success
	 */
	

	/**
	 * Update Product
	 * 
	 * Handles the update of existing products
	 * 
	 * @param int $product_id The ID of the product to update
	 * @return void
	 */
	public function update($product_id)
	{
		if (!in_array('updateProduct', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		if (!$product_id) {
			redirect('dashboard', 'refresh');
		}

		$this->form_validation->set_rules('customers', 'Customer name', 'trim|required');
		$this->form_validation->set_rules('product_name[]', 'Medicine name', 'trim|required');
		$this->form_validation->set_rules('qty[]', 'Qty', 'trim|required|numeric|greater_than[0]');
		// $this->form_validation->set_rules('availability', 'Availability', 'trim|required'); // Uncomment if needed

		if ($this->form_validation->run() == TRUE) {
			$customer_id = $this->input->post('customers');
			$product_names = $this->input->post('product_name');
			$quantities = $this->input->post('qty');

			// Fetch old data for stock adjustment
			$old_data = $this->model_products->getProductData($product_id);
			$old_medicine_ids = explode(',', $old_data['medicine_id']);
			$old_quantities = explode(',', $old_data['qty']);

			if (!is_array($old_medicine_ids)) {
				$old_medicine_ids = [$old_medicine_ids];
			}
			if (!is_array($old_quantities)) {
				$old_quantities = [$old_quantities];
			}

			$old_meds = [];
			foreach ($old_medicine_ids as $idx => $mid) {
				$mid_int = (int)trim($mid);
				$qty_int = isset($old_quantities[$idx]) ? (int)$old_quantities[$idx] : 0;
				$old_meds[$mid_int] = $qty_int;
			}

			// Normalize new medicines
			$new_meds = [];
			foreach ($product_names as $idx => $mid) {
				$mid_int = (int)trim($mid);
				$qty_int = isset($quantities[$idx]) ? (int)$quantities[$idx] : 0;
				$new_meds[$mid_int] = $qty_int;
			}
			//dont remove this code ever
			// Create transaction record for stock adjustment
			$transaction_data = array(
				// 'transaction_type' => 'adjustment',
				'transaction_type' => 'inward',
				'reference_id' => $product_id,
				'customer_id' => $customer_id,
				'user_id' => $this->session->userdata('id'),
				// 'notes' => 'Medicine stock adjustment'
				'notes' => 'Medicine inward transaction'
			);

			//update transaction_history
			$transaction_id =  $this->Transaction_history_model->update_transaction_history($transaction_data);
			//update transaction_detailsprint
			// 1. Handle removed medicines (deduct their stock)
			foreach ($old_meds as $mid => $old_qty) {
				if (!array_key_exists($mid, $new_meds)) {

					// Record transaction detail
					//remove row from transaction_details
					$existing_stock = $this->model_products->getMedicineStck($mid);
					$current_stock = isset($existing_stock['stock']) ? (int)$existing_stock['stock'] : 0;
					$new_stock = $current_stock - $old_qty;

					if ($new_stock == 0) {
						// Medicine was removed, so deduct its old quantity from stock
						$this->Medicine_model->update_stock($mid, $old_qty, 'deduct');
						$this->Transaction_history_model->delete_transaction_details($transaction_id, $mid);
					}
					// $detail_data = array(
					//     'transaction_id' => $transaction_id,
					//     'medicine_id' => $mid,
					//     'quantity' => $old_qty,
					//     'operation' => 'deduct',
					//     // 'previous_stock' => $current_stock,
					//     // 'new_stock' => $new_stock
					// );

					// $this->Transaction_history_model->add_transaction_details($detail_data);
					//update ransaction_details
					// $this->Transaction_history_model->update_transaction_details($detail_data);
				}
			}

			// 2. Handle added medicines or changed quantities
			foreach ($new_meds as $mid => $new_qty) {

				if (!array_key_exists($mid, $old_meds)) {
					// New medicine added, so add its quantity to stock
					$this->Medicine_model->update_stock($mid, $new_qty, 'add');

					// Record transaction detail
					$existing_stock = $this->model_products->getMedicineStck($mid);
					$current_stock = isset($existing_stock['stock']) ? (int)$existing_stock['stock'] : 0;
					$new_stock = $current_stock + $new_qty;

					$detail_data = array(
						'transaction_id' => $transaction_id,
						'medicine_id' => $mid,
						'quantity' => $new_qty,
						'operation' => 'add',
						// 'previous_stock' => $current_stock,
						// 'new_stock' => $new_stock
					);

					// $this->Transaction_history_model->add_transaction_details($detail_data);
					//update transaction_details
					$this->Transaction_history_model->update_transaction_details($detail_data);
				} else {
					$old_qty = $old_meds[$mid];
					if ($new_qty !== $old_qty) { // Only update if quantity changed
						$diff = $new_qty - $old_qty;
						$operation = ($diff > 0) ? 'add' : 'deduct';
						$abs_diff = abs($diff);

						$this->Medicine_model->update_stock($mid, $abs_diff, $operation);

						// Record transaction detail
						$existing_stock = $this->model_products->getMedicineStck($mid);
						$current_stock = isset($existing_stock['stock']) ? (int)$existing_stock['stock'] : 0;
						$new_stock = ($operation == 'add') ? $current_stock + $abs_diff : $current_stock - $abs_diff;

						$detail_data = array(
							'transaction_id' => $transaction_id,
							'medicine_id' => $mid,
							// 'quantity' => $abs_diff,
							'quantity' => $new_qty,
							'operation' => $operation,
							// 'previous_stock' => $current_stock,
							// 'new_stock' => $new_stock
						);

						// $this->Transaction_history_model->add_transaction_details($detail_data);
						//update transaction_details
						$this->Transaction_history_model->update_transaction_details($detail_data);
					}
					// else: quantity is the same, do nothing!
				}
			}

			// Save updated data
			$product_names_str = implode(',', $product_names);
			$quantities_str = implode(',', $quantities);

			$data = array(
				'customer_id' => $customer_id,
				'medicine_id' => $product_names_str,
				'qty' => $quantities_str,
			);
			$update = $this->model_products->update($data, $product_id);
			if ($update == true) {
				$this->session->set_flashdata('success', 'Successfully updated');
				redirect('Controller_Products/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('Controller_Products/update/' . $product_id, 'refresh');
			}
		} else {
			$type = 2;
			$product_data = $this->model_products->getProductData($product_id);
			$this->data['product_data'] = $product_data;
			$this->data['customers'] = $this->model_customers->getCustomerData($type);
			$this->data['medicines'] = $this->Medicine_model->get_medicines_list();
			$this->render_template('products/edit', $this->data);
		}
	}
	

	/**
	 * Update Stock
	 * 
	 * Updates the stock level for a medicine
	 * 
	 * @param int $medicine_id The ID of the medicine
	 * @param int $quantity The quantity to update
	 * @param string $operation The operation to perform (add/deduct)
	 * @return void
	 */
	public function update_stock($medicine_id, $quantity, $operation = 'deduct')
	{
		try {
			if (!$medicine_id || !$quantity) {
				throw new Exception('Medicine ID and quantity are required');
			}

			$result = $this->model_products->updateMedicinesStock($medicine_id, $quantity);
			
			if ($result) {
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode(['status' => 'success']));
			} else {
				throw new Exception('Failed to update stock');
			}
		} catch (Exception $e) {
			log_message('error', 'Error in update_stock: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Failed to update stock']));
		}
	}

	/**
	 * Remove Product
	 * 
	 * Handles product deletion
	 * 
	 * @return void
	 */
	public function remove()
	{
		try {
			if (!in_array('deleteProduct', $this->permission)) {
				redirect('dashboard', 'refresh');
			}

			$product_id = $this->input->post('product_id');
			$response = array();
			
			if ($product_id) {
				$delete = $this->model_products->remove($product_id);
				if ($delete == true) {
					$response['success'] = true;
					$response['messages'] = "Successfully removed";
				} else {
					$response['success'] = false;
					$response['messages'] = "Error in the database while removing the product information";
				}
			} else {
				$response['success'] = false;
				$response['messages'] = "Refresh the page again!!";
			}

			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($response));
		} catch (Exception $e) {
			log_message('error', 'Error in remove: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Failed to remove product']));
		}
	}

	/**
	 * Fetch Product Data By ID
	 * 
	 * Retrieves product data for a specific product
	 * 
	 * @param int $product_id The ID of the product
	 * @return void
	 */
	public function fetchProductDataById($product_id)
	{
		try {
			if ($product_id) {
				$data = $this->model_products->getProductData($product_id);
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode($data));
			} else {
				throw new Exception('Product ID is required');
			}
		} catch (Exception $e) {
			log_message('error', 'Error in fetchProductDataById: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Failed to fetch product data']));
		}
	}

	/**
	 * Update Medicines Stock
	 * 
	 * Updates the stock level for multiple medicines
	 * 
	 * @param int $medicine_id The ID of the medicine
	 * @param int $new_qty The new quantity
	 * @return void
	 */
	public function updateMedicinesStock($medicine_id, $new_qty)
	{
		try {
			if (!$medicine_id || !$new_qty) {
				throw new Exception('Medicine ID and quantity are required');
			}

			$result = $this->model_products->updateMedicinesStock($medicine_id, $new_qty);
			
			if ($result) {
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode(['status' => 'success']));
			} else {
				throw new Exception('Failed to update medicine stock');
			}
		} catch (Exception $e) {
			log_message('error', 'Error in updateMedicinesStock: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Failed to update medicine stock']));
		}
	}
}