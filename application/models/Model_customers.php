<?php

class Model_customers extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // get the active atttributes data 
    public function getActiveCustomerData()
    {
        $sql = "SELECT * FROM customers WHERE active = ?";
        $query = $this->db->query($sql, array(1));
        return $query->result_array();
    }

    public function getCustomerDataById($id)
    {
        $sql = "SELECT * FROM customers WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        return $query->row_array();
    }

    /* get the attribute data */
    public function getCustomerData($type)
    {
        $sql = "SELECT * FROM customers where active = 1 AND user_type = ? order by id desc";   
        $query = $this->db->query($sql, array($type));
        return $query->result_array();
    }



    public function getAllCustomerData()
    {
        $sql = "SELECT * FROM customers order by id desc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    

    public function create($data)
    {
      
        if ($data) {
            $insert = $this->db->insert('customers', $data);
            return ($insert == true) ? true : false;
        }
    }

    public function update($data, $id)
    {
        if ($data && $id) {
            $this->db->where('id', $id);
            $update = $this->db->update('customers', $data);
            return ($update == true) ? true : false;
        }
    }

    public function remove($id)
    {
        if ($id) {
            $this->db->where('id', $id);
            $delete = $this->db->delete('customers');
            return ($delete == true) ? true : false;
        }
    }

    public function getUserMedicineStats($customerName)   
    {
        // $this->db->select('o.id, o.customer_name, o.created_at, m.id AS medicine_id, m.name, SUM(o.qty) AS total_quantity_ordered');
        // $this->db->from('orders o');
        // $this->db->join('medicines m', 'FIND_IN_SET(m.id, o.medicine_id) > 0', 'inner');
        // $this->db->where('o.customer_name', $customerName);
        // $this->db->group_by('m.id');
        // $query = $this->db->get();
        // return $query->result();
        // $customerName = 5;
        $this->db->select('o.id, o.transaction_date, m.id AS medicine_id, m.transaction_id,md.name AS medicine_name, SUM(m.quantity_given) AS total_quantity_ordered');
        $this->db->from('medicine_transactions o');
        $this->db->join('medicine_transaction_details m', 'FIND_IN_SET(m.transaction_id , o.id) > 0', 'inner');
        $this->db->join('medicines md', 'md.id = m.medicine_id', 'inner');
        $this->db->where('o.customer_id', $customerName);
        $this->db->group_by('m.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function getMostOrderedMedicineByCustomer()   
    {
        $this->db->select('o.id, o.customer_name, o.created_at, m.id AS medicine_id, m.name, SUM(o.quantity_ordered) AS total_quantity_ordered');
        $this->db->from('orders o');
        $this->db->join('medicines m', 'FIND_IN_SET(m.id, o.medicine_id) > 0', 'inner');
        $this->db->where('o.customer_name', $customerName);
        $this->db->group_by('m.id');
        $this->db->order_by('total_quantity_ordered', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();  // Return the single result (most ordered medicine)
    }

    public function get_top_customers_with_products() {
        // Query to get the top 5 customers with their ordered products
        $this->db->select('customers.id as customer_id, customers.name as customer_name, 
                           medicines.id as medicine_id, medicines.name as medicine_name, 
                           SUM(orders_item.qty) as total_ordered');
        $this->db->from('orders_item');
        $this->db->join('customers', 'customers.id = orders_item.customer_id');
        $this->db->join('medicines', 'medicines.id = orders_item.product_id');
        $this->db->group_by('orders_item.customer_id, orders_item.product_id');
        $this->db->order_by('total_ordered', 'DESC'); // Order by total quantity ordered
        $this->db->limit(5); // Get top 5 customers
        $query = $this->db->get();
        
        return $query->result(); // Return the result as an array of objects
    }
}

