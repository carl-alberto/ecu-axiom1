<?php

namespace Site_Management;
use \Mu_Plugins\Form as Form;

defined( 'ABSPATH' ) OR exit;

class Init_Multisite_Form extends Form
{
	public function process() {

		$blog_id = get_current_blog_id();
		if($blog = get_blog_details($blog_id)) {

			$result = '<h2><a href="' . $blog->siteurl . '" target="_blank">' . $blog->domain . ' Multi-Site Initialized</a></h2>';

			$this->results[] = $this->init_plugins();
			$this->results[] = $this->init_options();
			$site = new Init_Site();
			$this->results[] = $site->init_roles();
			$this->results[] = $site->init_itcs_users();
			$this->results[] = $site->init_options();
	
		} else {
			$this->errors[] = 'Error getting blog information.';
		}
	}

	private function init_options() {
		$result = '<h3>Set network options</h3><ul>';

		update_site_option( 'add_new_users', 1);
		$result .= '<li>Add Users enabled for site administrators.</li>';

		$menu_perms = get_site_option( 'menu_items' );
		$menu_perms['plugins'] = 1;
		update_site_option( 'menu_items', $menu_perms);
		$result .= '<li>Plugin menu enabled for site administrators.</li>';

		$result .= '</ul>';

		return $result;
	}

	private function init_plugins()	{
		$result = '<h3>Network Activate Plugins</h3><ul>';
		// array ( plugin dir / plugin file )
		$plugins = array(
			'redis-cache/redis-cache.php',
			'akismet/akismet.php'			
		);
		
		foreach ($plugins as $plugin) {
			if( ! is_plugin_active( $plugin  ) ) {
				$error = activate_plugin($plugin, '', true );
				if ( !is_wp_error( $error ) ) {
					$result .= '<li>' . $plugin . ' activated.</li>';
				} else {
					$result .= '<li>Error: ' . $plugin . ' ' . $error->get_error_message() . '</li>';
				}
		    } else {
				$result .= '<li>' . $plugin . ' already activated.</li>';
		    }
		}
		$result .= '</ul>';

		$result .= '<h3>Site Activate Plugins</h3><ul>';
		// array ( plugin dir / plugin file )
		$plugins = array(
			'wp-cron/wp-cron.php',
			'so-widgets-bundle/so-widgets-bundle.php', // Needed for site init widget script to work when executing for other sites.
			'user-role-editor-pro/user-role-editor-pro.php',
			'wp-crontrol/wp-crontrol.php',
		);
		
		foreach ($plugins as $plugin) {
			if( ! is_plugin_active( $plugin  ) ) {
				$error = activate_plugin($plugin );
				if ( !is_wp_error( $error ) ) {
					$result .= '<li>' . $plugin . ' activated.</li>';
				} else {
					$result .= '<li>Error: ' . $plugin . ' ' . $error->get_error_message() . '</li>';
				}
		    } else {
				$result .= '<li>' . $plugin . ' already activated.</li>';
		    }
		}
		$result .= '</ul>';

		return $result;
	}	
}