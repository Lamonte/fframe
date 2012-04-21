<?php


/**
 * Database Driver Class that helps us connect to the database using
 * multiple database drivers.
 *
 * @version 0.0.01b00001
 * @package fframe
 * @copyright 2012 Lamonte & FFrame Development Team
 */

class Database extends PDO {

	/**
	 * initialize the database connection
	 */
	public function __construct() {

		global $config, $basedir;

		/**
		 * lets attempt to get the pdo driver from config and create a new pdo object
		 */
		$driverFile 	= $basedir . 'core/drivers/' . $config[workspace]['db_type'] . '.php';

		if(!file_exists($driverFile)) {
			throw new exception('Database driver "' . $driverFile . '" does not exist');
		}

		require_once $driverFile;

		$driverClass 	= $config[workspace]['db_type'] . '_pdo';

		if(!class_exists($driverClass)) {
			throw new exception('The driver class does not exist: ' . $driverFile);
		}

		$settings 	= $config[workspace];
		$driverClass 	= new $driverClass($settings['db_user'], $settings['db_pass'], $settings['db_tble'], $settings['db_host']);

		/**
		 * lets try creating a new pdo instance and turn this class into a pdo object
		 */
		parent::__construct($driverClass->driver, $driverClass->username, $driverClass->password);
	}	

}
