<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Controller_Customer extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->not_logged_in();

        $this->data['page_title'] = 'Customers';

        $this->load->model('Model_customers');
    }

    /* 
	* redirect to the index page 
	*/
    public function index()
    {
        if (!in_array('viewCustomers', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->render_template('customers/index', $this->data);
    }


    public function fetchCustomerDataById($id)
    {
        if ($id) {
            $data = $this->Model_customers->getCustomerDataById($id);
            echo json_encode($data);
        }
    }

    // * gets the attribute data from data and returns the attribute 
	
	public function fetchCustomerData()
	{
		$result = array('data' => array());

		$data = $this->Model_customers->getAllCustomerData();

		foreach ($data as $key => $value) {


			// button
			$buttons = '
			<button type="button" class="btn btn-warning btn-sm" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>
			<button type="button" class="btn btn-danger btn-sm" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>
			';

			$status = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';
            $user_type = ($value['user_type'] == 1) ? '<span class="label label-success">User</span>' : '<span class="label label-warning">Vendor</span>';
            $count = $key + 1;
            $result['data'][$key] = array(
				$count,
                $value['id'],
				$value['name'],
				$status,
				$user_type,
				$buttons
			);


		} // /foreach

		echo json_encode($result);
	}
    /* 
	* create the new attribute value 
	*/
    public function create()
    {
        if (!in_array('createCustomers', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $response = array();

        $this->form_validation->set_rules('customer_name', 'Customer name', 'trim|required');
        $this->form_validation->set_rules('user_type', 'User Type', 'trim|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|required');


        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'name' => $this->input->post('customer_name'),
                'active' => $this->input->post('active'),
                'user_type' => $this->input->post('user_type'),
            );

            $create = $this->Model_customers->create($data);
            if ($create == true) {
                $response['success'] = true;
                $response['messages'] = 'Succesfully created';
            } else {
                $response['success'] = false;
                $response['messages'] = 'Error in the database while creating the customer information';
            }
        } else {
            $response['success'] = false;
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }

        echo json_encode($response);
    }

    /* 
	* update the attribute value via attribute id 
	*/
    public function update()
    {
        if (!in_array('updateCustomers', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $response = array();

        // if ($id) {
            $this->form_validation->set_rules('edit_customer_name', 'Customer name', 'trim|required');
            $this->form_validation->set_rules('edit_active', 'Active', 'trim|required');
            $this->form_validation->set_rules('edit_user_type', 'User Type', 'trim|required');

            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

            if ($this->form_validation->run() == TRUE) {
                $data = array(
                    'name' => $this->input->post('edit_customer_name'),
                    'user_type' => $this->input->post('edit_user_type'),
                    'active' => $this->input->post('edit_active'),
                );
                $id = $this->input->post('customer_id');
                $update = $this->Model_customers->update($data, $id);
                if ($update == true) {
                    $response['success'] = true;
                    $response['messages'] = 'Succesfully updated';
                    
                    redirect('Controller_Customer/index');
                } else {
                    $response['success'] = false;
                    $response['messages'] = 'Error in the database while updated the customer information';
                }

            } else {
                $response['success'] = false;
                foreach ($_POST as $key => $value) {
                    $response['messages'][$key] = form_error($key);
                }
            }
        // } else {
        //     $response['success'] = false;
        //     $response['messages'] = 'Error please refresh the page again!!';
        // }

        echo json_encode($response);
    }

    /* 
	* remove the attribute value via attribute id 
	*/
    public function remove()
    {
        if (!in_array('deleteCustomers', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $customer_id = $this->input->post('customer_id');

        $response = array();
        if ($customer_id) {
            $delete = $this->Model_customers->remove($customer_id);
            if ($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed";
            } else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the customer information";
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }

        echo json_encode($response);
    }

    
}
