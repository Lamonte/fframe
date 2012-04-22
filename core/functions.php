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