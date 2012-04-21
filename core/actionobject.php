<?php
class ActionObject {

	protected $data 	= null;
	protected $db		= null;
	protected $permissions 	= null;

	public function __construct() {
		
		global $db;

		$this->db 		= $db;
		$this->data 		= new stdClass;
		$this->permissions 	= new permissions();
	}

	public function getData() {
		return $this->data;
	}
}