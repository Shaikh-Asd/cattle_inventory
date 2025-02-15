<?php 
class Medicine_model extends CI_Model {
    
    public function get_customers() {
        return $this->db->get('customers')->result();
    }

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
            medicines.name as medicine_name, 
            medicine_transaction_details.*,
            (medicine_transaction_details.quantity_given - (medicine_transaction_details.quantity_used + medicine_transaction_details.quantity_returned)) AS balance_quantity
        ');
        $this->db->from('medicine_transaction_details');
        $this->db->join('medicine_transactions', 'medicine_transactions.id = medicine_transaction_details.transaction_id');
        $this->db->join('medicines', 'medicines.id = medicine_transaction_details.medicine_id');
        $this->db->join('customers', 'customers.id = medicine_transactions.customer_id');
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
    
}

?>