<?php
/**
 * Database Driver Class that helps us connect to the database using
 * multiple database drivers.
 *
 * @version 0.0.01b00001
 * @package fframe
 * @copyright 2012 Lamonte & FFrame Development Team
 */

class mysql_pdo {
	public $driver 		= null;
	public $password 	= null;
	public $username 	= null;
	public function __construct($user, $pass, $tble, $host) {
		$this->driver 	= "mysql:host=$host;dbname=$tble";
		$this->password = $pass;
		$this->username = $user;
	}
}