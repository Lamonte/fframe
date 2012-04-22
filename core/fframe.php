<?php
/**
 * FFrame class is the root forum class that makes everything work
 *
 * @version 0.0.01b00001
 * @package fframe
 * @copyright 2012 Lamonte & FFrame Development Team
 */

/**
 * require the necessary classes
 */
require_once $basedir . 'core/actionobject.php';
require_once $basedir . 'core/templates.php';
require_once $basedir . 'core/view.php';
require_once $basedir . 'core/database.php';

class FFrame {

	/**
	 * put everything together so the forum will run
	 * 
	 * @return void
	 */
	public function run($admin =  false) {

		global $db;

		$this->db = $db;

		/**
		 * need to grab the action from url + its pages
		 */
		$this->action_from_url($admin);

		/**
		 * load action from url if action exists
		 */
		if($admin == true) {
			$this->load_action($admin);
		} else {
			$this->load_action();
		}	
	}

	/**
	 * this function attempts to load the correct action file
	 * based on the url the user is visiting
	 *
	 * @param boolean $admin - tell the script if we are in the admin panel
	 * @return void
	 */
	public function load_action($admin = false) {

		global $basedir;

		$includeDirectory 	= $admin == true ? 'admin/' : 'core/';
		$currentAction 		= preg_replace('/[^a-zA-Z0-9_-]+/i','', $_GET['_action']);
		$currentAction 		= empty($currentAction) ? 'index' : $currentAction;
		
		/**
		 * lets check if the current action file exists or attempt to load the default
		 */
		$actionFile 		= $basedir . $includeDirectory . 'actions/' . strtolower($currentAction) . '.php';

		if(!file_exists($actionFile)) {
			throw new exception('Could not load action class file: ' . $actionFile);
		}

		require_once $actionFile;

		/**
		 * lets check if the class exists
		 */
		$actionClass 		= $currentAction . '_action';

		if(!class_exists($actionClass)) {
			throw new exception('The following action class could not be loaded: "' . $actionClass . '" (' . $actionFile . ')');
		}

		$actionClass 		= new $actionClass($this->db);

		/**
		 * we need to load the appropriate page or attempt to trigger the default page method
		 */
		$currentPage 		= preg_replace('/[^a-zA-Z0-9_-]+/i','', $_GET['_page']);

		if(empty($currentPage)) {
			$pageMethod 	= 'page_index';
		} else {
			$pageMethod 	= 'page_' . $currentPage;
		}

		/**
		 * make sure method exists
		 */
		if(!method_exists($actionClass, $pageMethod)) {
			throw new exception('The following action method could not be called: "' . get_class($actionClass) . '::' . $pageMethod . '();"' . ' (' . $actionFile . ')');
		}

		/**
		 * call the class page method and send arguments if necessary
		 */
		$params 		= empty($_GET['_params']) ? array() : $_GET['_params'];

		call_user_func_array(array($actionClass, $pageMethod), $params);

		/**
		 * output the templates to the browser
		 */
		$templates = new Templates($actionClass->getData(), $currentAction, $currentPage, $admin);
		$templates->display();
	}

	/**
	 * get the appropriate action based on the url
	 * 
	 * when calling this function note it has to replace the url from the
	 * configuration to get the proper action else the code might error out
	 *
	 * @param boolean $admin - tell the script if we are in the admin panel
	 * @return void
	 */
	public function action_from_url($admin = false) {

		global $config;


		$currentUrl 		= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

		/**
		 * add a forward slash to the current url
		 */
		$currentUrl 		= preg_match("/\/$/", $currentUrl) ? $currentUrl : $currentUrl . '/';

		/**
		 * clean up any get data
		 */
		$currentUrl 		= preg_replace("/\?.*/", "", $currentUrl);

		/**
		 * now lets clean up the url and get the path only
		 */
		$currentUrl 		= str_replace($config[workspace]['install_path'] . ($admin ? 'admin/' : null), '', $currentUrl);
		
		/**
		 * lets remove the forward slash at the beginning & end of the url path
		 */
		$currentUrl 		= preg_replace('/^(\/)+|(\/)+$/i', '', $currentUrl);

		/**
		 * now lets remove any forward slashes that repeat
		 */
		$currentUrl 		= preg_replace('/(\/)+/', '/', $currentUrl);

		/**
		 * split the path into an array 
		 */
		if(empty($currentUrl)) {
			$currentUrl 	= array();
		} else {
			$currentUrl 	= @explode('/', $currentUrl);
		}

		/**
		 * setup the get values to be used later to 
		 * access the current action + params
		 */
		$_GET['_action'] 	= null;
		$_GET['_page']		= null;
		$_GET['_params'] 	= array();

		if(!empty($currentUrl)) {

			/**
			 * set the action value then unset it from the array
			 */
			$_GET['_action'] 	= $currentUrl[0];
			unset($currentUrl[0]);

			/**
			 * set the pages value then unset it from the array
			 */
			if(isset($currentUrl[1])) {
				$_GET['_page'] 	= $currentUrl[1];
				unset($currentUrl[1]);
			}

			/**
			 * now we are safe to loop the rest of the array if it isn't empty
			 * we just want to reset the array to start at value zero '0'
			 */
			if(!empty($currentUrl)) {

				$resetArray 		= array();

				foreach($currentUrl as $arrayValue) {
					$resetArray[] 	= $arrayValue;
				}

				$_GET['_params'] 	= $resetArray;
			}
		}
	}

	/**
	 * takes the exception object and then display the errors to the browser
	 *
	 * @param Exception $e
	 * @return void
	 */
	public static function getException($e) {

		echo $e->getMessage() . "<br />\n";
		echo "Error File: " . $e->getFile() . " (on line: " . $e->getLine() . ")<br />\n";
		echo "Trace Error:<br />\n";
		foreach($e->getTrace() as $trace) {
			echo "\t" . str_repeat("&nbsp;", 4);
			echo "File: (On line: " . $trace['line'] . ") " . $trace['file'] . ";  Class: " . $trace['class'] . '; Function: ' . $trace['function'] . "<br />\n";
		}

	}
}