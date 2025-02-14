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
   public function get_transaction_by_id($id) {
    return $this->db->get_where('medicine_transactions', ['id' => $id])->row();
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
        $this->db->select('medicine_transactions.id as transaction_id, customers.name as customer_name, 
            medicines.name as medicine_name, medicine_transaction_details.*');
        $this->db->from('medicine_transaction_details');
        $this->db->join('medicine_transactions', 'medicine_transactions.id = medicine_transaction_details.transaction_id');
        $this->db->join('medicines', 'medicines.id = medicine_transaction_details.medicine_id');
        $this->db->join('customers', 'customers.id = medicine_transactions.customer_id');
        return $this->db->get()->result();
    }

    // Update medicine usage and return details
    public function update_transaction_details($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('medicine_transaction_details', $data);
    }


}

?>