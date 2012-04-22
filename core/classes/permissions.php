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
 * @package	FFrame
 * @copyright	Copyright (c) 2012 Lamonte & Helpers
 * @license	http://www.opensource.org/licenses/lgpl-3.0.html GNU General Public License, version 3 (GPL-3.0)
 * @version 	0.0.1 (Designating development stage http://en.wikipedia.org/wiki/Software_versioning)
 */

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
	 * class instance
	 */
	public static $instance = null;

	/**
	 * singleton function - get instance of this class
	 */
	public function instance() {
		if(is_null(self::$instance)) {
			self::$instance = new permissions();
		}
		return self::$instance;
	}

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