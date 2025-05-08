<?php 
/**
 * Medicine_model
 * 
 * Handles all database operations related to medicines including:
 * - Medicine inventory management
 * - Transaction processing
 * - Customer medicine records
 * - Stock management
 * 
 * @package     Cattle Inventory
 * @subpackage  Models
 * @category    Medicine Management
 * @author      Your Name
 * @link        http://your-website.com
 */
class Medicine_model extends CI_Model {
    /**
     * Constructor
     * 
     * Loads database connection
     */
    public function __construct() {
        // ... existing code ...
    }

    /**
     * Get Customers
     * 
     * Retrieves all customers who have medicine transactions
     * 
     * @return array List of customers
     */
    public function get_customers() {
        $this->db->where('active', 1); 
        $query = $this->db->get('customers'); 
        return $query->result();
    }

    /**
     * Get Customers
     * 
     * Retrieves all customers who have medicine transactions
     * 
     * @return array List of customers
     */
    public function get_customers2()
    {
        $this->db->where('active', 1);
        $this->db->where('user_type', '1');
        $query = $this->db->get('customers');
        return $query->result();
    }
    

    // public function get_customers($type): mixed {
    //     // return $this->db->get('customers')->result();
    //     $sql = "SELECT * FROM customers where active = 1 AND user_type = ? order by id desc";
    //     $query = $this->db->query($sql, array($type));
    //     return $query->result_array();
    // }

    /**
     * Get Medicines
     * 
     * Retrieves all active medicines from the database
     * 
     * @return array List of medicines
     */
    public function get_medicines() {
        $this->db->where('active', 1);
        $this->db->order_by('name', 'asc');
        $query = $this->db->get('medicines');
        return $query->result();
    }

    /**
     * Get Medicines List
     * 
     * Retrieves all active medicines from the database
     * 
     * @return array List of medicines
     */
    public function get_medicines_list() {
        $this->db->where('active', 1);
        $this->db->order_by('name', 'asc');
        $query = $this->db->get('medicines');
        return $query->result();
    }

    // public function add_transaction($data) {
    //     return $this->db->insert('medicine_transactions', $data);
    // }

    // public function get_transactions() {
    //     $this->db->select('medicine_transactions.*, medicines.name as medicine_name, customers.name as customer_name');
    //     $this->db->from('medicine_transactions');
    //     $this->db->join('medicines', 'medicines.id = medicine_transactions.medicine_id');
    //     $this->db->join('customers', 'customers.id = medicine_transactions.customer_id');
    //     return $this->db->get()->result();
    // }

