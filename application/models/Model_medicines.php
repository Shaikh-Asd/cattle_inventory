<?php

class Model_medicines extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // get the active atttributes data 
    public function getActiveMedicinesData()
    {
        $sql = "SELECT * FROM medicines WHERE active = ?";
        $query = $this->db->query($sql, array(1));
        return $query->result_array();
    }
    public function getMedicinesDataById($id)
    {
        $sql = "SELECT * FROM medicines WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        return $query->row_array();
    }
    /* get the attribute data */
    public function getMedicinesData()
    {
        $sql = "SELECT * FROM medicines where active = 1";   
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    

    public function create($data)
    {
      
        if ($data) {
            $insert = $this->db->insert('medicines', $data);
            return ($insert == true) ? true : false;
        }
    }

    public function update($data, $id)
    {
        if ($data && $id) {
            $this->db->where('id', $id);
            $update = $this->db->update('medicines', $data);
            return ($update == true) ? true : false;
        }
    }

    public function remove($id)
    {
        if ($id) {
            $this->db->where('id', $id);
            $delete = $this->db->delete('medicines');
            return ($delete == true) ? true : false;
        }
    }

    public function get_medicine_stock() {
        $this->db->select('medicine_stock.qty, medicines.name'); 
        $this->db->from('medicine_stock');
        $this->db->join('medicines', 'medicines.id = medicine_stock.medicine_id'); 
        $query = $this->db->get(); 
        return $query->result();
    }

    // public function get_medicine_stock_by_id($medicine_id) {
    //     $this->db->where('medicine_id', $medicine_id);  
    //     $query = $this->db->get('medicine_stock');
    //     return $query->row(); 
    // }
}
