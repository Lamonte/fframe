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
 * @package	View
 * @copyright	Copyright (c) 2012 Lamonte & Helpers
 * @license	http://www.opensource.org/licenses/lgpl-3.0.html GNU General Public License, version 3 (GPL-3.0)
 * @version 	0.0.1 (Designating development stage http://en.wikipedia.org/wiki/Software_versioning)
 */

class view {

	protected $data;
	
	/**
	 * store any passed data to the class to be usedd and extracted later
	 */
	public function __construct($data) {
		$this->data = $data;
	}

	/**
	 * load a template
	 */
	public function load_template($template, $output = false) {

		global $config, $basedir;

		$templatePath = $basedir . 'styles/' . $config[workspace]['style'] . '/' . $template . '.php';

		/**
		 * lets make sure the file exists
		 */
		if( !file_exists($templatePath)) {
			throw new exception('The template view file that you were trying to load does not exist: ' . 'styles/' . $config[workspace]['style'] . '/' . $template . '.php');
		}

		/**
		 * convert data into array and extract variables
		 */
		$this->data = (array) $this->data;

		extract($this->data);

		/**
		 * turn on output buffering
		 */
		ob_start();

		/**
		 * require the template file
		 */
		require $basedir . 'styles/' . $config[workspace]['style'] . '/' . $template . '.php';

		/**
		 * store template view into a variable
		 */
		$this->template_data = ob_get_contents();

		/**
		 * clear output buffer and turn it off
		 */
		ob_end_clean();

		if(false === $output) {
			return $this->template_data;
		}

		echo $this->template_data;
	}
}