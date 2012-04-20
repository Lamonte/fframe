<?php
/**
 * Index file of the fframe forum system
 *
 * @version 0.0.01b00001 - Versioning will start at 0.0.01 so it'll take longer to get to a 1.0 release
 * @package fframe
 * @copyright 2012 Lamonte Harris & FFrame Development Team
 */

/**
 *Actions:
 *
 * /		- index action, list all the forums and information about them
 * /forum/ 	- forum action
 * /thread/ 	- thread action
 * /profile/ 	- profile action
 * /admin/ 	- is another tough one 
 */

/**
 * base directory so there aren't any issues with including files
 */
$basedir = strtolower(str_replace('\\', '/', __DIR__)) . '/';

/**
 * load the configuration file
 */
require_once $basedir . 'config.php';

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


try {

/**
 * lets try connecting to the database
 */
$db = new Database();

/**
 * initialize the forum software
 */
$fframe = new FFrame();
$fframe->run();

} catch( Exception $e ) {
	echo $e->getMessage() . "<br />\n";
	echo "Error File: " . $e->getFile() . " (on line: " . $e->getLine() . ")<br />\n";
	echo "Trace Error:<br />\n";
	foreach($e->getTrace() as $trace) {
		echo "\t" . str_repeat("&nbsp;", 4);
		echo "File: (On line: " . $trace['line'] . ") " . $trace['file'] . ";  Class: " . $trace['class'] . '; Function: ' . $trace['function'] . "<br />\n";
	}
}