<?php

class permissions {
	
	/**
	 * used to store the session user id
	 */
	public $userid		= null;

	/**
	 * array of user permissions
	 */
	public $permissions	= array();

	/**
	 * get user id and attempt to load user permissions
	 */
	public function __construct() {

		global $db;

		$this->db = $db;

		/**
		 * check if userid siession is set
		 */
		if(isset($_SESSION['userid'])) {
			$this->userid = intval($_SESSION['userid']);
		}

		/**
		 * load all permissions from db
		 */
		$this->load_perms();
	}

	/**
	 * load user permissions from the database
	 */
	public function load_perms() {
		
		if(is_null($this->userid)) {
			return false;
		}

		/**
		 * select all the roles for this user, then select all the permissions for that role and attempt to return
		 * all the user permissions from each role that this user is connected with
		 */
		$prepare 	= $this->db->prepare("SELECT users.id, users.username, user_roles.roleid, role_perms.permid, permissions.permname FROM `users` 
					LEFT JOIN `user_roles` ON user_roles.userid = users.id
					LEFT JOIN `role_perms` ON user_roles.roleid = role_perms.roleid
					LEFT JOIN `permissions` ON permissions.id = role_perms.permid
					WHERE users.id = ?");

		$prepare->execute(array($this->userid));

		$result 	= $prepare->fetchAll(PDO::FETCH_ASSOC);
		
		/**
		 * loop through the array and attempt get every possible permission name
		 */
		foreach($result as $permissions) {

			if(!in_array($permissions['permname'], $this->permissions)) {
				$this->permissions[] = $permissions['permname'];
			}
		}
	}

	/**
	 * check if user has permission
	 */
	public function check($permission) {
		return in_array($permission, $this->permissions) ? true : false;
	}
}