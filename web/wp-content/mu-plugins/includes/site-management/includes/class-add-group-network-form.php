<?php

namespace Site_Management;
use \Mu_Plugins\Form as Form;

defined( 'ABSPATH' ) OR exit;

/**
 * Provides a forms functionality ( data validation/sanitation, submission process, error/result reporting).
 * This is not meant to be form generator.   Also provides some data retrieval functions that exclude things like the info.ecu.edu site
 * or the administrator role.    
 */
class Add_Group_Network_Form extends Form
{
	protected $recursive = true;

	protected $ad_groups = '';

	protected $role = '';

	protected $blogs = [];

	public function set_recursive($recursive) {
		$this->recursive = (bool) $recursive;
	}

	public function get_recursive() {
		return $this->recursive;
	}

	public function set_ad_groups($groups) {
		$this->ad_groups = sanitize_text_field($groups);
	}

	public function get_ad_groups() {
		return $this->ad_groups;
	}

	public function set_role($r) {
		$this->role = sanitize_text_field($r);
	}

	public function get_role() {
		return $this->role;
	}

	public function set_blogs($b) {
		$this->blogs = array_map('absint', $b);
	}

	public function get_blogs() {
		return $this->blogs;
	}

	public function process() {

		ini_set('memory_limit','350M');

		$this->errors = [];
		$this->results = [];

		//blogs are required
		if (empty($this->blogs)) {
			$this->errors[] = 'At least 1 blog is required!';
		} 	

		//group is required
		if (empty($this->ad_groups)) {
			$this->errors[] = 'AD Group is required!';
		} 

		if(empty($this->errors)) {
		
			$groups = explode(',', $this->ad_groups);
			
			// 0 is All Blogs
			if($this->blogs[0] == 0) {
				$blogs = $this->get_all_blog_ids();
			} else {
				$blogs = $this->blogs;
			}	

			foreach($groups as $group) {
				$group = new \Ldap\Ad_Group(trim($group));
				if($group->is_valid()) {
					foreach ($blogs as $blog_id) {						
						$this->results = $group->add_group_to_blog($blog_id, $this->role, $this->recursive);
					}
				} else {
					$this->errors[] =  $group . ' is an invalid AD group';
				}
			}
		} 

		if (!empty($this->errors)) {
			foreach ($this->errors as $err)	{
				$this->message .= '<div id="message" class="error notice is-dismissible"><p><strong>Error! </strong>'.$err.'</p></div>';
			}
		} else {
			$this->message = '<div id="message" class="updated notice is-dismissible"><p><strong>Success! </strong> Users added as expected!</p></div>';
		} 
	}
}