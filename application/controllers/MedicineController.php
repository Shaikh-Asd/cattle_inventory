<?php   
/**
 * MedicineController
 * 
 * Handles all medicine-related operations including:
 * - Adding new medicines
 * - Managing medicine inventory
 * - Processing medicine transactions
 * - Viewing transaction history
 * 
 * @package     Cattle Inventory
 * @subpackage  Controllers
 * @category    Medicine Management
 * @author      Your Name
 * @link        http://your-website.com
 */
class MedicineController extends Admin_Controller {

    
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();

        $this->data['page_title'] = 'Manage Outward Medicine';

        $this->load->model('Medicine_model');
        $this->load->model('model_customers');
    }
    
    /**
     * Add Transaction Form
     * 
     * Displays the form for adding new medicine transactions
     * 
     * @return void
     */
    public function add_transaction_form() {
        try {
            $this->data['customers'] = $this->Medicine_model->get_customers2();        
            $this->data['medicines'] = $this->Medicine_model->get_medicines();
            $this->render_template('add_transaction_view', $this->data);
        } catch (Exception $e) {
            log_message('error', 'Error in add_transaction_form: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Failed to load transaction form. Please try again.');
            redirect('dashboard');
        }
    }

    /**
     * Add Transaction
     * 
     * Processes the addition of a new medicine transaction
     * 
     * @return void
     */
    public function add_transaction() {
        try {
            $customer_id = $this->input->post('customer_id');
            if (!$customer_id) {
                throw new Exception('Customer ID is required');
            }

            $transaction_id = $this->Medicine_model->add_transaction($customer_id);
            if (!$transaction_id) {
                throw new Exception('Failed to create transaction');
            }

            $medicines = $this->input->post('medicine_id');
            $quantities = $this->input->post('quantity_given');

            if (empty($medicines) || empty($quantities)) {
                throw new Exception('Medicine details are required');
            }

            $data = [];
            foreach ($medicines as $index => $medicine_id) {
                if (!isset($quantities[$index]) || $quantities[$index] <= 0) {
                    continue;
                }

                $data[] = [
                    'transaction_id' => $transaction_id,
                    'medicine_id' => $medicine_id,
                    'quantity_given' => $quantities[$index]
                ];

                // Deduct stock
                $this->Medicine_model->update_stock($medicine_id, $quantities[$index], 'deduct');
            }

            if (empty($data)) {
                throw new Exception('No valid medicine quantities provided');
            }

            $this->Medicine_model->add_transaction_details($data);
            $this->session->set_flashdata('success', 'Transaction added successfully');
        } catch (Exception $e) {
            log_message('error', 'Error in add_transaction: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Failed to add transaction: ' . $e->getMessage());
        }

        redirect('MedicineController/customer_medicine_view');
    }

    /**
     * View Transactions
     * 
     * Displays all medicine transactions with filtering and pagination
     * 
     * @return void
     */
    public function view_transactions() {
        try {
            $this->data['transactions'] = $this->Medicine_model->get_transactions();
            $this->render_template('transactions_view', $this->data);
        } catch (Exception $e) {
            log_message('error', 'Error in view_transactions: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Failed to load transactions. Please try again.');
            redirect('dashboard');
        }
    }
    
    /**
     * Check Stock
     * 
     * Verifies if sufficient stock is available for a medicine
     * 
     * @param int $medicine_id The ID of the medicine
     * @param int $quantity The quantity to check
     * @return bool True if sufficient stock is available
     */
    public function check_stock($medicine_id, $quantity) {
        try {
            $this->db->select('stock');
            $this->db->from('medicines');
            $this->db->where('id', $medicine_id);
            $medicine = $this->db->get()->row();

            return ($medicine && $medicine->stock >= $quantity);
        } catch (Exception $e) {
            log_message('error', 'Error in check_stock: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Edit Transaction
     * 
     * Displays the form for editing an existing transaction
     * 
     * @param int $transaction_id The ID of the transaction to edit
     * @return void
     */
    public function edit_transaction($transaction_id) {
        try {
            $this->data['customers'] = $this->Medicine_model->get_customers();
            $this->data['medicines'] = $this->Medicine_model->get_medicines();
            $this->data['transaction'] = $this->Medicine_model->get_transaction_by_id($transaction_id);
            $this->data['transaction_details'] = $this->Medicine_model->get_transaction_details($transaction_id);
            $this->render_template('edit_transaction_view', $this->data);
        } catch (Exception $e) {
            log_message('error', 'Error in edit_transaction: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Failed to load transaction for editing. Please try again.');
            redirect('MedicineController/view_transactions');
        }
    }

    /**
     * Update Transaction
     * 
     * Processes the update of an existing transaction
     * 
     * @return void
     */
    public function update_transaction() {
        try {
            $transaction_id = $this->input->post('transaction_id');
            if (empty($transaction_id)) {
                throw new Exception('Transaction ID is required');
            }

            $detail_ids = $this->input->post('detail_id');
            $medicine_ids = $this->input->post('medicine_id');
            $quantities_given = $this->input->post('quantity_given');
            $removed_detail_ids = $this->input->post('removed_detail_ids');

            // Start transaction
            $this->db->trans_start();

            // Handle removed rows first
            if (!empty($removed_detail_ids)) {
                $removed_ids = explode(',', $removed_detail_ids);
                foreach ($removed_ids as $detail_id) {
                    $detail = $this->Medicine_model->get_transaction_detail($detail_id);
                    if ($detail) {
                        $this->Medicine_model->update_breakdown_stock_out(
                            $transaction_id,
                            $detail->medicine_id,
                            0
                        );
                        $this->Medicine_model->delete_transaction_detail($detail_id);
                    }
                }
            }

            // Process each medicine in the transaction
            if ($detail_ids) {
                foreach ($detail_ids as $index => $detail_id) {
                    $medicine_id = $medicine_ids[$index];
                    $quantity = $quantities_given[$index];

                    if ($detail_id === 'new') {
                        // Check stock for new items
                        $medicine = $this->Medicine_model->get_medicine($medicine_id);
                        if ($medicine && $medicine->stock >= $quantity) {
                            $data = [
                                'transaction_id' => $transaction_id,
                                'medicine_id' => $medicine_id,
                                'quantity_given' => $quantity
                            ];
                            $this->Medicine_model->add_transaction_detail($data);
                            $this->Medicine_model->update_stock($medicine_id, $quantity, 'deduct');
                        }
                    } else {
                        $data = ['quantity_given' => $quantity];
                        $this->Medicine_model->update_transaction_details($detail_id, $data);
                        $this->Medicine_model->update_breakdown_stock_out(
                            $transaction_id,
                            $medicine_id,
                            $quantity
                        );
                    }
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction update failed');
            }

            $this->session->set_flashdata('success', 'Transaction updated successfully');
        } catch (Exception $e) {
            log_message('error', 'Error in update_transaction: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Failed to update transaction: ' . $e->getMessage());
        }

        redirect('MedicineController/view_transactions');
    }
        
        public function customer_transactions($customer_id) {
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
        
            $this->data['customer'] = $this->Medicine_model->get_customer_by_id($customer_id);
            $this->data['transactions'] = $this->Medicine_model->get_customer_transactions($customer_id, $start_date, $end_date);
        
            $this->render_template('customer_transactions_view', $this->data);
        }
        
    /**
     * View Medicine Stock
     * 
     * Displays the current stock of all medicines
     * 
     * @return void
     */
    public function view_medicine_stock() {
        try {
            $this->data['medicines'] = $this->Medicine_model->get_medicine_stock();
            $this->render_template('medicines/stock', $this->data);
        } catch (Exception $e) {
            log_message('error', 'Error in view_medicine_stock: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Failed to load medicine stock data');
            redirect('dashboard');
        }
    }

    /**
     * Get Transaction Details
     * 
     * Retrieves and returns transaction details in JSON format
     * 
     * @param int $transaction_id The ID of the transaction
     * @return void
     */
    public function get_transaction_details($transaction_id) {
        try {
            $transaction = $this->Medicine_model->get_single_transaction_by_id($transaction_id);
            $medicines = $this->Medicine_model->get_transaction_medicines($transaction_id);
            
            $response = [
                'customer_name' => $transaction->customer_name,
                'transaction_date' => $transaction->transaction_date,
                'medicines' => $medicines
            ];
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        } catch (Exception $e) {
            log_message('error', 'Error in get_transaction_details: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Failed to retrieve transaction details']));
        }
    }
    
    /**
     * Manage Customer Stock
     * 
     * Displays the customer stock management interface
     * 
     * @return void
     */
    public function manage_customer_stock() {
        try {
            $this->data['customers'] = $this->Medicine_model->get_customers();
            $this->render_template('manage_customer_stock', $this->data);
        } catch (Exception $e) {
            log_message('error', 'Error in manage_customer_stock: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Failed to load customer stock management. Please try again.');
            redirect('dashboard');
        }
    }

    /**
     * Get Customer Medicine Summary
     * 
     * Retrieves and returns medicine summary for a customer in JSON format
     * 
     * @param int $customer_id The ID of the customer
     * @return void
     */
    public function get_customer_medicine_summary($customer_id) {
        try {
            $summary = $this->Medicine_model->get_customer_medicine_summary_details($customer_id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['summary' => $summary]));
        } catch (Exception $e) {
            log_message('error', 'Error in get_customer_medicine_summary: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Failed to retrieve medicine summary']));
        }
    }

    /**
     * Get Medicine Breakup
     * 
     * Retrieves and returns medicine breakup details in JSON format
     * 
     * @param int $customer_id The ID of the customer
     * @return void
     */
    public function get_medicine_breakup($customer_id) {
        try {
            $breakup = $this->Medicine_model->get_medicine_breakup($customer_id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['breakup' => $breakup]));
        } catch (Exception $e) {
            log_message('error', 'Error in get_medicine_breakup: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Failed to retrieve medicine breakup']));
        }
    }
    
    /**
     * Update Medicine Stock
     * 
     * Updates medicine stock for a customer
     * 
     * @return void
     */
    public function update_medicine_stock() {
        try {
            $customer_id = $this->input->post('customer_id');
            $medicine_updates = $this->input->post('medicine_updates');
            
            if (empty($customer_id) || empty($medicine_updates)) {
                throw new Exception('Customer ID and medicine updates are required');
            }
            
            $result = $this->Medicine_model->update_medicine_stock($customer_id, $medicine_updates);
            
            if ($result) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'success']));
            } else {
                throw new Exception('Failed to update medicine stock');
            }
        } catch (Exception $e) {
            log_message('error', 'Error in update_medicine_stock: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Failed to update medicine stock']));
        }
    }

    /**
     * Customer Medicine View
     * 
     * Displays medicine transactions for a specific customer
     * 
     * @return void
     */
    public function customer_medicine_view() {
        $this->data['customers'] = $this->Medicine_model->get_customers2();
        // print_r($data);die();
        $this->render_template('customer_medicine_view', $this->data);
    }
    
    public function get_customer_medicines($customer_id) {
        $medicines = $this->Medicine_model->get_customer_medicine_summary_details($customer_id);
        echo json_encode($medicines);
    }
    
    public function get_medicine_breakdown($customer_id, $medicine_id) {
        try {
            if (empty($customer_id) || empty($medicine_id)) {
                throw new Exception('Customer ID and Medicine ID are required');
            }

            $breakdown = $this->Medicine_model->get_medicine_breakdown($customer_id, $medicine_id);
            
            if ($breakdown === false) {
                throw new Exception('Failed to retrieve medicine breakdown');
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($breakdown));
        } catch (Exception $e) {
            log_message('error', 'Error in get_medicine_breakdown: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $e->getMessage()]));
        }
    }
    
    public function adjust_quantity() {
        $detail_id = $this->input->post('detail_id');
        $operation = $this->input->post('operation');
        $this->Medicine_model->adjust_quantity($detail_id, $operation);
    }

    public function update_stock()
    {
        // Get the transaction ID, medicine ID, and quantity from the POST request
        $transaction_id = $this->input->post('transaction_id');
        $medicine_id = $this->input->post('medicine_id');
        $quantity_given = $this->input->post('quantity_given');
        
        // Validate the input data
        if (empty($transaction_id) || empty($medicine_id) || !isset($quantity_given)) {
            echo json_encode(["status" => "error", "message" => "Missing required parameters"]);
            return;
        }
        
        // Update the stock in the database
        $result = $this->Medicine_model->update_breakdown_stock($transaction_id, $medicine_id, $quantity_given);
        
        // Return success response
        if ($result) {
            echo json_encode(["status" => "success", "message" => "Stock updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update stock"]);
        }
    }
        
        
    }
    
?>