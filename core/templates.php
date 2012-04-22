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