<?php

/**
 * global functions
 */

function redirect($location) {

	global $config;

	if(preg_match("/^http/i", $location)) {
		$location = $location;
	} else {
		$location = $config[workspace]['install_path'] . $location;
	}

	header("Location: " . $location);
	exit();
}

/**
 * get configuration url
 */
function get_url($path = null) {

	global $config;

	return $config[workspace]['install_path'] . $path;
}