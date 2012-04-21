<?php

class account_action extends ActionObject {

	/**
	 * userid used for the login session
	 */
	public $userid		= null;
	
	public function __construct($db) {
		parent::__construct();

		$this->db = $db;

		$modelFile = str_replace('\\', '/', strtolower(__DIR__))  . '/classes/account_model.php';

		if(file_exists($modelFile)) {
			include_once $modelFile;
		} else {
			throw new exception('Account Model class could not be loaded');
		}
	}

	public function page_test() {
		/*$prepare = $this->db->prepare("SELECT users.id, users.username, user_roles.roleid, role_perms.permid, permissions.permname FROM `users` 
					LEFT JOIN `user_roles` ON user_roles.userid = users.id
					LEFT JOIN `role_perms` ON user_roles.roleid = role_perms.roleid
					LEFT JOIN `permissions` ON permissions.id = role_perms.permid
					WHERE users.id = 1");
		$prepare->execute();
		$result = $prepare->fetchAll(PDO::FETCH_ASSOC);
		print_r($result);*/

		print_r($this->permissions->check("can_view_admin"));
	}

	/**
	 * lets try registering a new user
	 */
	public function page_register() {

		if(isset($_POST['submit'])) {

			$validate = new validate();

			/**
			 * setup custom allback text
			 */
			$validate->errors_text['account_action::_pwdntmatch'] = 'Password fields do not match!';
			$validate->errors_text['account_action::_userexists'] = 'User already exists!';

			/**
			* setup form validation rules
			*/
			$validate->set_rules('username', array(
				'empty', 
				array(
					'callback' => array(&$this, '_userexists')
				)
			));

			$validate->set_rules('password', array(
				'empty',
				array(
					'callback' => array($this, '_pwdntmatch'),
					'params' => array('password_again')
				)
			), true);

			/**
			 * lets attempt to save user into the database
			 */
			if(!$validate->check()) {

				/**
				 * register user
				 */
				if(account_model::register()) {
					$this->data->message = "Account created successful";
				} else {
					$this->data->message = "Something went wrong when attempting to create this account!";
				}


			} else {
				$this->data->message = validate::errors();
			}
		}
	}

	public function page_login() {

		if(isset($_POST['submit'])) {

			$validate = new validate();

			/**
			 * setup custom allback text
			 */
			$validate->errors_text['account_action::_userntexists'] = 'User does not exists!';
			$validate->errors_text['account_action::_pwdntmatchdb'] = 'Password does not match db';

			/**
			* setup form validation rules
			*/
			$validate->set_rules('username', array(
				'empty', 
				array(
					'callback' => array(&$this, '_userntexists')
				),
				array(
					'callback' => array($this, '_pwdntmatchdb'),
					'params' => array('password')
				)
			), true);

			/**
			 * lets attempt to save user into the database
			 */
			if(!$validate->check()) {

				/**
				 * register user
				 */
				if(!is_null($this->userid)) {
					$_SESSION['userid'] = $this->userid;
					$_SESSION['auth'] = true;
					$this->data->message = 'Login Successful';
				} else {
					$this->data->message = "There was an issue setting session data";
				}
			} else {
				$this->data->message = validate::errors();
			}
		}
	}

/**
 * custom form validation call back methods
 */

	/**
	 * check if the passwords match
	 */
	public function _pwdntmatch($input, $input2) {
		if($_POST[$input] != $_POST[$input2]) {
			return true;
		}
	}

	/**
	 * check if the passwords match
	 */
	public function _pwdntmatchdb($input, $password) {
		
		$password 	= md5(r::post($password));
		$username 	= r::post($input);

		$prepare	= $this->db->prepare("SELECT * FROM `users` WHERE `username` = ? AND `password` = ?");
		$prepare->execute(array($username, $password));
		$result		= $prepare->fetch(PDO::FETCH_ASSOC);

		if(empty($result)) {
			return true;
		}

		$this->userid = $result['id'];
	}

	/**
	 * check if user exists
	 */
	public function _userexists($input) {

		$prepare 	= $this->db->prepare("SELECT * FROM `users` WHERE `username` = ?");
		$prepare->execute(array(r::post($input)));
		$result 	= $prepare->fetch();

		if(!empty($result)) {
			return true;
		}
	}

	/**
	 * check if user doesn't exists
	 */
	public function _userntexists($input) {

		$prepare 	= $this->db->prepare("SELECT * FROM `users` WHERE `username` = ?");
		$prepare->execute(array(r::post($input)));
		$result 	= $prepare->fetch();

		if(empty($result)) {
			return true;
		}
	}
}