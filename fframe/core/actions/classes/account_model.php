<?php

class account_model {
	
	/**
	 * register a new user into the database
	 */
	public static function register() {

		global $db;

		$username 	= r::post("username", true);
		$password 	= md5(r::post("password"));

		$prepare 	= $db->prepare("INSERT INTO `users` (`username`,`password`) VALUES(?, ?)");
		$result 	= $prepare->execute(array($username, $password));

		return !$result ? false : true;
	}
}