<?php

class viewforum_action extends ActionObject {

	/**
	 * construct, necessary to create blank data object
	 * 
	 * @param Database Object $db - get the db object so we can make db queries
	 * @return void
	 */
	public function __construct($db) {
		parent::__construct();

		$this->db = $db;
	}

	public function page_forum($id) {
		$this->db->query("SELECT * FROM `users`");
		while($result = $this->db->fetch()) {
			print_r($result);
		}
	}
}