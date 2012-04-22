<?php
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
