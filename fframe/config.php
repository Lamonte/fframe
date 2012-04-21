<?php
/**
 * Configuration file for FForum
 *
 * Make sure you edit the database settings in order for your software to work properly.
 * if you don't do so, your forum might throw up unexpected errors. 
 *
 * @category FForum software
 * @package config
 * @version 0.0.01b00001
 * @copyright 2012 Lamonte Harris & FForum Development Team
 */

/**
 * define the default workspace which allows you to have multiple config
 * settings so you cans witch between live and development environment without
 * much setting changes
 */

define('workspace', 'workspace');

$config = array(
	'workspace' => array(
		'style' 	=> 'default',
		'install_path' 	=> 'http://localhost/fframe/',
		'db_host' 	=> 'localhost',
		'db_user' 	=> 'root',
		'db_pass' 	=> '',
		'db_tble' 	=> 'forum',
		'db_type' 	=> 'mysql',
	)
);