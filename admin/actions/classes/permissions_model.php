<?php

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

	public function select_permissions() {

		$prepare 	= $this->db->prepare("SELECT * FROM `permissions`");
		$prepare->execute();
		$result		= $prepare->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}
}