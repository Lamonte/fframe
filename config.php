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