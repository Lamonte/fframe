<?php
/**
 * FFrame Forum Software
 *
 * LICENSE
 * 
 * The licenses for most software and other practical works are designed to take 
 * away your freedom to share and change the works. By contrast, the GNU General 
 * Public License is intended to guarantee your freedom to share and change all 
 * versions of a program--to make sure it remains free software for all its users. 
 * We, the Free Software Foundation, use the GNU General Public License for most 
 * of our software; it applies also to any other work released this way by its 
 * authors. You can apply it to your programs, too.
 *
 * @category	Admin
 * @package	FFrame
 * @copyright	Copyright (c) 2012 Lamonte & Helpers
 * @license	http://www.opensource.org/licenses/lgpl-3.0.html GNU General Public License, version 3 (GPL-3.0)
 * @version 	0.0.1 (Designating development stage http://en.wikipedia.org/wiki/Software_versioning)
 */

class roles_model {

	/**
	 * class instance
	 */
	public static $instance = null;

	/**
	 * singleton function
	 */
	public static function inst() {
		if(is_null(self::$instance)) {
			$class = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	public function __construct() {

		global $db;
		$this->db = $db;
	}

	public function select_roles() {

		$prepare 	= $this->db->prepare("SELECT * FROM `roles`");
		$prepare->execute();
		$result		= $prepare->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	public function select_role_permissions($id) {

		$id 		= intval($id);
		$prepare 	= $this->db->prepare("SELECT role_perms.*, permissions.permname, permissions.description FROM `role_perms`
							LEFT JOIN `permissions` ON permissions.id = role_perms.permid
							WHERE role_perms.roleid = ?");
		$prepare->execute(array($id));
		$result		= $prepare->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	public function select_all_permissions() {

		$prepare 	= $this->db->prepare("SELECT * FROM `permissions`");
		$prepare->execute();
		$result		= $prepare->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * update role in database
	 * 
	 * @param integer $id - role id in database
	 * @return boolean
	 */
	public function update($id = 0) {

		$roleid		= intval($id);
		$rolename	= r::post("rolename", true);

		$this->prepare	= $this->db->prepare("UPDATE `roles` SET `rolename` = ? WHERE `id` = ?");
		$result 	= $this->prepare->execute(array($rolename, $roleid));
		$this->errors 	= $this->db->getErrorInfo($this->prepare);

		return ($result ? true : false);
	}

	/**
	 * update role permissions to database
	 */
	public function update_permissions($id) {

		/**
		 * loop through the permission names & get the id then sign them to
		 * an array and loop through and delete all permissions from that role
		 * and insert new rows into the database from the array list
		 */
		$permissions	= r::post("perms");
		$permsArray	= array();

		if(!is_array($permissions) || empty($permissions)) {
			return void;
		}

		foreach($permissions as $permission) {
			if(($permid = $this->get_permission_id($permission)) !== false) {
				$permsArray[] = $permid;
			}
		}

		$this->prepare 	= $this->db->prepare("DELETE FROM `role_perms` WHERE `roleid` = ?");
		$result		= $this->prepare->execute(array($id));
		$this->errors 	= $this->db->getErrorInfo($this->prepare);

		if(!$result) {
			return false;
		}

		foreach($permsArray as $perm) {
			$this->prepare	= $this->db->prepare("INSERT INTO `role_perms` (`roleid`, `permid`) VALUES (?, ?);");
			$result 	= $this->prepare->execute(array($id, $perm));
			$this->errors	= $this->db->getErrorInfo($this->prepare);

			if(!$result) {
				return false;
			}
		}

		return true;
		
	}

	private function delete_perms($id) {

	}

	/**
	 * get permission id by permission name
	 *
	 * @param string $permname
	 * @return integer, boolean
	 */
	public function get_permission_id($permname) {

		$permname	= $permname;
		$this->prepare	= $this->db->prepare("SELECT * FROM `permissions` WHERE `permname` = ?");
		$this->prepare->execute(array($permname));
		$result 	= $this->prepare->fetch(PDO::FETCH_ASSOC);

		if(!$result) {
			return false;
		} else {
			return $result['id'];
		}
	}

	/**
	 * insert role into the database
	 *
	 * @return boolean
	 */
	public function create() {

		$rolename 	= r::post("rolename", true);

		$this->prepare 	= $this->db->prepare("INSERT INTO `roles` (`rolename`) VALUES(?)");
		$result		= $this->prepare->execute(array($rolename));
		$this->errors 	= $this->db->getErrorInfo($this->prepare);

		return ($result ? true : false);
	}
}