<?php 

/**
 * Model_users
 * 
 * Handles all database operations related to users including:
 * - User data management
 * - User group assignments
 * - User statistics
 * 
 * @package     Cattle Inventory
 * @subpackage  Models
 * @category    User Management
 * @author      Your Name
 * @link        http://your-website.com
 */
class Model_users extends CI_Model
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
	 * Get User Data
	 * 
	 * Retrieves user data by ID or all users except admin
	 * 
	 * @param int|null $userId User ID
	 * @return array
	 */
	public function getUserData($userId = null) 
	{
		if ($userId) {
			$sql = "SELECT * FROM users WHERE id = ?";
			$query = $this->db->query($sql, array($userId));
			return $query->row_array();
		}

		$sql = "SELECT * FROM users WHERE id != ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/**
	 * Get User Group
	 * 
	 * Retrieves user group information
	 * 
	 * @param int|null $userId User ID
	 * @return array|null
	 */
	public function getUserGroup($userId = null) 
	{
		if ($userId) {
			$sql = "SELECT * FROM user_group WHERE user_id = ?";
			$query = $this->db->query($sql, array($userId));
			$result = $query->row_array();

			if ($result) {
				$group_id = $result['group_id'];
				$g_sql = "SELECT * FROM groups WHERE id = ?";
				$g_query = $this->db->query($g_sql, array($group_id));
				return $g_query->row_array();
			}
		}
		return null;
	}

	/**
	 * Create User
	 * 
	 * Creates a new user with group assignment
	 * 
	 * @param array $data User data
	 * @param int|null $group_id Group ID
	 * @return bool
	 */
	public function create($data = '', $group_id = null)
	{
		if ($data && $group_id) {
			if ($this->db->insert('users', $data)) {
				$user_id = $this->db->insert_id();
				$group_data = array(
					'user_id' => $user_id,
					'group_id' => $group_id
				);
				return $this->db->insert('user_group', $group_data);
			}
		}
		return false;
	}

	/**
	 * Edit User
	 * 
	 * Updates user data and group assignment
	 * 
	 * @param array $data User data
	 * @param int|null $id User ID
	 * @param int|null $group_id Group ID
	 * @return bool
	 */
	public function edit($data = array(), $id = null, $group_id = null)
	{
		if ($id) {
			$this->db->where('id', $id);
			$update = $this->db->update('users', $data);

			if ($group_id) {
				$update_user_group = array('group_id' => $group_id);
				$this->db->where('user_id', $id);
				$user_group = $this->db->update('user_group', $update_user_group);
				return ($update && $user_group);
			}
			return $update;
		}
		return false;
	}

	/**
	 * Delete User
	 * 
	 * Removes a user record
	 * 
	 * @param int $id User ID
	 * @return bool
	 */
	public function delete($id)
	{
		if ($id) {
			$this->db->where('id', $id);
			return $this->db->delete('users');
		}
		return false;
	}

	/**
	 * Count Total Users
	 * 
	 * Counts total number of users
	 * 
	 * @return int
	 */
	public function countTotalUsers()
	{
		$sql = "SELECT * FROM users";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
}