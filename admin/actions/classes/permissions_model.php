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

class permissions_model {

	/**
	 * class instance
	 */
	public static $instance = null;

	/**
	 * singleton function
	 */
	public static function inst() {
		if(is_null(self::$instance)) {
			self::$instance = new permissions_model();
		}
		return self::$instance;
	}

	public function __construct() {
		global $db;
		$this->db = $db;
	}

	/**
	 * select all permissions from the database
	 *
	 * @return array
	 */
	public function select_permissions() {

		$prepare 	= $this->db->prepare("SELECT * FROM `permissions`");
		$prepare->execute();
		$result		= $prepare->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * add new permission to the database
	 *
	 * @return boolean - true/false if there was an issue = false
	 */
	public function create_permission() {

		$permission 	= r::post("name");
		$description	= r::post("desc", true);

		$this->prepare 	= $this->db->prepare("INSERT INTO `permissions` (`permname`, `description`) VALUES (?, ?)");
		$result		= $this->prepare->execute(array($permission, $description));

		return !$result ? false : true;
	}
}