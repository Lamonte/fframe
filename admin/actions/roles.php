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

class roles_action extends ActionObject {

	/**
	 * used when assigning permissions to roles, just the way we'll group them
	 */
	public $permGroupText = array(
		'admin_role' 	=> "Manage Roles",
		'admin_perm' 	=> "Manage Permissions",
		'admin_forum'	=> "Manage Forums",
		'admin_other' 	=> "Other Permissions",
	);

	public function __construct() {

		global $basedir;
		parent::__construct();

		/**
		 * check if user has permissions
		 */
		if(!$this->permissions->check("admin_role_access")) {
			redirect("admin");
		}

		/**
		 * include permissions model
		 */
		$includeFile = $basedir . 'admin/actions/classes/roles_model.php';
		
		if(!file_exists($includeFile)) {
			throw new exception('There was an issue loading roles class model');
		}

		include_once $includeFile;
	}

	public function page_index() {

		$this->data->roles = roles_model::inst()->select_roles();
	}

	public function page_create() {

		/**
		 * check if user has permissions
		 */
		if(!$this->permissions->check("admin_role_create")) {
			redirect("admin/roles");
		}

		if(isset($_POST['submit'])) {

			$validate = new validate();
			$validate->errors_text['roles_action::roles_exists'] = "Current role already exists in the database";

			$validate->set_rules("rolename", array(
				'empty',
				array(
					'callback' => array($this, 'role_exists'),
					'params' => array(0) //set it to zero: WHERE `id` != 0
				)
			), true);

			if(!$validate->check()) {
				
				if(!roles_model::inst()->create()) {
					$this->data->message = "There was an issue trying to create the role: " . roles_model::inst()->errors;
				} else {
					redirect("admin/roles");
				}

			} else {
				$this->data->message = $validate->errors();
			}
		}

	}

	public function page_edit($id = null) {

		/**
		 * check if user has permissions
		 */
		if(!$this->permissions->check("admin_role_modify") || !$this->data->role = $this->get_role($id)) {
			redirect("admin/roles");
		}

		if(isset($_POST['submit'])) {

			$validate = new validate();
			$validate->errors_text['roles_action::roles_exists'] = "Current role already exists in the database";

			$validate->set_rules("rolename", array(
				'empty',
				array(
					'callback' => array($this, 'role_exists'),
					'params' => array($this->data->role['id'])
				)
			), true);

			if(!$validate->check()) {
				
				if(!roles_model::inst()->update($id)) {
					$this->data->message = "There was an issue trying to update the role: " . roles_model::inst()->errors;
				} else {
					redirect("admin/roles/edit/" . $id);
				}

			} else {
				$this->data->message = $validate->errors();
			}
		}

	}

	public function page_assign($id = null) {

		/**
		 * check if user has permissions
		 */
		if(!$this->permissions->check("admin_role_perms") || !$this->data->role = $this->get_role($id)) {
			redirect("admin/roles");
		}

		/**
		 * check if the form has been executed and attempt to update the
		 * the permissions for this role
		 */
		if(isset($_POST['submit'])) {
			
			$roles_model = new roles_model();

			if(!$roles_model->update_permissions($id)) {
				$this->errors = $roles_model->errors;
			} else {
				return redirect("admin/roles/assign/" . $id);
			}

		}

		$permGroups 		= array();
		$allPermGroups		= array();

		$permGroupText 		= array();
		$allPermGroupText 	= array();

		$permissions 		= roles_model::inst()->select_role_permissions($id);
		$allPermissions 	= roles_model::inst()->select_all_permissions();

		/**
		 * loop through the group permissions text & setup default array
		 */
		foreach($this->permGroupText as $groupText => $value) {

			//setup empty array
			$permGroups[$groupText] 	= array();
			$allPermGroups[$groupText] 	= array();

			$permGroupText[] 		= $groupText;
			$allPermGroupText[]		= $groupText;
		}

		/**
		 * loop through permissions and assign them to the appropriate group
		 */
		foreach($permissions as $permission) {

			/**
			 * loop through the group permissions text & add permissions to group
			 */
			foreach($permGroupText as $groupText) {

				$permName = @explode('_', $permission['permname']);
				$permName = (!empty($permName) && is_array($permName) && count($permName) >= 2 ? $permName[0] . '_' . $permName[1] : null);

				//preg_quote to be safe
				if(preg_match("/^" . preg_quote($groupText) . "/i", $permission['permname'])) {
					$permGroups[$groupText][$permission['permname']] = $permission;
				}

				//setup the admin other group text
				if(!in_array($permName, $allPermGroupText)) {
					$permGroups['admin_other'][$permission['permname']] = $permission;
				}
			}
		}

		foreach($allPermissions as $permission) {


			/**
			 * loop through the group permissions text & add permissions to group
			 */
			foreach($allPermGroupText as $groupText) {

				$permName = @explode('_', $permission['permname']);
				$permName = (!empty($permName) && is_array($permName) && count($permName) >= 2 ? $permName[0] . '_' . $permName[1] : null);

				//preg_quote to be safe
				if(preg_match("/^" . preg_quote($groupText) . "/i", $permission['permname'])) {
					$allPermGroups[$groupText][$permission['permname']] = $permission;
				}

				//setup the admin other group text
				if(!in_array($permName, $permGroupText)) {
					$allPermGroups['admin_other'][$permission['permname']] = $permission;
				}

			}
		}

		/**
		 * now lets loop through all the permissions and create a new array which then
		 * checks the current roles permissions and add a "checked" value if this role
		 * has said permissions.
		 */
		
		$permsArray = array();

		foreach($allPermGroups as $group => $roles) {

			$permsArray[$group] = array();

			/**
			 * loop through all permissions and cross reference the current roles permissions
			 */
			foreach($roles as $role => $data) {

				if(isset($permGroups[$group][$role]) && !empty($permGroups[$group][$role])) {
					$permsArray[$group][$role] = $data;
					$permsArray[$group][$role]['checked'] = true;
				} else {
					$permsArray[$group][$role] = $data;
					$permsArray[$group][$role]['checked'] = false;
				}
			}

		}

		$this->data->permGroupText 	= $this->permGroupText;
		$this->data->permissions 	= $permsArray;

	}

	public function page_delete($id = null) {

	}

	/**
	 * private functions
	 */


	private function get_role($id) {

		$prepare = $this->db->prepare("SELECT * FROM `roles` WHERE `id` = ?");
		$prepare->execute(array($id));
		$result = $prepare->fetch();

		return empty($result) ? false : $result;
	}

	/**
	 * callback functions
	 */

	/**
	 * check if rule already exists (excluding itself)
	 */
	public function role_exists($input, $currentId, $dont = false) {

		$roleName 	= r::post($input);

		$prepare 	= $this->db->prepare("SELECT * FROM `roles` WHERE `rolename` = ? AND `id` != ?");
		$prepare->execute(array($roleName, $currentId));
		$result		= $prepare->fetchAll(PDO::FETCH_ASSOC);

		return empty($result) ? false : true;
	}
}