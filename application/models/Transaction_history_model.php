<?php 

/**
 * Transaction_history_model
 * 
 * Handles all database operations related to transaction history including:
 * - Managing transaction records and details
 * - CRUD operations for transactions
 * - Transaction reporting and analytics
 * 
 * @package     Cattle Inventory
 * @subpackage  Models
 * @category    Transaction Management
 * @author      Your Name
 * @link        http://your-website.com
 */
class Transaction_history_model extends CI_Model
{
	/**
	 * Constructor
	 * 
	 * Initializes the model
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Transaction_history_model');
	}

	/**
	 * Add Transaction
	 * 
	 * Creates a new transaction record
	 * 
	 * @param array $data Transaction data
	 * @return int|bool Transaction ID if successful, false otherwise
	 */
	public function add_transaction($data)
	{
		if ($data) {
			if ($this->db->insert('transaction_history', $data)) {
				return $this->db->insert_id();
			}
		}
		return false;
	}

	/**
	 * Add Transaction Details
	 * 
	 * Creates transaction details record
	 * 
	 * @param array $data Transaction details data
	 * @return bool
	 */
	public function add_transaction_details($data)
	{
		if ($data) {
			return $this->db->insert('transaction_details', $data);
		}
		return false;
	}

	/**
	 * Get Transaction History
	 * 
	 * Retrieves transaction history with pagination
	 * 
	 * @param int|null $limit Number of records to return
	 * @param int|null $start Starting position
	 * @return array
	 */
	public function get_transaction_history($limit = null, $start = null)
	{
		$this->db->select('
			transaction_history.id,
			transaction_history.transaction_type,
			transaction_history.reference_id,
			transaction_history.customer_id,
			transaction_history.created_at,
			customers.name as customer_name
		');
		$this->db->from('transaction_history');
		$this->db->join('customers', 'customers.id = transaction_history.customer_id', 'left');
		$this->db->order_by('transaction_history.created_at', 'DESC');
		
		if ($limit && $start) {
			$this->db->limit($limit, $start);
		}
		
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * Get Transaction Details
	 * 
	 * Retrieves transaction details by transaction ID
	 * 
	 * @param int $transaction_id Transaction ID
	 * @return array
	 */
	public function get_transaction_details($transaction_id)
	{
		$this->db->select('
			transaction_details.id,
			transaction_details.transaction_id,
			transaction_details.medicine_id,
			transaction_details.quantity,
			transaction_details.operation,
			medicines.name as medicine_name
		');
		$this->db->from('transaction_details');
		$this->db->join('medicines', 'medicines.id = transaction_details.medicine_id', 'left');
		$this->db->where('transaction_details.transaction_id', $transaction_id);
		
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * Get Transaction By ID
	 * 
	 * Retrieves transaction data by ID
	 * 
	 * @param int $id Transaction ID
	 * @return array|null
	 */
	public function get_transaction_by_id($id)
	{
		$this->db->select('
			transaction_history.id,
			transaction_history.transaction_type,
			transaction_history.reference_id,
			transaction_history.customer_id,
			transaction_history.created_at,
			customers.name as customer_name
		');
		$this->db->from('transaction_history');
		$this->db->join('customers', 'customers.id = transaction_history.customer_id', 'left');
		$this->db->where('transaction_history.id', $id);
		
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * Get Customer Transactions
	 * 
	 * Retrieves customer transaction history with date range
	 * 
	 * @param int $customer_id Customer ID
	 * @param string|null $start_date Start date
	 * @param string|null $end_date End date
	 * @return array
	 */
	public function get_customer_transactions($customer_id, $start_date = null, $end_date = null)
	{
		$this->db->select('
			transaction_history.id,
			transaction_history.transaction_type,
			transaction_history.reference_id,
			transaction_history.created_at
		');
		$this->db->from('transaction_history');
		$this->db->where('transaction_history.customer_id', $customer_id);
		
		if ($start_date && $end_date) {
			$this->db->where('transaction_history.created_at >=', $start_date);
			$this->db->where('transaction_history.created_at <=', $end_date);
		}
		
		$this->db->order_by('transaction_history.created_at', 'DESC');
		
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * Count Total Transactions
	 * 
	 * Counts total number of transactions
	 * 
	 * @return int
	 */
	public function count_total_transactions()
	{
		$sql = "SELECT * FROM transaction_history";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	/**
	 * Update Transaction History
	 * 
	 * Updates an existing transaction record
	 * 
	 * @param array $data Transaction data
	 * @return int|bool Transaction ID if successful, false otherwise
	 */
	public function update_transaction_history($data)
	{
		if ($data) {
			$id = $data['user_id'];
			$this->db->where('id', $id);
			if ($this->db->update('transaction_history', $data)) {
				return $id;
			}
		}
		return false;
	}

	/**
	 * Update Transaction Details
	 * 
	 * Updates or creates transaction details record
	 * 
	 * @param array $data Transaction details data
	 * @return bool
	 */
	public function update_transaction_details($data)
	{
		if ($data) {
			$this->db->where('transaction_id', $data['transaction_id']);
			$this->db->where('medicine_id', $data['medicine_id']);
			$query = $this->db->get('transaction_details');
			
			if ($query->num_rows() > 0) {
				$this->db->where('transaction_id', $data['transaction_id']);
				$this->db->where('medicine_id', $data['medicine_id']);
				return $this->db->update('transaction_details', $data);
			} else {
				return $this->db->insert('transaction_details', $data);
			}
		}
		return false;
	}

	/**
	 * Delete Transaction Details
	 * 
	 * Deletes transaction details record
	 * 
	 * @param int $transaction_id Transaction ID
	 * @param int $medicine_id Medicine ID
	 * @return bool
	 */
	public function delete_transaction_details($transaction_id, $medicine_id)
	{
		$this->db->where('transaction_id', $transaction_id);
		$this->db->where('medicine_id', $medicine_id);
		return $this->db->delete('transaction_details');
	}
} 