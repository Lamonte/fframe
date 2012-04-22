<?php

/**
 * global functions
 */

/**
 * redirect a user to the provided location or send them to a page
 * without the website using the installation path as a base
 *
 * @param string $location - ex. http://google.com or action/pagename/withparamseven
 * @return void
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
 * gets the configuration url and you can append any action/pages to the end
 * if you like
 *
 * @param string $path
 * @return string
 */
function get_url($path = null) {

	global $config;

	return $config[workspace]['install_path'] . $path;
}