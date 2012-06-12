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

class Forums_action extends ActionObject {

	public $rolesArray = array(
		0 => 'Guest',
	);

	public function __construct() {

		global $basedir;
		parent::__construct();

		/**
		 * check if user has permissions
		 */
		if(!$this->permissions->check("admin_forum_access")) {
			redirect("admin");
		}

		/**
		 * include model classes
		 */
		$includesArray = array(
			$basedir . 'admin/actions/classes/forums_model.php',
			$basedir . 'admin/actions/classes/roles_model.php',
		);

		foreach($includesArray as $include) {
			if(!file_exists($include)) {
				throw new exception('There was an issue loading model class: ' . $include);
			}

			include_once $include;
		}

	}

	public function page_index() {

		/**
		 * select all forums from database
		 */
		$this->data->forums 	= forums_model::inst()->get();
	}

	/**
	 * creates a new forum
	 *
	 * @param integer $parent - parent forum of the current forum you're creating
	 * @return void
	 */
	public function page_create($parent = 0) {

		/**
		 * form submission
		 */
		if(isset($_POST['submit'])) {

			$message = null;
			if(empty($_POST['name'])) {
				$message = "You left the forum name empty!";
			}

			/**
			 * lets try saving this forum into the database
			 */
			if(is_null($message)) {
				$forumPermissions = array(
					'read' 		=> null,
					'write' 	=> null,
					'edit'		=> null,
					'delete'	=> null,
				);	

				foreach($forumPermissions as $perm => $value) {
					if(isset($_POST[$perm]) && !empty($_POST[$perm])) {
						$forumPermissions[$perm] = $_POST[$perm];
					}
				}

				/**
				 * lets save forum to the database
				 */
				$this->data->message = "New forum added to the database";
				forums_model::inst()->create($forumPermissions, $parent);

			} else {
				$this->data->message = $message;
			}


		}

		/**
		 * setup roles array
		 */
		$roles = roles_model::inst()->select_roles();
		foreach($roles as $role) {
			$this->rolesArray[$role['id']] = $role['rolename'];
		}
		
		/**
		 * setup permission inputs array
		 */
		$perms = array(
			'read',
			'write',
			'edit',
			'delete',
		);

		$permInputs = array();
		foreach($perms as $perm) {

			$permInputs[$perm] = array();

			/**
			 * loop through db roles array
			 */
			foreach($this->rolesArray as $id => $role) {
				$permInputs[$perm][] = array($id, $role);
			}
		}

		$this->data->permInputs = $permInputs;
		$this->data->rolesArray = $this->rolesArray;

		//var_dump($_POST);
	}

	public function page_edit($id){}
	public function page_delete($id){}
}