<?php

/**
 * Model_customers
 * 
 * Handles all database operations related to customers including:
 * - Managing customer and vendor data
 * - CRUD operations for customers
 * - Customer statistics and analytics
 * 
 * @package     Cattle Inventory
 * @subpackage  Models
 * @category    Customer Management
 * @author      Your Name
 * @link        http://your-website.com
 */
class Model_customers extends CI_Model
{
    /**
     * Constructor
     * 
     * Initializes the model
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get Active Customer Data
     * 
     * Retrieves all active customers (user_type = 1)
     * 
     * @return array
     */
    public function getActiveCustomerData()
    {
        $sql = "SELECT * FROM customers WHERE active = ? AND user_type = ?";
        $query = $this->db->query($sql, array(1, '1'));
        return $query->result_array();
    }

    /**
     * Get Active Vendor Data
     * 
     * Retrieves all active vendors (user_type = 2)
     * 
     * @return array
     */
    public function getActiveVendorData()
    {
        $sql = "SELECT * FROM customers WHERE active = ? AND user_type = ?";
        $query = $this->db->query($sql, array(1, '2'));
        return $query->result_array();
    }

    /**
     * Get Vendor Data By ID
     * 
     * Retrieves vendor data by ID
     * 
     * @param int $id Vendor ID
     * @return array|null
     */
    public function getVendorDataById($id)
    {
        $sql = "SELECT * FROM customers WHERE id = ? AND user_type = 2 ORDER BY name ASC";
        $query = $this->db->query($sql, array($id));
        return $query->row_array();
    }

    /**
     * Get Customer Data By ID
     * 
     * Retrieves customer data by ID
     * 
     * @param int $id Customer ID
     * @return array|null
     */
    public function getCustomerDataById($id)
    {
        $sql = "SELECT * FROM customers WHERE id = ? ORDER BY name ASC";
        $query = $this->db->query($sql, array($id));
        return $query->row_array();
    }

    /**
     * Get Customer Data
     * 
     * Retrieves customer data by type
     * 
     * @param int $type User type (1 for customers, 2 for vendors)
     * @return array
     */
    public function getCustomerData($type)
    {
        $sql = "SELECT * FROM customers WHERE active = 1 AND user_type = ? ORDER BY id DESC";   
        $query = $this->db->query($sql, array($type));
        return $query->result_array();
    }

    /**
     * Get All Customer Data
     * 
     * Retrieves all customer data ordered by name
     * 
     * @return array
     */
    public function getAllCustomerData()
    {
        $sql = "SELECT * FROM customers ORDER BY name ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * Create Customer
     * 
     * Creates a new customer record
     * 
     * @param array $data Customer data
     * @return bool
     */
    public function create($data)
    {
        if ($data) {
            return $this->db->insert('customers', $data);
        }
        return false;
    }

    /**
     * Update Customer
     * 
     * Updates an existing customer record
     * 
     * @param array $data Customer data
     * @param int $id Customer ID
     * @return bool
     */
    public function update($data, $id)
    {
        if ($data && $id) {
            $this->db->where('id', $id);
            return $this->db->update('customers', $data);
        }
        return false;
    }

    /**
     * Remove Customer
     * 
     * Deletes a customer record
     * 
     * @param int $id Customer ID
     * @return bool
     */
    public function remove($id)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->delete('customers');
        }
        return false;
    }

    /**
     * Get User Medicine Statistics
     * 
     * Retrieves medicine statistics for a specific customer
     * 
     * @param string $customerName Customer name
     * @return array
     */
    public function getUserMedicineStats($customerName)   
    {
        $this->db->select('o.id, m.created_at, m.id AS medicine_id, m.transaction_id, md.name AS medicine_name, SUM(m.quantity_given) AS total_quantity_ordered');
        $this->db->from('medicine_transactions o');
        $this->db->join('medicine_transaction_history m', 'FIND_IN_SET(m.transaction_id, o.id) > 0', 'inner');
        $this->db->join('medicines md', 'md.id = m.medicine_id', 'inner');
        $this->db->where('o.customer_id', $customerName);
        $this->db->group_by('m.id');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get Most Ordered Medicine By Customer
     * 
     * Retrieves the most ordered medicine for a specific customer
     * 
     * @param string $customerName Customer name
     * @return object|null
     */
    public function getMostOrderedMedicineByCustomer($customerName)   
    {
        $this->db->select('o.id, o.customer_name, o.created_at, m.id AS medicine_id, m.name, SUM(o.quantity_ordered) AS total_quantity_ordered');
        $this->db->from('orders o');
        $this->db->join('medicines m', 'FIND_IN_SET(m.id, o.medicine_id) > 0', 'inner');
        $this->db->where('o.customer_name', $customerName);
        $this->db->group_by('m.id');
        $this->db->order_by('total_quantity_ordered', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Check Customer Exists
     * 
     * Checks if a customer with the given name exists
     * 
     * @param string $name Customer name
     * @return bool
     */
    public function checkCustomerExists($name)
    {
        $this->db->where('LOWER(name)', strtolower($name));
        $query = $this->db->get('customers');
        return ($query->num_rows() > 0);
    }

    /**
     * Check Customer Exists Except This
     * 
     * Checks if a customer with the given name exists, excluding a specific ID
     * 
     * @param string $name Customer name
     * @param int $id Customer ID to exclude
     * @return bool
     */
    public function checkCustomerExistsExceptThis($name, $id)
    {
        $this->db->where('LOWER(name)', strtolower($name));
        $this->db->where('id !=', $id);
        $query = $this->db->get('customers');
        return ($query->num_rows() > 0);
    }

    /**
     * Get Top Customers With Products
     * 
     * Retrieves the top 5 customers with their ordered products
     * 
     * @return array
     */
    public function get_top_customers_with_products()
    {
        $this->db->select('customers.id as customer_id, customers.name as customer_name, 
                          medicines.id as medicine_id, medicines.name as medicine_name, 
                          SUM(orders_item.qty) as total_ordered');
        $this->db->from('orders_item');
        $this->db->join('customers', 'customers.id = orders_item.customer_id');
        $this->db->join('medicines', 'medicines.id = orders_item.product_id');
        $this->db->group_by('orders_item.customer_id, orders_item.product_id');
        $this->db->order_by('total_ordered', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get();
        
        return $query->result();
    }
}

