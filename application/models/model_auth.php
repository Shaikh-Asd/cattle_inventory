<?php 

/**
 * Model_auth
 * 
 * Handles all authentication-related database operations including:
 * - Email verification
 * - User login
 * - Password validation
 * 
 * @package     Cattle Inventory
 * @subpackage  Models
 * @category    Authentication
 * @author      Your Name
 * @link        http://your-website.com
 */
class Model_auth extends CI_Model
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
	 * Check Email
	 * 
	 * Verifies if an email exists in the database
	 * 
	 * @param string $email Email to check
	 * @return bool
	 */
	public function check_email($email) 
	{
		if ($email) {
			$sql = 'SELECT * FROM users WHERE email = ?';
			$query = $this->db->query($sql, array($email));
			return ($query->num_rows() === 1);
		}
		return false;
	}

	/**
	 * Login
	 * 
	 * Authenticates user credentials
	 * 
	 * @param string $email User email
	 * @param string $password User password
	 * @return array|bool User data if successful, false otherwise
	 */
	public function login($email, $password)
	{
		if ($email && $password) {
			$sql = "SELECT * FROM users WHERE email = ?";
			$query = $this->db->query($sql, array($email));

			if ($query->num_rows() === 1) {
				$result = $query->row_array();
				return password_verify($password, $result['password']) ? $result : false;
			}
		}
		return false;
	}
}