<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Controller_Medicines extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->not_logged_in();

        $this->data['page_title'] = 'Medicines';

        $this->load->model('Model_medicines');
    }

    /* 
	* redirect to the index page 
	*/
    public function index()
    {
        if (!in_array('viewMedicines', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->render_template('medicines/index', $this->data);
    }

    public function getActiveMedicineData()
    {
        $sql = "SELECT * FROM medicines WHERE active = ?";
        $query = $this->db->query($sql, array(1));
        return $query->result_array();
    }

    // public function fetchMedicinesDataById($id)
    // {
    //     if ($id) {
    //         $data = $this->Model_medicines->getMedicinesData($id);
    //         echo json_encode($data);
    //     }
    // }
    public function fetchMedicinesDataById($id)
    {
        if ($id) {
            $data = $this->Model_medicines->getMedicinesDataById($id);
            echo json_encode($data);
        }
    }
    // * gets the attribute data from data and returns the attribute 
	
	public function fetchMedicinesData()
	{
		$result = array('data' => array());

		$data = $this->Model_medicines->getAllMedicinesData();

		foreach ($data as $key => $value) {


			// button
			$buttons = '
			<button type="button" class="btn btn-warning btn-sm" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>
			<button type="button" class="btn btn-danger btn-sm" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>
			';

			$status = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';
            $count = $key + 1;
			$result['data'][$key] = array(
				$count,
				$value['name'],

                $value['dead_stock'],
				$status,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}
    /* 
	* create the new attribute value 
	*/
    public function create()
    {
        if (!in_array('createMedicines', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $response = array();

        $this->form_validation->set_rules('medicine_name', 'Medicine name', 'trim|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|required');
        $this->form_validation->set_rules('dead_stock', 'Dead Stock', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'name' => $this->input->post('medicine_name'),
                'active' => $this->input->post('active'),
                'dead_stock' => $this->input->post('dead_stock'),
            );

            $create = $this->Model_medicines->create($data);
            if ($create == true) {
                $response['success'] = true;
                $response['messages'] = 'Succesfully created';
            } else {
                $response['success'] = false;
                $response['messages'] = 'Something went wrong or Medicine already exists';
            }
        } else {
            $response['success'] = false;
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }

        echo json_encode($response);
    }

    /* 
	* update the attribute value via attribute id 
	*/
    public function update($id)
    {
        if (!in_array('updateMedicines', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $response = array();

        if ($id) {
            $this->form_validation->set_rules('edit_medicine_name', 'Medicine name', 'trim|required');
            $this->form_validation->set_rules('edit_active', 'Active', 'trim|required');
            $this->form_validation->set_rules('edit_dead_stock', 'Dead Stock', 'trim|required');
            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

            if ($this->form_validation->run() == TRUE) {
                $data = array(
                    'name' => $this->input->post('edit_medicine_name'),
                    'active' => $this->input->post('edit_active'),
                    'dead_stock' => $this->input->post('edit_dead_stock'),
                );

                $update = $this->Model_medicines->update($data, $id);
                if ($update == true) {
                    $response['success'] = true;
                    $response['messages'] = 'Succesfully updated';
                } else {
                    $response['success'] = false;
                    $response['messages'] = 'Error in the database while updated the customer information';
                }
            } else {
                $response['success'] = false;
                foreach ($_POST as $key => $value) {
                    $response['messages'][$key] = form_error($key);
                }
            }
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error please refresh the page again!!';
        }

        echo json_encode($response);
    }

    /* 
	* remove the attribute value via attribute id 
	*/
    public function remove()
    {
        if (!in_array('deleteMedicines', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $medicine_id = $this->input->post('medicine_id');

        $response = array();
        if ($medicine_id) {
            $delete = $this->Model_medicines->remove($medicine_id);
            if ($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed";
            } else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the medicine information";
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }

        echo json_encode($response);
    }

    public function getMedicineQuantitys()
    {
        $id = $this->input->post('id');
        $quantity = $this->Model_medicines->getMedicineQuantity($id);
        echo json_encode($quantity);
    }
}
