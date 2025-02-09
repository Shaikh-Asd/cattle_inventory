<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_Products extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Inward Medicines';

		$this->load->model('model_products');
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
        if(!in_array('viewProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->render_template('products/index', $this->data);	
	}

    /*
    * It Fetches the products data from the product table 
    * this function is called from the datatable ajax function
    */
	public function fetchProductData()
	{
		$result = array('data' => array());

		$data = $this->model_products->getProductData();

		foreach ($data as $key => $value) {
         
            // $store_data = $this->model_stores->getStoresData($value['store_id']);
			// button
            $buttons = '';
            // if(in_array('updateProduct', $this->permission)) {
    		// 	$buttons .= '<a href="'.base_url('Controller_Products/update/'.$value['id']).'" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>';
            // }

            // if(in_array('deleteProduct', $this->permission)) { 
    		// 	$buttons .= ' <button type="button" class="btn btn-danger btn-sm" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
            // }
			

			$img = '<img src="'.base_url($value['image']).'" alt="'.$value['name'].'" class="img-circle" width="50" height="50" />';

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

			$result['data'][$key] = array(
				// $img,
				// $value['sku'],
				$customer_name,
				$medicine_name,
                // $value['name'],
                $value['qty'] . ' ' . $qty_status,
				// $value['price'],
                // $store_data['name'],
				$availability,
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
        if (!in_array('createProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('customers', 'Customer name', 'trim|required');
        $this->form_validation->set_rules('product_name[]', 'Medicine name', 'trim|required');
        // $this->form_validation->set_rules('price[]', 'Price', 'trim|required');
        $this->form_validation->set_rules('qty[]', 'Qty', 'trim|required');
        $this->form_validation->set_rules('availability', 'Availability', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $upload_image = $this->upload_image();

            // Prepare data for multiple products
            $customer_id = $this->input->post('customers');
            $product_names = $this->input->post('product_name');
            // $prices = $this->input->post('price');
            $quantities = $this->input->post('qty');
            $availability = $this->input->post('availability');

            // Concatenate values into comma-separated strings
            $product_names_str = implode(',', $product_names);
            // $prices_str = implode(',', $prices);
            $quantities_str = implode(',', $quantities);

            // Prepare data for insertion
            $data = array(
                'customer_id' => $customer_id,
                'medicine_id' => $product_names_str,
                // 'price' => $prices_str,
                'qty' => $quantities_str,
            );
            // print_r($data); 
            // exit;
            // Insert the data
            $create = $this->model_products->create($data);

            // Loop through each product to create or update stock entries
            foreach ($product_names as $index => $product_name) {
                // Check if the medicine already exists in stock
                $existing_stock = $this->model_products->getStockByMedicineId($product_name, $customer_id);
                
                if ($existing_stock) {
                    // Update the existing stock entry
                    $new_qty = $existing_stock['qty'] + $quantities[$index]; // Increase quantity
                    $this->model_products->updateStock($product_name, $customer_id, $new_qty);
                } else {
                    // Create a new stock entry
                    $dataStock = array(
                        'customer_id' => $customer_id,
                        'medicine_id' => $product_name,
                        'qty' => $quantities[$index],
                    );
                    $this->model_products->createStock($dataStock);
                }
            }

            if ($create) {
                $this->session->set_flashdata('success', 'Successfully created');
                redirect('Controller_Products/', 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('Controller_Products/create', 'refresh');
            }
        } else {
            // Load necessary data for the view
            $this->data['attributes'] = $this->model_attributes->getActiveAttributeData();
            $this->data['brands'] = $this->model_brands->getActiveBrands();
            $this->data['category'] = $this->model_category->getActiveCategroy();
            $this->data['stores'] = $this->model_stores->getActiveStore();
            $this->data['customers'] = $this->model_customers->getCustomerData();
            $this->data['medicines'] = $this->model_medicines->getMedicinesData();
            $this->render_template('products/create', $this->data);
        }
    }

    /*
    * This function is invoked from another function to upload the image into the assets folder
    * and returns the image path
    */
	public function upload_image()
    {
    	// assets/images/product_image
        $config['upload_path'] = 'assets/images/product_image';
        $config['file_name'] =  uniqid();
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';

        // $config['max_width']  = '1024';s
        // $config['max_height']  = '768';

        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('product_image'))
        {
            $error = $this->upload->display_errors();
            return $error;
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $type = explode('.', $_FILES['product_image']['name']);
            $type = $type[count($type) - 1];
            
            $path = $config['upload_path'].'/'.$config['file_name'].'.'.$type;
            return ($data == true) ? $path : false;            
        }
    }

    /*
    * If the validation is not valid, then it redirects to the edit product page 
    * If the validation is successfully then it updates the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
	public function update($product_id)
	{      
        if(!in_array('updateProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if(!$product_id) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('customers', 'Customer name', 'trim|required');
        $this->form_validation->set_rules('product_name[]', 'Medicine name', 'trim|required');
        $this->form_validation->set_rules('price[]', 'Price', 'trim|required');
        $this->form_validation->set_rules('qty[]', 'Qty', 'trim|required');
        $this->form_validation->set_rules('availability', 'Availability', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            // Prepare data for update
            // $data = array(
            //     'customer_id' => $this->input->post('customers'),
            //     'medicine_id' => json_encode($this->input->post('product_name')), // Store as JSON
            //     'qty' => json_encode($this->input->post('qty')),
            //     'price' => json_encode($this->input->post('price')),
            //     'availability' => $this->input->post('availability'),
            // );
            // Prepare data for multiple products
            $customer_id = $this->input->post('customers');
            $product_names = $this->input->post('product_name');
            $prices = $this->input->post('price');
            $quantities = $this->input->post('qty');
            $availability = $this->input->post('availability');

            // Handle image upload if necessary
            // if($_FILES['product_image']['size'] > 0) {
            //     $upload_image = $this->upload_image();
            //     $data['image'] = $upload_image;
            // }
           
            // Concatenate values into comma-separated strings
            $product_names_str = implode(',', $product_names);
            $prices_str = implode(',', $prices);
            $quantities_str = implode(',', $quantities);

            // Prepare data for insertion
            $data = array(
                'customer_id' => $customer_id,
                'medicine_id' => $product_names_str,
                'price' => $prices_str,
                'qty' => $quantities_str,
                'availability' => $availability,
            );
            $update = $this->model_products->update($data, $product_id);
            if($update == true) {
                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('Controller_Products/', 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('Controller_Products/update/'.$product_id, 'refresh');
            }
        } else {
            // Load existing product data for the form
            $product_data = $this->model_products->getProductData($product_id);
            $this->data['product_data'] = $product_data;
            $this->data['customers'] = $this->model_customers->getCustomerData();
            $this->data['medicines'] = $this->model_medicines->getMedicinesData();
            $this->data['attributes'] = $this->model_attributes->getActiveAttributeData();
            $this->data['brands'] = $this->model_brands->getActiveBrands();         
            $this->data['category'] = $this->model_category->getActiveCategroy();           
            $this->data['stores'] = $this->model_stores->getActiveStore();          

            $this->render_template('products/edit', $this->data); 
        }   
	}

    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */
	public function remove()
	{
        if(!in_array('deleteProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
        $id = $this->input->post('product_id');

        $response = array();
        if($id) {
            $delete = $this->model_products->remove($id);
            if($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed"; 
            }
            else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the product information";
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
    public function fetchProductDataById($product_id)
    {
        $result = array();

        // Fetch product data by ID
        $product_data = $this->model_products->getProductData($product_id);
       
        if ($product_data) {
            // Fetch customer data by ID
            $customer_data = $this->model_customers->getCustomerDataById($product_data['customer_id']);
            // print_r(value: $product_data['medicine_id']);
           
            // Check if medicine_id is a valid JSON string or a comma-separated string
            $medicine_ids = json_decode($product_data['medicine_id']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // If it's not valid JSON, treat it as a comma-separated string
                $medicine_ids = explode(',', $product_data['medicine_id']);
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
            $prices = json_decode($product_data['price']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $prices = explode(',', $product_data['price']);
            }

            $quantities = json_decode($product_data['qty']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $quantities = explode(',', $product_data['qty']);
            }

            // Prepare the result
            $result = array(
                'customer_name' => isset($customer_data['id']) ? $customer_data['id'] : 'Unknown Customer',
                'medicine_name' => $medicine_name,
                // 'price' => $prices,
                'qty' => $quantities,
                'availability' => $product_data['availability'],
                'id' => $product_data['id'],
            );
        }

        echo json_encode($result);
    }

}