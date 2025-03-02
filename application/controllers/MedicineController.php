<?php   
class MedicineController extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->not_logged_in();

		$this->data['page_title'] = 'Manage Transaction';

        $this->load->model('Medicine_model');
        $this->load->model('model_customers');
    }
    
    public function add_transaction_form() {
       
        $this->data['customers'] = $this->Medicine_model->get_customers();        
        $this->data['medicines'] = $this->Medicine_model->get_medicines();
		$this->render_template('add_transaction_view', $this->data);	

    }

    // public function add_transaction() {
    //     $data = array(
    //         'customer_id' => $this->input->post('customer_id'),
    //         'medicine_id' => $this->input->post('medicine_id'),
    //         'quantity_given' => $this->input->post('quantity_given'),
    //     );
    //     $this->Medicine_model->add_transaction($data);
    //     redirect('MedicineController/view_transactions');
    // }
    public function add_transaction() {
        $customer_id = $this->input->post('customer_id');
        $transaction_id = $this->Medicine_model->add_transaction($customer_id);

        $medicines = $this->input->post('medicine_id');
        $quantities = $this->input->post('quantity_given');

        $data = [];
        foreach ($medicines as $index => $medicine_id) {
            $data[] = [
                'transaction_id' => $transaction_id,
                'medicine_id' => $medicine_id,
                'quantity_given' => $quantities[$index]
            ];
            // Deduct stock
        $this->Medicine_model->update_stock($medicine_id, $quantities[$index], 'deduct');
        }

        $this->Medicine_model->add_transaction_details($data);
        redirect('MedicineController/view_transactions');
    }

    public function view_transactions() {
        $this->data['transactions'] = $this->Medicine_model->get_transactions();
        // print_r($this->data['transactions']);
        // die();

        $this->render_template('transactions_view', $this->data);
    }
    
//    public function update_transaction() {
//     $id = $this->input->post('transaction_id');
//     $quantity_used = $this->input->post('quantity_used');
//     $quantity_returned = $this->input->post('quantity_returned');

//     // Ensure used + returned does not exceed given quantity
//     $transaction = $this->Medicine_model->get_transaction_by_id($id);
//     if (($quantity_used + $quantity_returned) > $transaction->quantity_given) {
//         echo "Error: Used + Returned cannot be greater than Given!";
//         return;
//     }

//     $data = array(
//         'quantity_used' => $quantity_used,
//         'quantity_returned' => $quantity_returned
//     );

