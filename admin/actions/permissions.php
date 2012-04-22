<?php

class permissions_action extends ActionObject {

	public function __construct() {

		global $basedir;
		parent::__construct();

		if(!$this->permissions->check("admin_perm_access")) {
			redirect("admin");
		}

		/**
		 * include permissions model
		 */
		$includeFile = $basedir . 'admin/actions/classes/permissions_model.php';
		
		if(!file_exists($includeFile)) {
			throw new exception('There was an issue loading permissions class model');
		}

		include_once $includeFile;
	}

	public function page_index() {
		
		/**
		 * get permissions from database
		 */
		$this->data->permissions = permissions_model::inst()->select_permissions();
	}
}