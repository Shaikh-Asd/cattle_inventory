<?php   
class MedicineController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Medicine_model');
    }
    
    public function add_transaction_form() {
        $data['customers'] = $this->Medicine_model->get_customers();
        $data['medicines'] = $this->Medicine_model->get_medicines();
        $this->load->view('add_transaction_view', $data);
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
        $data['transactions'] = $this->Medicine_model->get_transactions();
        $this->load->view('transactions_view', $data);
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
            $data['customers'] = $this->Medicine_model->get_customers();
            $data['medicines'] = $this->Medicine_model->get_medicines();
            $data['transaction'] = $this->Medicine_model->get_transaction_by_id($transaction_id);
            $data['transaction_details'] = $this->Medicine_model->get_transaction_details($transaction_id);
            $this->load->view('edit_transaction_view', $data);
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
        
            $data['customer'] = $this->Medicine_model->get_customer_by_id($customer_id);
            $data['transactions'] = $this->Medicine_model->get_customer_transactions($customer_id, $start_date, $end_date);
        
            $this->load->view('customer_transactions_view', $data);
        }
        
        public function view_medicine_stock() {
            $data['medicines'] = $this->Medicine_model->get_medicine_stock();
            $this->load->view('medicine_stock_view', $data);
        }
        
    }
    
?>