<?php

namespace Site_Management;
use \Mu_Plugins\Form as Form;

defined( 'ABSPATH' ) OR exit;

class Add_User_Form extends Form
{
	protected $pirate_ids = '';

	protected $role = '';

	protected $blogs = [];

	public function set_pirate_ids($ids) {
		$this->pirate_ids = sanitize_text_field($ids);
	}

	public function get_pirate_ids() {
		return $this->pirate_ids;
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

		if (empty($this->pirate_ids)) {
			$this->errors[] = 'At least one pirate id is required!';
		} 

		if(empty($this->errors)) {
			//create user array
			$users = explode(',', $this->pirate_ids);

			// 0 is All Blogs
			if($this->blogs[0] == 0) {
				$blogs = $this->get_all_blog_ids();
			} else {
				$blogs = $this->blogs;
			}

			foreach ($users as $id) {
				
				$ad_user = new \Ldap\Ad_User(trim($id));			

				if($ad_user->is_valid()) {
					foreach ($blogs as $blog_id) {
						$result = $ad_user->add_user_to_blog($blog_id, $this->role);
						if(is_wp_error($result)) {
							$this->errors[] = $result->get_error_message();
						} else {
							$this->results[] = $result;
						}
					}
				} else {
					$this->errors[] =  $id . ' is an invalid pirate id';
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