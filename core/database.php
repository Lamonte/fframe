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
 * @package	FFrame
 * @copyright	Copyright (c) 2012 Lamonte & Helpers
 * @license	http://www.opensource.org/licenses/lgpl-3.0.html GNU General Public License, version 3 (GPL-3.0)
 * @version 	0.0.1 (Designating development stage http://en.wikipedia.org/wiki/Software_versioning)
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
