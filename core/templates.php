<?php
/**
 * Template class that loads action templates based on the current action page
 *
 * @version 0.0.01b00001
 * @package fframe
 * @copyright 2012 Lamonte & FFrame Development Team
 */


class Templates {

	/**
	 * empty action variables
	 */
	protected $data;
	protected $action;
	protected $page;
	protected $admin;

	/**
	 * setup default variables to determine which action templates to load
	 */
	public function __construct(stdClass $data, $action, $page, $admin = false) {
		$this->data 	= $data;
		$this->action 	= empty($action) ? 'index' : $action;
		$this->page 	= empty($page) ? 'default' : $page;
		$this->admin 	= $admin;
	}

	/**
	 * display all the templates once pieced together
	 */
	public function display() {
		$this->load_action_view();
		$this->load_base_view();
	}

	/**
	 * load current action page
	 */
	public function load_action_view() {

		/**
		 * attempt to load action file
		 */
		$template 		= new view($this->data);
		$viewfile 		= ($this->admin ? 'admin/' : null) . $this->action . '/' . $this->page;

		$this->action_html 	= $template->load_template($viewfile);

	}

	/**
	 * load body template and what ever data you want to include
	 */
	public function load_base_view() {

		/**
		 * setup a empty object to store data
		 */
		$data = new stdClass;
		$data->content 	= $this->action_html;

		/**
		 * this is where we'll have more code to add to the body template
		 */

		/**
		 * load body template and display it to the browser
		 */

		$templateFile 	= $this->admin ? 'adminbody' : 'body';
		$template 	= new view($data);

		$template->load_template($templateFile, true);
	}
}