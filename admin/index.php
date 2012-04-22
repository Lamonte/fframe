<?php
/**
 * Administration panel index
 */

include_once '/../includes.php';

try {

/**
 * lets try connecting to the database
 */
$db = new Database();

if(!permissions::instance()->check("admin_access")) {
	redirect("account/login");
}

/**
 * initialize the forum software
 */
$fframe = new FFrame();
$fframe->run(true);

} catch( Exception $e ) {
	FFrame::getException($e);
}