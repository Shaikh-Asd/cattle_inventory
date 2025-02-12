<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_History extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Manage History';

		$this->load->model('model_history');
		$this->load->model('model_brands');
		$this->load->model('model_category');
		$this->load->model('model_stores');
		$this->load->model('model_attributes');
		$this->load->model('model_customers');
        $this->load->model('model_medicines');
	}

    /* 
    * It only redirects to the manage product page
    */
	public function index()
	{
        if(!in_array('viewHistory', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->render_template('history/index', $this->data);	
	}

    /*
    * It Fetches the products data from the product table 
    * this function is called from the datatable ajax function
    */
	public function fetchStockData()
	{
		$result = array('data' => array());
		$data = $this->model_history->getHistoryData();
		foreach ($data as $key => $value) {
			// button
            $buttons = '';
            $availability = ($value['availability'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            $qty_status = '';
            if($value['qty'] <= 10) {
                $qty_status = '<span class="label label-warning">Low !</span>';
            } else if($value['qty'] <= 0) {
                $qty_status = '<span class="label label-danger">Out of stock !</span>';
            }

            // Fetch customer data by ID
            $customer_data = $this->model_customers->getCustomerDataById($value['customer_id']);
            // Fetch medicine data by multiple IDs
            $medicine_ids = explode(',', $value['medicine_id']); // Assuming medicine_id is a comma-separated string
            $medicine_names = [];
            foreach ($medicine_ids as $id) {
                $medicine_data = $this->model_medicines->getMedicinesDataById($id);
                $medicine_names[] = isset($medicine_data['name']) ? $medicine_data['name'] : 'Unknown Medicine';
            }
            $medicine_name = implode(', ', $medicine_names); // Join names with a comma

            // Check if 'name' key exists in the returned data
            $customer_name = isset($customer_data['name']) ? $customer_data['name'] : 'Unknown Customer';

            $count = $key + 1;
			$result['data'][$key] = array(
                $count,
				$customer_name,
				$medicine_name,
                $value['qty'] . ' ' . $qty_status,
				$availability,
                $value['created_at'],
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

    /*
    * This function fetches product data by ID and prepares it for the edit form
    */
    public function fetchHistoryDataById($history_id)
    {
        $result = array();

        // Fetch product data by ID
        $history_data = $this->model_history->getHistoryData($history_id);
       
        if ($history_data) {
            // Fetch customer data by ID
            $customer_data = $this->model_customers->getCustomerDataById($history_data['customer_id']);
            // print_r(value: $product_data['medicine_id']);
           
            // Check if medicine_id is a valid JSON string or a comma-separated string
            $medicine_ids = json_decode($history_data['medicine_id']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // If it's not valid JSON, treat it as a comma-separated string
                $medicine_ids = explode(',', $history_data['medicine_id']);
            }

            // Ensure that $medicine_ids is always an array
            if (!is_array($medicine_ids)) {
                $medicine_ids = [$medicine_ids]; // Wrap in an array if it's a single value
            }

            // Prepare medicine names
            $medicine_names = [];
            foreach ($medicine_ids as $id) {
                $medicine_data = $this->model_medicines->getMedicinesDataById(trim($id)); // Trim to remove any whitespace
                $medicine_names[] = isset($medicine_data['name']) ? $medicine_data['name'] : 'Unknown Medicine';
            }
            $medicine_name = implode(', ', $medicine_names); // Join names with a comma

            // Handle price and qty as comma-separated strings
            $prices = json_decode($history_data['price']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $prices = explode(',', $history_data['price']);
            }

            $quantities = json_decode($history_data['qty']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                    $quantities = explode(',', $history_data['qty']);
            }

            // Prepare the result
            $result = array(
                'customer_name' => isset($customer_data['id']) ? $customer_data['id'] : 'Unknown Customer',
                'medicine_name' => $medicine_name,
                'qty' => $quantities,
                'id' => $history_data['id'],
            );
        }

        echo json_encode($result);
    }
    
}