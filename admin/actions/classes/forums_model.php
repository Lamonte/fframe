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

class forums_model {

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

	public function create($roles, $parent = 0) {

		$forumName 	= r::post("name", true);
		$query 		= "INSERT INTO `forums` (`forumName`, `forumParent`) VALUES (?, ?);";
		
		$prepare 	= $this->db->prepare($query);
		$prepare->execute(array($forumName, $parent));
		$id 		= $this->db->lastInsertId();

		$this->assign_roles($roles, $id);
	}

	/**
	 * insert forum permissions based on role into the database
	 *
	 * @param array $roles
	 * @param integer $forumId
	 * @param boolean $delete - do we want to delete all the rows with this forumId first?
	 * @return void
	 */
	public function assign_roles($roles, $forumId, $delete = false) {
		/**
		 * delete forum permissions based on this forum id
		 */
		if($delete === true) {
			$query 		= "DELETE FROM `forum_perms` WHERE `forumId` = ?";
			$prepare 	= $this->db->prepare($query);
			$execute 	= $prepare->execute(array($forumId));

			if(!$execute) {
				$error = $prepare->errorInfo();
				throw new exception("There was an issue executing query: " . $error[2]);
			}
		}

		/**
		 * loop through each read/write/delete/edit permission for 
		 * each role
		 */
		foreach($roles as $role => $values) {
			foreach($values as $value) {
				
				$query 		= "INSERT INTO `forum_perms` (`forumId`, `roleId`, `perm`) VALUES(?, ?, ?)";
				$prepare 	= $this->db->prepare($query);
				$execute 	= $prepare->execute(array($forumId, $value, $role));

				if(!$execute) {
					$error = $prepare->errorInfo();
					throw new exception("There was an issue executing query: " . $error[2]);
				}
			}
		}
	}

	/**
	 * Select all forums from the database
	 *
	 * @return array
	 */
	public function get() {

		$query 		= "SELECT * FROM `forums` WHERE `forumParent` = 0 ORDER BY `forumId` DESC";
		$prepare	= $this->db->prepare($query);
		$execute	= $prepare->execute();

		if(!$execute) {
			$error = $prepare->errorInfo();
			throw new exception("There was an issue executing query: " . $error[2]);
		}

		$result		= $prepare->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}
}