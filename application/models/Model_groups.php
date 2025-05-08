<?php 

/**
 * Model_groups
 * 
 * Handles all database operations related to user groups including:
 * - Group data management
 * - User group assignments
 * - Group validation
 * 
 * @package     Cattle Inventory
 * @subpackage  Models
 * @category    Group Management
 * @author      Your Name
 * @link        http://your-website.com
 */
class Model_groups extends CI_Model
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
	 * Get Group Data
	 * 
	 * Retrieves group data by ID or all groups except admin
	 * 
	 * @param int|null $groupId Group ID
	 * @return array
	 */
	public function getGroupData($groupId = null) 
	{
		if($groupId) {
			$sql = "SELECT * FROM groups WHERE id = ?";
			$query = $this->db->query($sql, array($groupId));
			return $query->row_array();
		}

		$sql = "SELECT * FROM groups WHERE id != ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/**
	 * Create Group
	 * 
	 * Creates a new group record
	 * 
	 * @param array $data Group data
	 * @return bool
	 */
	public function create($data = '')
	{
		if ($data) {
			return $this->db->insert('groups', $data);
		}
		return false;
	}

	/**
	 * Edit Group
	 * 
	 * Updates an existing group record
	 * 
	 * @param array $data Group data
	 * @param int $id Group ID
	 * @return bool
	 */
	public function edit($data, $id)
	{
		if ($data && $id) {
			$this->db->where('id', $id);
			return $this->db->update('groups', $data);
		}
		return false;	
	}

	/**
	 * Delete Group
	 * 
	 * Removes a group record
	 * 
	 * @param int $id Group ID
	 * @return bool
	 */
	public function delete($id)
	{
		if ($id) {
			$this->db->where('id', $id);
			return $this->db->delete('groups');
		}
		return false;
	}

	/**
	 * Check Group in User Group
	 * 
	 * Verifies if a group is assigned to any user
	 * 
	 * @param int $id Group ID
	 * @return bool
	 */
	public function existInUserGroup($id)
	{
		if ($id) {
			$sql = "SELECT * FROM user_group WHERE group_id = ?";
			$query = $this->db->query($sql, array($id));
			return ($query->num_rows() > 0);
		}
		return false;
	}

	/**
	 * Get User Group By User ID
	 * 
	 * Retrieves group information for a specific user
	 * 
	 * @param int $user_id User ID
	 * @return array|null
	 */
	public function getUserGroupByUserId($user_id) 
	{
		if ($user_id) {
			$sql = "SELECT * FROM user_group 
			INNER JOIN groups ON groups.id = user_group.group_id 
			WHERE user_group.user_id = ?";
			$query = $this->db->query($sql, array($user_id));
			return $query->row_array();
		}
		return null;
	}
}