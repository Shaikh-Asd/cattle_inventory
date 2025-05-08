<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard
 * 
 * Handles all dashboard-related operations including:
 * - Displaying dashboard statistics
 * - Managing user medicine statistics
 * - Tracking medicine transactions
 * - Analyzing product and customer data
 * 
 * @package     Cattle Inventory
 * @subpackage  Controllers
 * @category    Dashboard Management
 * @author      Your Name
 * @link        http://your-website.com
 */
class Dashboard extends Admin_Controller 
{
	
	public $data = [];

	/**
	 * Constructor
	 * 
	 * Loads necessary models and libraries
	 * Checks user authentication
	 */
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();

		$this->data['page_title'] = 'Dashboard';
		
		// Load required models
		$this->load->model('model_products');
		$this->load->model('model_users');
		$this->load->model('model_customers');
		$this->load->model('model_medicines');
		$this->load->model('Medicine_model');
		$this->load->model('Model_groups');
	}

	/**
	 * Index
	 * 
	 * Displays the dashboard with various statistics
	 * 
	 * @return void
	 */
	public function index()
	{
		try {
			// Load product statistics
			$this->data['total_products'] = $this->model_products->countTotalProducts();
			// Load order statistics
			$this->data['total_orders'] = $this->Medicine_model->get_total_medicine_given();

			// Load user and store statistics
			$this->data['total_users'] = $this->model_users->countTotalUsers();
			// $this->data['total_stores'] = $this->model_stores->countTotalStores();

			// Load customer and vendor statistics
			$this->data['total_customers'] = $this->model_customers->getActiveCustomerData();
			$this->data['total_vendors'] = $this->model_customers->getActiveVendorData();

			// Load medicine statistics
			$this->data['total_medicines'] = $this->model_medicines->getActiveMedicinesData();
			$this->data['low_stock_medicines'] = $this->model_medicines->get_medicine_stock();
			
			// Load all medicines for stock display with stock quantity
			$this->data['medicines'] = $this->model_medicines->getActiveMedicinesData();

			// Load manager-wise data
			$this->data['managers'] = $this->Medicine_model->get_customers2();
			$this->data['manager_stocks'] = [];
			foreach ($this->data['managers'] as $manager) {
				$this->data['manager_stocks'][$manager->id] = $this->Medicine_model->get_customer_medicine_summary($manager->id);
			}

			// Check admin status
			$user_id = $this->session->userdata('id');
			$this->data['is_admin'] = ($user_id == 1);

			$this->render_template('dashboard', $this->data);
		} catch (Exception $e) {
			log_message('error', 'Error in index: ' . $e->getMessage());
			$this->session->set_flashdata('error', 'Failed to load dashboard data');
			redirect('dashboard');
		}
	}

	/**
	 * Get User Medicine Statistics
	 * 
	 * Retrieves medicine statistics for a specific user
	 * 
	 * @param int $userId The user ID
	 * @return void
	 */
	public function getUserMedicineStats($userId)
	{
		try {
			$data = $this->model_customers->getUserMedicineStats($userId);
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($data));
		} catch (Exception $e) {
			log_message('error', 'Error in getUserMedicineStats: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Failed to fetch user medicine statistics']));
		}
	}

	/**
	 * Count Total Medicine Given
	 * 
	 * Retrieves total count of medicine given
	 * 
	 * @return void
	 */
	

	/**
	 * Count Total Medicine Taken
	 * 
	 * Retrieves total count of medicine taken
	 * 
	 * @return void
	 */
	public function countTotalmedicineTaken()
	{
		try {
			$data = $this->model_products->count_total_medicine_taken();
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($data));
		} catch (Exception $e) {
			log_message('error', 'Error in countTotalmedicineTaken: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Failed to count total medicine taken']));
		}
	}

	/**
	 * Get Most Ordered Product
	 * 
	 * Retrieves the most ordered product by count
	 * 
	 * @return void
	 */
	public function most_ordered_product()
	{
		try {
			$result = $this->model_products->get_most_ordered_product();
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($result));
		} catch (Exception $e) {
			log_message('error', 'Error in most_ordered_product: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Failed to fetch most ordered product']));
		}
	}

	/**
	 * Get Most Ordered Product By Quantity
	 * 
	 * Retrieves the most ordered product by quantity
	 * 
	 * @return void
	 */
	public function getMostOrderedProductByQuantity()
	{
		try {
			$result = $this->model_products->get_most_ordered_product_by_quantity();
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($result));
		} catch (Exception $e) {
			log_message('error', 'Error in getMostOrderedProductByQuantity: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Failed to fetch most ordered product by quantity']));
		}
	}

	/**
	 * Get Medicine Stock
	 * 
	 * Retrieves current medicine stock levels
	 * 
	 * @return void
	 */
	public function getMedicineStock()
	{
		try {
			$result = $this->model_medicines->get_medicine_stock();
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($result));
		} catch (Exception $e) {
			log_message('error', 'Error in getMedicineStock: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Failed to fetch medicine stock']));
		}
	}

	/**
	 * Get Top Customers With Products
	 * 
	 * Retrieves top customers with their product information
	 * 
	 * @return void
	 */
	public function getTopCustomersWithProducts()
	{
		try {
			$result = $this->model_customers->get_top_customers_with_products();
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($result));
		} catch (Exception $e) {
			log_message('error', 'Error in getTopCustomersWithProducts: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Failed to fetch top customers with products']));
		}
	}
}