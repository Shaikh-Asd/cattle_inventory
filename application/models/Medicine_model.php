<?php 
class Medicine_model extends CI_Model {
    
    public function get_customers() {
        return $this->db->get('customers')->result();
    }

    // public function get_customers($type): mixed {
    //     // return $this->db->get('customers')->result();
    //     $sql = "SELECT * FROM customers where active = 1 AND user_type = ? order by id desc";
    //     $query = $this->db->query($sql, array($type));
    //     return $query->result_array();
    // }

    public function get_medicines() {
        return $this->db->get('medicines')->result();
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
        return $this->db->insert_batch('medicine_transaction_details', $data);
    }

    // Get all transactions with medicine details
    public function get_transactions() {
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
            medicine_transactions.transaction_date, 
            medicine_transactions.updated_at,
            medicines.name as medicine_name, 
            medicine_transaction_details.quantity_given,
            medicine_transaction_details.quantity_used,
            medicine_transaction_details.quantity_returned,
            (medicine_transaction_details.quantity_given - (medicine_transaction_details.quantity_used + medicine_transaction_details.quantity_returned)) AS balance_quantity
        ');
        $this->db->from('medicine_transaction_details');
        $this->db->join('medicine_transactions', 'medicine_transactions.id = medicine_transaction_details.transaction_id');
        $this->db->join('medicines', 'medicines.id = medicine_transaction_details.medicine_id');
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
    
    public function update_stock($medicine_id, $quantity, $operation = 'deduct') {
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
    
    public function get_medicine_stock() {
        $this->db->select('name, stock');
        $this->db->from('medicines');
        return $this->db->get()->result();
    }
   
     // Update medicine usage and return details
     public function update_transaction_details($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('medicine_transaction_details', $data);
    }

    public function get_customer_medicine_summary($customer_id) {
        $this->db->select('medicine_id, SUM(quantity_given) as total_given');
        $this->db->from('medicine_transaction_details');
        $this->db->where('customer_id', $customer_id);
        $this->db->group_by('medicine_id');
        return $this->db->get()->result();
    }

    public function get_medicine_breakup($customer_id) {
        $this->db->select('id, medicine_id, quantity_given');
        $this->db->from('medicine_transaction_details');
        $this->db->where('customer_id', $customer_id);
        return $this->db->get()->result();
    }

    public function update_medicine_stock($customer_id, $medicine_updates) {
        foreach ($medicine_updates as $update) {
            $this->db->where('id', $update['transaction_id']);
            $this->db->update('medicine_transaction_details', ['quantity_given' => $update['new_quantity']]);
            
            // Update stock accordingly
            $this->db->set('stock', 'stock - '.((int)$update['old_quantity'] - (int)$update['new_quantity']), FALSE);
            $this->db->where('id', $update['medicine_id']);
            $this->db->update('medicines');
        }
    }

    public function get_customer_medicine_summary_details($customer_id) {
        $this->db->select('medicines.id, medicines.name, SUM(medicine_transaction_details.quantity_given) as total_given');
        $this->db->from('medicine_transaction_details');
        $this->db->join('medicines', 'medicines.id = medicine_transaction_details.medicine_id');
        $this->db->join('medicine_transactions', 'medicine_transactions.id = medicine_transaction_details.transaction_id');
        $this->db->where('medicine_transactions.customer_id', $customer_id);
        $this->db->group_by('medicines.id, medicines.name');
        return $this->db->get()->result();
    }

    public function get_medicine_breakdown($customer_id, $medicine_id) {
        $this->db->select('medicine_transaction_details.id as transaction_detail_id, medicines.name, medicine_transaction_details.quantity_given, medicine_transaction_details.quantity_used, medicine_transaction_details.quantity_returned, (medicine_transaction_details.quantity_given - (medicine_transaction_details.quantity_used + medicine_transaction_details.quantity_returned)) as balance');
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

    public function update_breakdown_stock($detail_id, $quantity_used) {
        $this->db->where('id', $detail_id);
        $transaction = $this->db->get('medicine_transaction_details')->row();

        if ($transaction) {
            // Calculate the difference in quantity
            $previous_quantity_given = $transaction->quantity_given; // Get the previous quantity given

            // Update the given quantity in medicine transaction
            $this->db->set('quantity_given', $quantity_used);
            $this->db->where('id', $detail_id);
            $this->db->update('medicine_transaction_details');

            // Calculate the difference
            $difference = $quantity_used - $previous_quantity_given; // Find the difference in quantity

            // get stock then minus the difference
            $stock = $this->db->get_where('medicines', ['id' => $transaction->medicine_id])->row()->stock;
            // print_r($stock);
            // print_r($difference);

            // die();
            $new_stock = $stock - $difference;
            // print_r($new_stock);
            // die();
            // Update main stock in 'medicines' table
            if ($difference != 0) { // Only update stock if there is a difference
                $this->db->set('stock', $new_stock, FALSE); // Adjust stock based on the difference
                $this->db->where('id', $transaction->medicine_id);
                $this->db->update('medicines');
            }
        }
    }
    
    
}

?>