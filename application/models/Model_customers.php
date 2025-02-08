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
    public function getCustomerData()
    {
        $sql = "SELECT * FROM customers";   
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
        $this->db->select('o.id, o.customer_name, o.created_at,  m.id, m.name');
        $this->db->from('orders o');
        $this->db->join('medicines m', 'FIND_IN_SET(m.id, o.medicine_id) > 0', 'inner');
        $this->db->where('o.customer_name', $customerName);
        
        $query = $this->db->get();
        
        return $query->result();
    }

}

