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
		// $this->render_template('add_transaction_view', $this->data);	

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

        public function update_transaction() {
            $id = $this->input->post('transaction_detail_id');
            $quantity_used = $this->input->post('quantity_used');
            $quantity_returned = $this->input->post('quantity_returned');

            $data = [
                'quantity_used' => $quantity_used,
                'quantity_returned' => $quantity_returned
            ];

            $this->Medicine_model->update_transaction_details($id, $data);
            redirect('MedicineController/view_transactions');
        }
    }
    
?>