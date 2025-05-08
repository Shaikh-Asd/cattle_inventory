<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Transaction_history
 * 
 * Handles all transaction history related operations including:
 * - Viewing transaction history
 * - Fetching transaction data
 * - Viewing transaction details
 * - Managing customer transactions
 * 
 * @package     Cattle Inventory
 * @subpackage  Controllers
 * @category    Transaction Management
 * @author      Your Name
 * @link        http://your-website.com
 */
class Transaction_history extends Admin_Controller 
{
	public $data = array();

	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Transaction History';

		$this->load->model('Transaction_history_model');
		$this->load->model('model_customers');
		$this->load->model('Medicine_model');
		// $this->load->model('Transaction_details_model');
	}
	// public function index()
	// {
	// 	try {
	// 		if(!in_array('viewTransaction', $this->permission)) {
	// 			redirect('dashboard', 'refresh');
	// 		}

	// 		$this->render_template('transaction_history/index', $this->data);
	// 	} catch (Exception $e) {
	// 		log_message('error', 'Error in index: ' . $e->getMessage());
	// 		$this->session->set_flashdata('error', 'Failed to load transaction history page');
	// 		redirect('dashboard');
	// 	}
	// }
	public function index()
	{
		if (!in_array('viewTransaction', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$this->render_template('transaction_history/index', $this->data);
	}

	/**
	 * Fetch Transaction Data
	 * 
	 * Retrieves transaction data for datatable display
	 * 
	 * @return void
	 */
	public function fetchTransactionData()
	{
		try {
			$draw = $this->input->post('draw');
			$start = $this->input->post('start');
			$length = $this->input->post('length');
			$search = $this->input->post('search')['value'];
			
			$total_records = $this->Transaction_history_model->get_total_records();
			$filtered_records = $this->Transaction_history_model->get_filtered_records($search);
			$transactions = $this->Transaction_history_model->get_transactions($start, $length, $search);
			
			$data = array();
			foreach ($transactions as $transaction) {
				$type_label = $this->get_transaction_type_label($transaction['transaction_type']);
				
				$data[] = array(
					$transaction['id'],
					$type_label,
					$transaction['customer_name'],
					date('d M Y H:i', strtotime($transaction['created_at'])),
					'<a href="' . base_url('Transaction_history/view/' . $transaction['id']) . '" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> View</a>'
				);
			}
			
			$output = array(
				"draw" => intval($draw),
				"recordsTotal" => $total_records,
				"recordsFiltered" => $filtered_records,
				"data" => $data
			);
			
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($output));
		} catch (Exception $e) {
			log_message('error', 'Error in fetchTransactionData: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Failed to fetch transaction data']));
		}
	}

	/**
	 * Get Transaction Type Label
	 * 
	 * Returns formatted HTML label for transaction type
	 * 
	 * @param string $type The transaction type
	 * @return string HTML label
	 */
	private function get_transaction_type_label($type)
	{
		$labels = [
			'inward' => '<span class="label label-success">Inward</span>',
			'outward' => '<span class="label label-danger">Outward</span>',
			'adjustment' => '<span class="label label-warning">Adjustment</span>'
		];

		return $labels[$type] ?? '<span class="label label-default">' . $type . '</span>';
	}

	/**
	 * View Transaction
	 * 
	 * Displays details of a specific transaction
	 * 
	 * @param int $id The transaction ID
	 * @return void
	 */
	public function view($id)
	{
		try {
			if(!in_array('viewTransaction', $this->permission)) {
				redirect('dashboard', 'refresh');
			}

			if(!$id) {
				redirect('dashboard', 'refresh');
			}

			$transaction_data = $this->Transaction_history_model->get_transaction_by_id($id);
			$transaction_details = $this->Transaction_history_model->get_transaction_details($id);

			$this->data['transaction_data'] = $transaction_data;
			$this->data['transaction_details'] = $transaction_details;

			$this->render_template('transaction_history/view', $this->data);
		} catch (Exception $e) {
			log_message('error', 'Error in view: ' . $e->getMessage());
			$this->session->set_flashdata('error', 'Failed to load transaction details');
			redirect('Transaction_history');
		}
	}

	/**
	 * Customer Transactions
	 * 
	 * Displays transaction history for a specific customer
	 * 
	 * @param int $customer_id The customer ID
	 * @return void
	 */
	public function customer_transactions($customer_id)
	{
		
		$customer_data = $this->model_customers->getVendorDataById($customer_id);
		$transactions = $this->Transaction_history_model->get_customer_transactions($customer_id);

		// For each transaction, get its details
		foreach ($transactions as &$transaction) {
			$transaction['details'] = $this->Transaction_history_model->get_transaction_details($transaction['id']);
		}

		$this->data['customer_data'] = $customer_data;
		$this->data['transactions'] = $transactions;

		$this->render_template('transaction_history/customer_transactions', $this->data);
	}
} 