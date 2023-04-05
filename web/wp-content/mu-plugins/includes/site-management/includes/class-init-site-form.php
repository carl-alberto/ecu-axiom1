<?php
namespace Site_Management;

use \Mu_Plugins\Form as Form;
use \Ldap\Ad_User as Ad_User;
use \WP_User as WP_User;


defined( 'ABSPATH' ) OR exit;

class Init_Site_Form extends Form
{
	protected $site = '';

	protected $plugins = true;

	protected $roles = true;

	protected $widgets = true;

	protected $itcs = true;

	protected $cs = true;

	protected $cron = true;

	protected $options = true;

	public function set_site($id) {
		$this->site = absint($id);
	}

	public function get_site() {
		return $this->site;
	}

	public function set_cs($value) {
		$this->cs = (bool) $value;
	}
	
	public function get_cs() {
		return $this->cs;
	}

	public function set_plugins($value) {
		$this->plugins = (bool) $value;
	}
	public function get_plugins() {
		return $this->plugins;
	}

	public function set_widgets($value) {
		$this->widgets = (bool) $value;
	}
	public function get_widgets() {
		return $this->widgets;
	}

	public function set_roles($value) {
		$this->roles = (bool) $value;
	}

	public function get_roles() {
		return $this->roles;
	}

	public function set_cron($value) {
		$this->cron = (bool) $value;
	}

	public function get_cron() {
		return $this->cron;
	}

	public function set_itcs($value) {
		$this->itcs = (bool) $value;
	}

	public function get_itcs() {
		return $this->itcs;
	}
	
	public function set_options($value) {
		$this->options = (bool) $value;
	}

	public function get_options() {
		return $this->options;
	}

	public function process() {

		$time_start = microtime(true);
		
		if($this->site == 0) {
			
			ini_set('memory_limit','350M');
			$sites = $this->get_all_blog_ids();
			
			foreach($sites as $site_id) {
				$this->results[] = $this->process_init($site_id);
				
			}
		} else {
			$this->results[] = $this->process_init($this->site);
			
		}
		$time_end = microtime(true);

		$execution_time = $time_end - $time_start;
		$this->message = '<br /><b>Total Execution Time:</b> '.$execution_time.' Seconds<br />';
		$this->message .= '<div id="message" class="updated notice is-dismissible"><p>Site Initalization Complete!  Check the results below for any issues ( search for "error").</p></div>';
	}

	/**
	 * Functions for initializing a site.  Used by the init site form.
	 */
	private function process_init( $blog_id )	{
		global $wpdb;

		$blog =  $wpdb->get_row( 'SELECT * FROM ' .  getenv('WORDPRESS_DB_NAME') . '.' . getenv('WORDPRESS_TABLE_PREFIX') . 'blogs where blog_id = ' . $blog_id );
		$site = new Init_Site();

		if($blog) {	
			
			switch_to_blog($blog_id);

			$result = '<h2><a href="https://' . $blog->domain . $blog->path . '" target="_blank">' . $blog->domain . $blog->path . '</a> Initialized</h2>';

			if ( $this->get_plugins()) {
				$result .= $site->activate_plugins();
			}

			if ( $this->get_options()) {
				$result .= $site->init_options();
			}

			if ( $this->get_cron() ) {
				$result .= $site->init_cron();
			}
			
			if ( $this->get_widgets()) {
				$result .= $site->init_widgets();
			}

			if ( $this->get_roles()) {
				$result .= $site->init_roles();
			}

			if ( $this->get_itcs()) {
				$result .= $site->init_itcs_users();
			}

			if ( $this->get_cs()) {
				$result .= $site->init_cs_users();
			}

			// Switch back to blog
			restore_current_blog();
			
		} else {
			$result = 'Invalid Blog ID: ' . $blog_id;
		}
		
		return $result;
	}	
}