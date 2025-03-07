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
        $sql = "SELECT * FROM medicines where active = 1 order by id desc";   
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getAllMedicinesData()
    {
        $sql = "SELECT * FROM medicines order by id desc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getMedicineQuantity($id)
    {
        $sql = "SELECT qty FROM medicine_stock WHERE medicine_id = ?";
        $query = $this->db->query($sql, array($id));
        return $query->row_array();
    }

    public function create($data)
    {
      
        if ($data) {
            $this->db->where('name', $data['name']);
            $query = $this->db->get('medicines');

        if ($query->num_rows() > 0) {
            return false;
        }

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
        $this->db->select('*'); 
        $this->db->from('medicines');
        $this->db->order_by('stock', 'ASC');
        $query = $this->db->get(); 
        return $query->result();
    }

    // public function get_medicine_stock_by_id($medicine_id) {
    //     $this->db->where('medicine_id', $medicine_id);  
    //     $query = $this->db->get('medicine_stock');
    //     return $query->row(); 
    // }
    
}
