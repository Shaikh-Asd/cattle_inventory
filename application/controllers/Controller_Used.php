<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_Used extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Manage Used Medicines';

		$this->load->model('model_used');
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
        if(!in_array('viewUsed', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->render_template('used/index', $this->data);	
	}

    /*
    * It Fetches the products data from the product table 
    * this function is called from the datatable ajax function
    */
	public function fetchUsedData()
	{
		$result = array('data' => array());
		$data = $this->model_used->getUsedData();
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
    * If the validation is not valid, then it redirects to the create page.
    * If the validation for each input field is valid then it inserts the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
    public function create()
    {
        if (!in_array('createUsed', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('customers', 'Customer name', 'trim|required');
        $this->form_validation->set_rules('medicine_name[]', 'Medicine name', 'trim|required');
        $this->form_validation->set_rules('qty[]', 'Qty', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            // Improved error handling for image upload
            $upload_image = $this->upload_image();
            if (!$upload_image) {
                $this->session->set_flashdata('errors', 'Image upload failed!');
                redirect('Controller_Used/create', 'refresh');
                return; // Early return to avoid further processing
            }

            // Prepare data for multiple products
            $customer_id = $this->input->post('customers');
            $medicine_names = $this->input->post('medicine_name');
            $quantities = $this->input->post('qty');

            // Use a helper method to handle medicine and quantity processing
            $this->processMedicinesAndQuantities($medicine_names, $quantities, $customer_id);

            if ($create) {
                $this->session->set_flashdata('success', 'Successfully created');
                redirect('Controller_Used/', 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('Controller_Used/create', 'refresh');
            }
        } else {
            $type = 1;
            // Load necessary data for the view
            $this->data['attributes'] = $this->model_attributes->getActiveAttributeData();
            $this->data['brands'] = $this->model_brands->getActiveBrands();
            $this->data['category'] = $this->model_category->getActiveCategroy();
            $this->data['stores'] = $this->model_stores->getActiveStore();
            $this->data['customers'] = $this->model_customers->getCustomerData($type);
            $this->data['medicines'] = $this->model_medicines->getMedicinesData();
            $this->render_template('used/create', $this->data);
        }
    }

    private function processMedicinesAndQuantities($medicine_names, $quantities, $customer_id)
    {
        // Concatenate values into comma-separated strings
        $medicine_names_str = implode(',', $medicine_names);
        $quantities_str = implode(',', $quantities);

        // Prepare data for insertion
        $data = array(
            'customer_id' => $customer_id,
            'medicine_id' => $medicine_names_str,
            'qty' => $quantities_str,
        );

        // Insert the data
        $create = $this->model_used->create($data);

        // Loop through each product to create or update stock entries
        foreach ($medicine_names as $index => $medicine_name) {
            // Check if the medicine already exists in stock
            $existing_stock = $this->model_used->getStockByMedicineId($medicine_name, $customer_id);
            
            if ($existing_stock) {
                // Update the existing stock entry
                $new_qty = $existing_stock['qty'] + $quantities[$index]; // Increase quantity
                $this->model_used->updateStock($medicine_name, $customer_id, $new_qty);
            } else {
                // Create a new stock entry
                $dataStock = array(
                    'customer_id' => $customer_id,
                    'medicine_id' => $medicine_name,
                    'qty' => $quantities[$index],
                );
                $this->model_used->createStock($dataStock);
            }
        }
    }

   

    /*
    * If the validation is not valid, then it redirects to the edit product page 
    * If the validation is successfully then it updates the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
	public function update($used_id)
	{      
        if(!in_array('updateUsed', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if(!$used_id) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('customers', 'Customer name', 'trim|required');
        $this->form_validation->set_rules('medicine_name[]', 'Medicine name', 'trim|required');
        $this->form_validation->set_rules('qty[]', 'Qty', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            // Prepare data for multiple products
            $customer_id = $this->input->post('customers');
            $medicine_names = $this->input->post('medicine_name');
            $quantities = $this->input->post('qty');

            // Use a helper method to handle medicine and quantity processing
            $this->processMedicinesAndQuantities($medicine_names, $quantities, $customer_id);

            if($update == true) {
                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('Controller_Used/', 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('Controller_Used/update/'.$used_id, 'refresh');
            }
        } else {
            // Load existing product data for the form
            $used_data = $this->model_used->getUsedData($used_id);
            $this->data['used_data'] = $used_data;
            $this->data['customers'] = $this->model_customers->getCustomerData();
            $this->data['medicines'] = $this->model_medicines->getMedicinesData();
            $this->data['attributes'] = $this->model_attributes->getActiveAttributeData();
            $this->data['brands'] = $this->model_brands->getActiveBrands();         
            $this->data['category'] = $this->model_category->getActiveCategroy();           
            $this->data['stores'] = $this->model_stores->getActiveStore();          

            $this->render_template('used/edit', $this->data); 
        }   
	}

    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */
	public function remove()
	{
        if(!in_array('deleteUsed', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
        $id = $this->input->post('used_id');

        $response = array();
        if($id) {
            $delete = $this->model_used->remove($id);
            if($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed"; 
            }
            else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the used information";
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }

        echo json_encode($response);
	}

    /*
    * This function fetches product data by ID and prepares it for the edit form
    */
    public function fetchUsedDataById($used_id)
    {
        $result = array();

        // Fetch product data by ID
        $used_data = $this->model_used->getUsedData($used_id);
       
        if ($used_data) {
            // Fetch customer data by ID
            $customer_data = $this->model_customers->getCustomerDataById($used_data['customer_id']);
            // print_r(value: $product_data['medicine_id']);
           
            // Check if medicine_id is a valid JSON string or a comma-separated string
            $medicine_ids = json_decode($used_data['medicine_id']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // If it's not valid JSON, treat it as a comma-separated string
                $medicine_ids = explode(',', $used_data['medicine_id']);
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
            $prices = json_decode($used_data['price']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $prices = explode(',', $used_data['price']);
            }

            $quantities = json_decode($used_data['qty']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $quantities = explode(',', $used_data['qty']);
            }

            // Prepare the result
            $result = array(
                'customer_name' => isset($customer_data['id']) ? $customer_data['id'] : 'Unknown Customer',
                'medicine_name' => $medicine_name,
                'qty' => $quantities,
                'id' => $used_data['id'],
            );
        }

        echo json_encode($result);
    }
    
}