//     $this->Medicine_model->update_transaction($id, $data);
//     redirect('MedicineController/view_transactions');
// }

        public function check_stock($medicine_id, $quantity) {
            $this->db->select('stock');
            $this->db->from('medicines');
            $this->db->where('id', $medicine_id);
            $medicine = $this->db->get()->row();

            return ($medicine && $medicine->stock >= $quantity);
        }


        public function edit_transaction($transaction_id) {
            $this->data['customers'] = $this->Medicine_model->get_customers();
            $this->data['medicines'] = $this->Medicine_model->get_medicines();
            $this->data['transaction'] = $this->Medicine_model->get_transaction_by_id($transaction_id);
            $this->data['transaction_details'] = $this->Medicine_model->get_transaction_details($transaction_id);
            $this->render_template('edit_transaction_view', $this->data);
        }
        // public function update_transaction() {
        //     $id = $this->input->post('transaction_detail_id');
        //     $quantity_used = $this->input->post('quantity_used');
        //     $quantity_returned = $this->input->post('quantity_returned');

        //     $data = [
        //         'quantity_used' => $quantity_used,
        //         'quantity_returned' => $quantity_returned
        //     ];

        //     $this->Medicine_model->update_transaction_details($id, $data);
        //     redirect('MedicineController/view_transactions');
        // }

        public function update_transaction() {
            $transaction_id = $this->input->post('transaction_id');
            $detail_ids = $this->input->post('detail_id');
            $medicine_ids = $this->input->post('medicine_id');
            $quantities_given = $this->input->post('quantity_given');
            $quantities_used = $this->input->post('quantity_used');
            $quantities_returned = $this->input->post('quantity_returned');
        
            foreach ($detail_ids as $index => $detail_id) {
                $data = [
                    'quantity_used' => $quantities_used[$index],
                    'quantity_returned' => $quantities_returned[$index]
                ];
        
                $this->Medicine_model->update_transaction_details($detail_id, $data);
        
                // Add back returned stock
                if ($quantities_returned[$index] > 0) {
                    $this->Medicine_model->update_stock($medicine_ids[$index], $quantities_returned[$index], 'add');
                }
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
        
        public function view_medicine_stock() {
            $data['medicines'] = $this->Medicine_model->get_medicine_stock();
            $this->load->view('medicine_stock_view', $data);
        }

        public function get_transaction_details($transaction_id) {
            $this->load->model('Medicine_model');
            $transaction = $this->Medicine_model->get_single_transaction_by_id($transaction_id);
            $medicines = $this->Medicine_model->get_transaction_medicines($transaction_id);
        
            $response = [
                'customer_name' => $transaction->customer_name,
                'transaction_date' => $transaction->transaction_date,
                'medicines' => $medicines
            ];
        
            echo json_encode($response);
        }
        
        public function manage_customer_stock() {
            $this->load->model('Medicine_model');
            $data['customers'] = $this->Medicine_model->get_customers();
            $this->load->view('manage_customer_stock', $data);
        }
    
        public function get_customer_medicine_summary($customer_id) {
            $this->load->model('Medicine_model');
            $data['summary'] = $this->Medicine_model->get_customer_medicine_summary_details($customer_id);
            echo json_encode($data);
        }

        public function get_medicine_breakup($customer_id) {
            $this->load->model('Medicine_model');
            $data['breakup'] = $this->Medicine_model->get_medicine_breakup($customer_id);
            echo json_encode($data);
        }
    
        public function update_medicine_stock() {
            $customer_id = $this->input->post('customer_id');
            $medicine_updates = $this->input->post('medicine_updates');
            
            $this->load->model('Medicine_model');
            $this->Medicine_model->update_medicine_stock($customer_id, $medicine_updates);
            echo json_encode(['status' => 'success']);
        }

        public function customer_medicine_view() {
            $this->data['customers'] = $this->Medicine_model->get_customers();
            // print_r($data);die();
            $this->render_template('customer_medicine_view', $this->data);
        }
    
        public function get_customer_medicines($customer_id) {
            $medicines = $this->Medicine_model->get_customer_medicine_summary_details($customer_id);
            echo json_encode($medicines);
        }
    
        public function get_medicine_breakdown($customer_id, $medicine_id) {
            $breakdown = $this->Medicine_model->get_medicine_breakdown($customer_id, $medicine_id);
            echo json_encode($breakdown);
        }
    
        public function adjust_quantity() {
            $detail_id = $this->input->post('detail_id');
            $operation = $this->input->post('operation');
            $this->Medicine_model->adjust_quantity($detail_id, $operation);
        }

    // public function update_stock() {
    //     $updated_data = $this->input->post('updated_data');

    //     if (!empty($updated_data)) {
    //         foreach ($updated_data as $data) {
    //             $this->Medicine_model->update_breakdown_stock($data['detail_id'], $data['quantity_given']);
    //         }
    //     }

    //     echo json_encode(["status" => "success", "message" => "Stock updated successfully"]);
    // }
    public function update_stock()
    {
        $type = $this->input->post('type');
        $detail_id = $this->input->post('detail_id');
        $quantity_given = $this->input->post('quantity_given');
      
        // die();
        $this->Medicine_model->update_breakdown_stock($detail_id, $quantity_given);
       

        echo json_encode(["status" => "success", "message" => "Stock updated successfully"]);
    }
        
        
    }
    
?>