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

	public function page_create() {

		if(!$this->permissions->check("admin_perm_create")) {
			redirect("admin/permissions");
		}

		if(isset($_POST['submit'])) {

			$validate = new validate();

			/**
			 * setup callback error messages
			 */
			$validate->errors_text['permissions_action::perm_exists'] = 'Permission rule already exists!';
			$validate->errors_text['permissions_action::perm_name_invalid'] = 'Permission name is invalid.  Only allowed to have lower cased alphanumeric characters.  Underscore is allowed.';

			$validate->set_rules("name", array(
				'empty',
				array(
					'callback' => array($this, 'perm_name_invalid')
				),
				array(
					'callback' => array($this, 'perm_exists')
				)

			), true);

			$validate->set_rules("desc", array(
				'empty',
			), true);

			if(!$validate->check()) {
				
				if(permissions_model::inst()->create_permission()) {
					$this->data->message = 'Permission added to database';
				} else {
					$dberror = permissions_model::inst()->prepare->errorInfo();
					$this->data->message = 'There was an error inserting permission into database: ' . $dberror[2];
				}
			} else {
				$this->data->message = $validate->errors();
			}
		}
	}


/**
 * permission callbacks
 */
	
	/**
	 * checks if permission already exists
	 */
	public function perm_exists($input) {

		$rule 		= r::post($input);
		$prepare 	= $this->db->prepare("SELECT * FROM `permissions` WHERE `permname` = ?");
		$prepare->execute(array($rule));

		$result		= $prepare->fetch(PDO::FETCH_ASSOC);

		if(!empty($result)) {
			return true;
		}
	}

	/**
	 * checks if a permissions is a proper alphan lowercased numeric
	 * with a possible underscore or so
	 */
	public function perm_name_invalid($input) {

		if(!preg_match("/^[a-z0-9_]+$/", r::post($input))) {
			return true;
		}
	}
}