    // public function update_transaction($id, $data) {
    //     $this->db->where('id', $id);
    //     return $this->db->update('medicine_transactions', $data);
    // }
    public function get_transaction_by_id($transaction_id) {
        return $this->db->get_where('medicine_transactions', ['id' => $transaction_id])->row();
    }
    public function get_total_medicine_given()
    {
        $sql = "SELECT transaction_id FROM medicine_transaction_details GROUP BY transaction_id";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    public function get_single_transaction_by_id($transaction_id) {
        $this->db->select('medicine_transactions.transaction_date, customers.name as customer_name');
        $this->db->from('medicine_transactions');
        $this->db->join('customers', 'customers.id = medicine_transactions.customer_id');
        $this->db->where('medicine_transactions.id', $transaction_id);
        return $this->db->get()->row();
    }

    public function get_transaction_medicines($transaction_id) {
        $this->db->select('medicines.name, medicine_transaction_details.quantity_given, medicine_transaction_details.quantity_used, medicine_transaction_details.quantity_returned');
        $this->db->from('medicine_transaction_details');
        $this->db->join('medicines', 'medicines.id = medicine_transaction_details.medicine_id');
        $this->db->where('medicine_transaction_details.transaction_id', $transaction_id);
        return $this->db->get()->result();
    }

    public function add_transaction($customer_id) {
        $this->db->insert('medicine_transactions', ['customer_id' => $customer_id]);
        return $this->db->insert_id();
    }

    // Insert medicine details for a transaction
    public function add_transaction_details($data) {
        $this->db->insert_batch('medicine_transaction_history', $data);
        return $this->db->insert_batch('medicine_transaction_details', $data);
    }

    /**
     * Get Transactions
     * 
     * Retrieves all medicine transactions with optional filtering
     * 
     * @param array $filters Optional filters for the query
     * @return array List of transactions
     */
    public function get_transactions($filters = array()) {
        $this->db->select('
            medicine_transactions.id as transaction_id, 
            medicine_transactions.transaction_date, 
            medicine_transactions.updated_at,
            customers.name as customer_name, 
            customers.id as customer_id, 
            GROUP_CONCAT(medicines.name SEPARATOR ", ") as medicine_names, 
            GROUP_CONCAT(medicine_transaction_details.quantity_given SEPARATOR ", ") as quantity_given,  
            GROUP_CONCAT(medicine_transaction_details.quantity_used SEPARATOR ", ") as quantity_used,    
            GROUP_CONCAT(medicine_transaction_details.quantity_returned SEPARATOR ", ") as quantity_returned, 
            GROUP_CONCAT((medicine_transaction_details.quantity_given - (medicine_transaction_details.quantity_used + medicine_transaction_details.quantity_returned)) SEPARATOR ", ") AS balance_quantity  -- Updated to show comma-separated balance quantities
        ');
        $this->db->from('medicine_transaction_details');
        $this->db->join('medicine_transactions', 'medicine_transactions.id = medicine_transaction_details.transaction_id');
        $this->db->join('medicines', 'medicines.id = medicine_transaction_details.medicine_id');
        $this->db->join('customers', 'customers.id = medicine_transactions.customer_id');
        $this->db->where('customers.active', 1); 
        $this->db->group_by('medicine_transactions.id, customers.name, medicine_transactions.transaction_date, medicine_transactions.updated_at');
        return $this->db->get()->result();
    }
    
    

    public function get_transaction_details($transaction_id) {
        $this->db->select('medicine_transaction_details.*, medicines.name as medicine_name');
        $this->db->from('medicine_transaction_details');
        $this->db->join('medicines', 'medicines.id = medicine_transaction_details.medicine_id');
        $this->db->where('medicine_transaction_details.transaction_id', $transaction_id);
        return $this->db->get()->result();
    }

    public function update_transaction($transaction_id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s'); // Set update time
        $this->db->where('id', $transaction_id);
        return $this->db->update('medicine_transactions', $data);
    }

    public function update_transaction_details_batch($transaction_details) {
        return $this->db->update_batch('medicine_transaction_details', $transaction_details, 'id');
    }

    public function get_customer_transactions($customer_id, $start_date = null, $end_date = null) {
        $this->db->select('
            medicine_transactions.id as transaction_id, 
            medicine_transaction_history.created_at, 
            medicine_transactions.updated_at,
            medicines.name as medicine_name, 
            medicine_transaction_history.quantity_given,
            medicine_transaction_history.quantity_used,
            medicine_transaction_history.quantity_returned,
            (medicine_transaction_history.quantity_given - (medicine_transaction_history.quantity_used + medicine_transaction_history.quantity_returned)) AS balance_quantity
        ');
        $this->db->from('medicine_transaction_history');
        $this->db->join('medicine_transactions', 'medicine_transactions.id = medicine_transaction_history.transaction_id');
        $this->db->join('medicines', 'medicines.id = medicine_transaction_history.medicine_id');
        $this->db->where('medicine_transactions.customer_id', $customer_id);
    
        if ($start_date && $end_date) {
            // Append time to include full-day range
            $start_datetime = $start_date . " 00:00:00";
            $end_datetime = $end_date . " 23:59:59";
    
            $this->db->where('medicine_transactions.transaction_date >=', $start_datetime);
            $this->db->where('medicine_transactions.transaction_date <=', $end_datetime);
        }
    
        return $this->db->get()->result();
    }
    

    public function get_customer_by_id($customer_id) {
        return $this->db->get_where('customers', ['id' => $customer_id])->row();
    }

    public function update_stock($medicine_id, $quantity, $operation = 'deduct')
    {
        if ($operation === 'deduct') {
            $this->db->set('stock', 'stock - ' . (int) $quantity, FALSE);
        } else {
            $this->db->set('stock', 'stock + ' . (int) $quantity, FALSE);
        }
        $this->db->where('id', $medicine_id);
        return $this->db->update('medicines');
    }
    
    public function get_low_stock_medicines($threshold = 5) {
        return $this->db->get_where('medicines', ['stock <' => $threshold])->result();
    }
    
    /**
     * Get Medicine Stock
     * 
     * Retrieves the current stock of all medicines
     * 
     * @return array List of medicines with stock information
     */
    public function get_medicine_stock() {
        $this->db->select('
            medicines.id,
            medicines.name,
            medicines.stock as current_stock,
            medicines.min_stock,
            medicines.max_stock,
            medicines.unit
        ');
        $this->db->from('medicines');
        $this->db->where('medicines.active', 1);
        $this->db->order_by('medicines.name', 'asc');
        return $this->db->get()->result();
    }
   
     // Update medicine usage and return details
     public function update_transaction_details($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('medicine_transaction_details', $data);
    }

    /**
     * Get Customer Medicine Summary
     * 
     * Retrieves a summary of all medicines given to a customer
     * 
     * @param int $customer_id The customer ID
     * @return array List of medicines with quantities
     */
    public function get_customer_medicine_summary($customer_id) {
        $this->db->select('
            medicine_transaction_details.medicine_id,
            medicines.name as medicine_name,
            medicines.stock as current_stock,
            SUM(medicine_transaction_details.quantity_given) as total_given
        ');
        $this->db->from('medicine_transaction_details');
        $this->db->join('medicine_transactions', 'medicine_transactions.id = medicine_transaction_details.transaction_id');
        $this->db->join('medicines', 'medicines.id = medicine_transaction_details.medicine_id');
        $this->db->where('medicine_transactions.customer_id', $customer_id);
        $this->db->group_by('medicine_transaction_details.medicine_id');
        return $this->db->get()->result();
    }

    public function get_medicine_breakup($customer_id) {
        $this->db->select('id, medicine_id, quantity_given');
        $this->db->from('medicine_transaction_details');
        $this->db->where('customer_id', $customer_id);
        return $this->db->get()->result();
    }

    /**
     * Update Medicine Stock
     * 
     * Updates the stock quantity for a medicine
     * 
     * @param int $medicine_id The medicine ID
     * @param int $new_stock The new stock quantity
     * @return bool True if successful, false otherwise
     */
    public function update_medicine_stock($medicine_id, $new_stock) {
        $this->db->where('id', $medicine_id);
        return $this->db->update('medicines', array('stock' => $new_stock));
    }

    public function get_customer_medicine_summary_details($customer_id) {
        $this->db->select('medicines.id, medicines.name, SUM(medicine_transaction_details.quantity_given) as total_given, medicine_transaction_details.transaction_id, medicine_transaction_details.medicine_id');
        $this->db->from('medicine_transaction_details');
        $this->db->join('medicines', 'medicines.id = medicine_transaction_details.medicine_id');
        $this->db->join('medicine_transactions', 'medicine_transactions.id = medicine_transaction_details.transaction_id');
        $this->db->where('medicine_transactions.customer_id', $customer_id);
        $this->db->group_by('medicines.id, medicines.name');
        return $this->db->get()->result();
    }

    public function get_medicine_breakdown($customer_id, $medicine_id) {
        $this->db->select('medicine_transaction_details.id as transaction_detail_id, medicines.name, medicine_transaction_details.quantity_given, medicine_transaction_details.quantity_used, medicine_transaction_details.quantity_returned, (medicine_transaction_details.quantity_given - (medicine_transaction_details.quantity_used + medicine_transaction_details.quantity_returned)) as balance, medicine_transactions.transaction_date');
        $this->db->from('medicine_transaction_details');
        $this->db->join('medicines', 'medicines.id = medicine_transaction_details.medicine_id');
        $this->db->join('medicine_transactions', 'medicine_transactions.id = medicine_transaction_details.transaction_id');
        $this->db->where('medicine_transactions.customer_id', $customer_id);
        $this->db->where('medicine_transaction_details.medicine_id', $medicine_id);
        return $this->db->get()->result();
    }

    public function adjust_quantity($detail_id, $operation) {
        if ($operation === 'add') {
            $this->db->set('quantity_used', 'quantity_used + 1', FALSE);
        } else {
            $this->db->set('quantity_used', 'quantity_used - 1', FALSE);
        }
        $this->db->where('id', $detail_id);
        $this->db->update('medicine_transaction_details');
    }

    // public function update_breakdown_stock() {
    //     // Get all transactions where quantity_used was modified
    //     $this->db->select('medicine_id, transaction_id, quantity_given, quantity_used, quantity_returned');
    //     $this->db->from('medicine_transaction_details');
    //     $modifiedTransactions = $this->db->get()->result();

    //     foreach ($modifiedTransactions as $transaction) {
    //         // Calculate the balance quantity
    //         $balance_quantity = $transaction->quantity_given - ($transaction->quantity_used + $transaction->quantity_returned);

    //         // Update the customer's medicine stock
    //         $this->db->set('quantity_used', $transaction->quantity_used);
    //         $this->db->set('quantity_returned', $transaction->quantity_returned);
    //         $this->db->where('medicine_id', $transaction->medicine_id);
    //         $this->db->where('transaction_id', $transaction->transaction_id);
    //         $this->db->update('medicine_transaction_details');

    //         // Update the main stock (reduce by used amount and add returned amount)
    //         $this->db->set('stock', 'stock - ' . $transaction->quantity_used . ' + ' . $transaction->quantity_returned, FALSE);
    //         $this->db->where('id', $transaction->medicine_id);
    //         $this->db->update('medicines');
    //     }
    // }

    public function update_breakdown_stock_out($transaction_id, $medicine_id, $quantity_given)
    {
        // Get the current transaction details
        $this->db->where('transaction_id', $transaction_id);
        $this->db->where('medicine_id', $medicine_id);
        $transaction = $this->db->get('medicine_transaction_history')->row();

        if ($transaction) {
            // Get the previous quantity given
            $previous_quantity_given = $transaction->quantity_given;
            
            // Calculate the difference in quantity (positive if increasing, negative if decreasing)
            $difference = $quantity_given - $previous_quantity_given;

            // Get current stock
            $this->db->where('id', $medicine_id);
            $medicine = $this->db->get('medicines')->row();
            $current_stock = $medicine->stock;

            // If we're giving out more medicine (positive difference)
            // Check if we have enough stock
            if ($difference > 0) {
                if ($difference > $current_stock) {
                    // Not enough stock available
                    return false;
                }
            }

            // Calculate new stock
            // If difference is positive, we're giving more medicine, so stock decreases
            // If difference is negative, we're giving less medicine, so stock increases
            $new_stock = $current_stock - $difference;
            
            if ($difference != 0) {
                // Ensure stock doesn't go below zero
                $new_stock = max(0, $new_stock);

                $this->db->set('stock', $new_stock);
                $this->db->where('id', $medicine_id);
                $this->db->update('medicines');

                // update medicine_transaction_history
                $this->db->set('quantity_given', $quantity_given);
                $this->db->where('transaction_id', $transaction_id);
                $this->db->where('medicine_id', $medicine_id);
                $this->db->update('medicine_transaction_history');
            }

            return true;
        }

        return false;
    }
    public function update_breakdown_stock($transaction_id, $medicine_id, $quantity_given) {
        // Get the current transaction details
        $this->db->where('transaction_id', $transaction_id);
        $this->db->where('medicine_id', $medicine_id);
        $transaction = $this->db->get('medicine_transaction_details')->row();
        // print_r($quantity_given);

        if ($transaction) {
            // Get the previous quantity given
            $previous_quantity_given = $transaction->quantity_given;
            // print_r($previous_quantity_given);
            // die();
            // Calculate the difference in quantity (positive if increasing, negative if decreasing)
            $difference = $quantity_given - $previous_quantity_given;
            
            // Get current stock
            $this->db->where('id', $medicine_id);
            $medicine = $this->db->get('medicines')->row();
            $current_stock = $medicine->stock;
            
            
            // Calculate new stock
            // If difference is positive, we're giving more medicine, so stock decreases
            // If difference is negative, we're giving less medicine, so stock increases
            $new_stock = $current_stock - $difference;
            // print_r($new_stock);
            // die();
            // Update the given quantity in medicine transaction
            $this->db->set('quantity_given', $quantity_given);
            $this->db->where('transaction_id', $transaction_id);
            $this->db->where('medicine_id', $medicine_id);
            $this->db->update('medicine_transaction_details');
            
            // print_r($difference);
            // die();
            // Update main stock in 'medicines' table if there is a difference
            if ($difference != 0) {
                // Ensure stock doesn't go below zero
                $new_stock = max(0, $new_stock);
                
                $this->db->set('stock', $new_stock);
                $this->db->where('id', $medicine_id);
                $this->db->update('medicines');
                
                // Record transaction history
                $this->db->insert('medicine_transaction_history', [
                    'transaction_id' => $transaction_id, 
                    'medicine_id' => $medicine_id, 
                    'quantity_given' => $difference,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Update medicine stock and transaction details
     * 
     * @param int $product_id The ID of the product to update
     * @param array $new_medicines Array of new medicine quantities [medicine_id => quantity]
     * @return bool True on success, false on failure
     */
    public function update($product_id, $new_medicines = []) {
        if (!$product_id) {
            log_message('error', 'Product ID is required for update');
            return false;
        }

        // Start transaction
        $this->db->trans_start();

        try {
            // Fetch old data for stock adjustment
            $old_data = $this->model_products->getProductData($product_id);
            if (!$old_data) {
                throw new Exception('Product not found');
            }

            $old_medicine_ids = explode(',', $old_data['medicine_id']);
            $old_quantities = explode(',', $old_data['qty']);

            // Build associative array: medicine_id => quantity
            $old_meds = [];
            foreach ($old_medicine_ids as $idx => $mid) {
                $old_meds[trim($mid)] = isset($old_quantities[$idx]) ? (int)$old_quantities[$idx] : 0;
            }

            // Process each medicine update
            foreach ($new_medicines as $mid => $new_qty) {
                $old_qty = isset($old_meds[$mid]) ? $old_meds[$mid] : 0;
                $existing_stock = $this->model_products->getMedicineStck($mid);
                $current_stock = isset($existing_stock['stock']) ? (int)$existing_stock['stock'] : 0;

                // Calculate new stock (remove old quantity, add new quantity)
                $new_stock = $current_stock - $old_qty + $new_qty;
                $new_stock = max(0, $new_stock); // Prevent negative stock

                // Update medicine stock
                $this->model_products->updateMedicinesStock($mid, $new_stock);
            }

            $this->db->trans_complete();
            return $this->db->trans_status();
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Error updating medicine stock: ' . $e->getMessage());
            return false;
        }
    }

    public function get_transaction_detail($detail_id) {
        return $this->db->get_where('medicine_transaction_details', ['id' => $detail_id])->row();
    }

    public function delete_transaction_detail($detail_id) {
        // Start transaction
        $this->db->trans_start();
        
        // Delete from medicine_transaction_details
        $this->db->where('id', $detail_id);
        $this->db->delete('medicine_transaction_details');
        
        // Delete from medicine_transaction_history
        $this->db->where('id', $detail_id);
        $this->db->delete('medicine_transaction_history');
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    public function get_medicine($medicine_id) {
        return $this->db->get_where('medicines', ['id' => $medicine_id])->row();
    }

    public function add_transaction_detail($data) {
        // Add to transaction details
        $this->db->insert('medicine_transaction_details', $data);
        
        // Also add to transaction history
        $this->db->insert('medicine_transaction_history', $data);
        
        return $this->db->insert_id();
    }
}

?>