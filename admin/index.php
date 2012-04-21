<?php

include_once '/../includes.php';

try {

/**
 * lets try connecting to the database
 */
$db = new Database();

/**
 * initialize the forum software
 */
$fframe = new FFrame();
$fframe->run(true);

} catch( Exception $e ) {
	echo $e->getMessage() . "<br />\n";
	echo "Error File: " . $e->getFile() . " (on line: " . $e->getLine() . ")<br />\n";
	echo "Trace Error:<br />\n";
	foreach($e->getTrace() as $trace) {
		echo "\t" . str_repeat("&nbsp;", 4);
		echo "File: (On line: " . $trace['line'] . ") " . $trace['file'] . ";  Class: " . $trace['class'] . '; Function: ' . $trace['function'] . "<br />\n";
	}
}