<?php
class ActionObject {
	protected $data = null;
	protected $db	= null;
	public function __construct() {
		
		global $db;

		$this->db 	= $db;
		$this->data 	= new stdClass;
	}

	public function getData() {
		return $this->data;
	}
}