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
 * start session
 */
session_start();

/**
 * base directory so there aren't any issues with including files
 */
$basedir = strtolower(str_replace('\\', '/', __DIR__)) . '/';

/**
 * load the configuration file
 */
require_once $basedir . 'config.php';

/**
 * include global functions
 */
require_once $basedir . 'core/functions.php';

/**
 * require the fframe class
 */
require_once $basedir . 'core/fframe.php';

/**
 * Autoload any classes that aren't included
 */
function __autoload( $classname ) {
	
	global $basedir;

	$folders = array(
		'core/classes/'
	);
	
	/* write in a plugin/hooks system to make code even more flexible -> for example
		being able to hook this auto load and add in more fodlers without actually touching the source code */
	
	foreach( $folders as $folder ) {
		if( file_exists( $basedir . $folder . strtolower( $classname ) . ".php" ) ) {
			require_once $basedir . $folder . strtolower( $classname ) . ".php";
			return true;
		}
	}
}
