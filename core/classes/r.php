<?php

class r {

	/**
	 * retrieve _POST data
	 *
	 * @param string $Input - request name from _GET/_POST data
	 * @param boolean $special_chars - do we want to escape the string for special chars or not?
	 * @return string|boolean - will return false on failure to sanitize string input
	 */
	public static function post($input, $special_chars = false) {
		$output = self::filter_content($input, $special_chars, INPUT_POST);
		return $output;
	}

	/**
	 * retrieve _GET data
	 *
	 * @param string $Input - request name from _GET/_POST data
	 * @param boolean $special_chars - do we want to escape the string for special chars or not?
	 * @return string|boolean - will return false on failure to sanitize string input
	 */
	public static function get($input, $special_chars = false) {
		$output = self::filter_content($input, $special_chars, INPUT_GET);
		return $output;
	}

	/**
	 * filter get/post data 
	 *
	 * @param string $Input - will be the input name of either $_GET or $_POST data
	 * @param boolean $special_chars - do we want to escape the string for special chars or not?
	 * @param const $type - filter input type INPUT_GET or INPUT_POST
	 * @return string|boolean - will return false on failure to sanitize string input
	 */
	public static function filter_content($input, $special_chars = false, $type) {
		
		switch($type) {
			case INPUT_GET:
				$string = &$_GET[$input];
				$string = urldecode($string);
			break;
			case INPUT_POST:
				$string = &$_POST[$input];
			break;
		}

		/**
		 * dirty ass magic quotes begone!
		 */
		if(@get_magic_quotes_gpc()) {
			$string = filter_input($type, $input, FILTER_SANITIZE_MAGIC_QUOTES);
		}

		/**
		 * do we trim special characters?
		 */
		if($special_chars) {
			$string = filter_input($type, $input, FILTER_SANITIZE_SPECIAL_CHARS);
		}

		return $string;
	}
}