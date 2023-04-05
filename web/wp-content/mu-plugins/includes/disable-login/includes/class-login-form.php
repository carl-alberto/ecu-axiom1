<?php

namespace Disable_Login;
use \Mu_Plugins\Form as Form;

defined( 'ABSPATH' ) OR exit;

class Login_Form extends Form
{
	protected $disabled = 0;

	public function set_disabled($value) {
		$this->disabled = absint($value);
	}

	public function get_disabled() {
		return $this->disabled;
	}

	public function init_site() {
		$this->set_disabled(get_option('wp-site-management-site-disable-login'));
	}

	public function init_network() {
		$this->set_disabled(get_site_option('wp-site-management-network-disable-login'));
	}

	public function process() {

		update_option('wp-site-management-site-disable-login', $this->get_disabled());

		if($this->get_disabled()) {
			$this->message = '<div id="message" class="updated notice is-dismissible"><p><strong>Success! </strong> Users are NOT be able to login to this site!</p></div>';	
		} else {
			$this->message = '<div id="message" class="updated notice is-dismissible"><p><strong>Success! </strong> Users are ABLE to login to this site!</p></div>';		
		}

	}

	public function process_network() {

		update_site_option('wp-site-management-network-disable-login', $this->get_disabled());

		if($this->get_disabled()) {
			$this->message = '<div id="message" class="updated notice is-dismissible"><p><strong>Success! </strong> Users are NOT be able to login to this multisite!</p></div>';	
		} else {
			$this->message = '<div id="message" class="updated notice is-dismissible"><p><strong>Success! </strong> Users are ABLE to login to this multisite!</p></div>';		
		}

	}
}