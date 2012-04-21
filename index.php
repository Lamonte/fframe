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

include_once "includes.php";

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