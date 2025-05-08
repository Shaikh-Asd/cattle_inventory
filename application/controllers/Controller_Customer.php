<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller_Customer
 * 
 * Handles all customer-related operations including:
 * - Managing customers and vendors
 * - Creating and updating customer information
 * - Processing customer data
 * - Managing customer status
 * 
 * @package     Cattle Inventory
 * @subpackage  Controllers
 * @category    Customer Management
 * @author      Your Name
 * @link        http://your-website.com
 */
class Controller_Customer extends Admin_Controller
{
    /**
     * @var Model_customers
     */
    // protected $Model_customers;

    /**
     * @var array
     */
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

        $this->data['page_title'] = 'Customers';
        $this->load->model('Model_customers');
        $this->Model_customers = $this->Model_customers;
    }

    /**
     * Index
     * 
     * Displays the customers management page
     * 
     * @return void
     */
    public function index()
    {
        try {
            if (!in_array('viewCustomers', $this->permission)) {
                redirect('dashboard', 'refresh');
            }

            $this->render_template('customers/index', $this->data);
        } catch (Exception $e) {
            log_message('error', 'Error in index: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Failed to load customers page');
            redirect('dashboard');
        }
    }

    /**
     * Fetch Customer Data By ID
     * 
     * Retrieves customer data by ID
     * 
     * @param int $id The customer ID
     * @return void
     */
    public function fetchCustomerDataById($id)
    {
        try {
            if ($id) {
                $data = $this->Model_customers->getCustomerDataById($id);
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));
            }
        } catch (Exception $e) {
            log_message('error', 'Error in fetchCustomerDataById: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Failed to fetch customer data']));
        }
    }

    /**
     * Fetch Customer Data
     * 
     * Retrieves all customer data for datatable display
     * 
     * @return void
     */
    public function fetchCustomerData()
    {
        try {
            $result = array('data' => array());
            $data = $this->Model_customers->getAllCustomerData();

            foreach ($data as $key => $value) {
                // Generate action buttons
                $buttons = '<button type="button" class="btn btn-warning btn-sm" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';

                // Get status labels
                $status = ($value['active'] == 1) 
                    ? '<span class="label label-success">Active</span>' 
                    : '<span class="label label-warning">Inactive</span>';
                
                $user_type = ($value['user_type'] == 1) 
                    ? '<span class="label label-success">User</span>' 
                    : '<span class="label label-warning">Vendor</span>';

                $count = $key + 1;
                $result['data'][$key] = array(
                    $count,
                    $value['id'],
                    $value['name'],
                    $status,
                    $user_type,
                    $buttons
                );
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
        } catch (Exception $e) {
            log_message('error', 'Error in fetchCustomerData: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Failed to fetch customer data']));
        }
    }

    /**
     * Create Customer
     * 
     * Handles customer creation with validation
     * 
     * @return void
     */
    public function create()
    {
        try {
            if (!in_array('createCustomers', $this->permission)) {
                redirect('dashboard', 'refresh');
            }

            $response = array();

            $this->form_validation->set_rules('customer_name', 'Customer name', 'trim|required');
            $this->form_validation->set_rules('user_type', 'User Type', 'trim|required');
            $this->form_validation->set_rules('active', 'Active', 'trim|required');
            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

            if ($this->form_validation->run() == TRUE) {
                $customer_name = $this->input->post('customer_name');
                
                // Check if customer name already exists
                $exists = $this->Model_customers->checkCustomerExists($customer_name);
                
                if ($exists) {
                    $response['success'] = false;
                    $response['messages'] = 'Customer name already exists';
                } else {
                    $data = array(
                        'name' => $customer_name,
                        'active' => $this->input->post('active'),
                        'user_type' => $this->input->post('user_type'),
                    );

                    $create = $this->Model_customers->create($data);
                    if ($create) {
                        $response['success'] = true;
                        $response['messages'] = 'Successfully created';
                    } else {
                        throw new Exception('Error in the database while creating the customer information');
                    }
                }
            } else {
                $response['success'] = false;
                foreach ($_POST as $key => $value) {
                    $response['messages'][$key] = form_error($key);
                }
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        } catch (Exception $e) {
            log_message('error', 'Error in create: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode([
                    'success' => false,
                    'messages' => 'Error occurred while creating customer'
                ]));
        }
    }

    /**
     * Update Customer
     * 
     * Handles customer updates with validation
     * 
     * @return void
     */
    public function update()
    {
        try {
            $response = array();

            $this->form_validation->set_rules('edit_customer_name', 'Customer name', 'trim|required');
            $this->form_validation->set_rules('edit_active', 'Active', 'trim|required');
            $this->form_validation->set_rules('edit_user_type', 'User Type', 'trim|required');

            if ($this->form_validation->run() == TRUE) {
                $customer_name = $this->input->post('edit_customer_name');
                $customer_id = $this->input->post('customer_id');
                
                // Get current customer data to check if name is being changed
                $current_customer = $this->Model_customers->getCustomerDataById($customer_id);
                
                // Only check for duplicates if the name is being changed
                if ($current_customer['name'] != $customer_name) {
                    $exists = $this->Model_customers->checkCustomerExistsExceptThis($customer_name, $customer_id);
                    
                    if ($exists) {
                        $response['success'] = false;
                        $response['messages'] = 'Customer name already exists';
                        $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode($response));
                        return;
                    }
                }
                
                $data = array(
                    'name' => $customer_name,
                    'user_type' => $this->input->post('edit_user_type'),
                    'active' => $this->input->post('edit_active'),
                );
                
                $update = $this->Model_customers->update($data, $customer_id);
                
                if ($update) {
                    $response['success'] = true;
                    $response['messages'] = 'Successfully updated';
                } else {
                    throw new Exception('Error in the database while updating');
                }
            } else {
                $response['success'] = false;
                $response['messages'] = validation_errors();
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        } catch (Exception $e) {
            log_message('error', 'Error in update: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode([
                    'success' => false,
                    'messages' => 'Error occurred while updating customer'
                ]));
        }
    }

    /**
     * Remove Customer
     * 
     * Handles customer deletion
     * 
     * @return void
     */
    public function remove()
    {
        try {
            if (!in_array('deleteCustomers', $this->permission)) {
                redirect('dashboard', 'refresh');
            }

            $customer_id = $this->input->post('customer_id');
            $response = array();

            if ($customer_id) {
                $delete = $this->Model_customers->remove($customer_id);
                if ($delete) {
                    $response['success'] = true;
                    $response['messages'] = "Successfully removed";
                } else {
                    throw new Exception('Error in the database while removing the customer information');
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
                ->set_output(json_encode([
                    'success' => false,
                    'messages' => 'Error occurred while removing customer'
                ]));
        }
    }
}
