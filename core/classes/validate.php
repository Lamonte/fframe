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
 * @package	validate
 * @copyright	Copyright (c) 2012 Lamonte & Helpers
 * @license	http://www.opensource.org/licenses/lgpl-3.0.html GNU General Public License, version 3 (GPL-3.0)
 * @version 	0.0.1 (Designating development stage http://en.wikipedia.org/wiki/Software_versioning)
 */

class validate {
	
	/**
	 * static array that holds error messages
	 */
	public static $errors 	= array();

	/**
	 * error messages
	 */
	public $errors_text 	= array(
		'validate::rule_empty' => '$1 was left empty',
	);

	/**
	 * stored rules
	 */
	public $rules 		= array();

	/**
	 * set validation rule to input
	 *
	 * the $rule array will be a series of private functions that will be called
	 * in a loop iteration.  If singled is set to true this means it'll only 
	 * attempt to show the first error in the array sequence then stopping. You can
	 * have multiple set_rule calls to the same function
	 */
	public function set_rules($input, $rule = array(), $singled = false) {
		$this->rules[] = array($input, $rule, $singled);
	}

	/**
	 * loops through rules array, then applies error messages to the static error
	 * variable if necessary.
	 *
	 * @return boolean - checked versus empty status of errors array
	 */
	public function check() {

		 foreach($this->rules as $rule) {

		 	/**
		 	 * double check if input exists lol
		 	 */
		 	if(!isset($_POST[$rule[0]])) {
		 		continue;
		 	}

		 	/**
		 	 * loop through all the rules for this input and check if its a
		 	 * string or an array.  string (attempt to check if rule functions exists)
		 	 * else if array call back, try loading call back function
		 	 */
		 	foreach($rule[1] as $rules_array) {

		 		if(is_string($rules_array)) {

		 			/**
		 			 * lets try calling the method and checking the return value
		 			 * should be noted return value has to return true for us to
		 			 * add to the errors array.
		 			 */
		 			if(is_callable(array($this, 'rule_' . $rules_array), true, $callable)) {
		 				$return = call_user_func(array($this, 'rule_' . $rules_array), $rule[0]);

		 				/**
		 				 * if error returns true after calling the class method
		 				 */
		 				if($return) {

		 					/**
		 					 * lets give a default text to array error messages that aren't set
		 					 */
		 					if(!isset($this->errors_text[strtolower($callable)])) {
		 						$this->errors_text[strtolower($callable)] = strtolower($callable) . ' has no error text';
		 					}

		 					self::$errors[] = str_replace('$1', $rule[0], $this->errors_text[strtolower($callable)]);
		 					
		 					/**
		 					 * check if we want the error messages to display one by one per set rule
		 					 */
		 					if($rule[2] == true) {
		 						break;
		 					}
		 				}
		 			}
		 		}

		 		/**
		 		 * the only difference between this chunk and the chunk of code above is it checks if its an array
		 		 * then it attempts to call a function/class function outside of class and then attempt to do simlar 
		 		 * checks as above lol
		 		 */
		 		if(is_array($rules_array)) {

		 			if(is_callable($rules_array['callback'], true, $callable)) {

		 				$rules_array['params'] 	= !isset($rules_array['params']) || !is_array($rules_array['params']) ? array() : $rules_array['params'];
		 				$return 		= call_user_func_array($rules_array['callback'], array_merge(array($rule[0]), $rules_array['params']));
		 				
		 				/**
		 				 * if error returns true after calling the class method
		 				 */
		 				if($return) {

		 					/**
		 					 * lets give a default text to array error messages that aren't set
		 					 */
		 					if(!isset($this->errors_text[strtolower($callable)])) {
		 						$this->errors_text[strtolower($callable)] = strtolower($callable) . ' has no error text';
		 					}

		 					self::$errors[] = str_replace('$1', $rule[0], $this->errors_text[strtolower($callable)]);
		 					if($rule[2] == true) {
		 						break;
		 					}
		 				}
		 			}
		 		}
		 	}
		 }

		 /**
		  * return true if nothing weird happens
		  */
		 return (count(self::$errors) > 0 ? true : false);
	}

	public function rule_empty($input) {
		return !isset($_POST[$input]) || empty($_POST[$input]) ? true : false;
	}

	public function errors($array = false) {
		if($array) {
			return self::$errors;
		}

		return "<ul><li>" . implode("</li><li>", self::$errors) . "</li></ul>";
	}
}