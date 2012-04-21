<?php
/**
 * FFrame class is the root forum class that makes everything work
 *
 * @version 0.0.01b00001
 * @package fframe
 * @copyright 2012 Lamonte & FFrame Development Team
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
			throw new exception('The template view file that yuou were trying to load does not exist: ' . 'styles/' . $config[workspace]['style'] . '/' . $template . '.php' );
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