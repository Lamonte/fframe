<?php

class account_action extends ActionObject {
	
	public function __construct($db) {
		parent::__construct();

		$this->db = $db;
	}

	/**
	 * lets try registering a new user
	 */
	public function page_register() {

		if(isset($_GET['submit'])) {

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

				$modelFile = str_replace('\\', '/', strtolower(__DIR__))  . '/classes/account_model.php';

				if(file_exists($modelFile)) {
					include_once $modelFile;
				} else {
					throw new exception('Account Model class could not be loaded');
				}

				/**
				 * register user
				 */
				if(account_model::register()) {
					$this->data->errors = "Account created successful";
				} else {
					$this->data->errors = "Something went wrong when attempting to create this account!";
				}


			} else {
				$this->data->errors = validate::$errors;
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
}