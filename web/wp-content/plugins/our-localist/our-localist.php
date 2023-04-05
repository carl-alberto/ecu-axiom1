<?php
	namespace OUR\LOCALIST;

	/*
	Plugin Name: Our Localist Calendar
	Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
	Description: The plugin will allow users to show events from their localist calender in widget and content areas.
	Version:     1.0
	Author:      Academic Technologies Web Consulting Team
	Author URI:  https://www.ecu.edu/itcs/
	License:     GPL2

	WP Localist Calendar is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	any later version.

	WP Localist Calendar is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with WP Localist Calendar. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
	*/

	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	define( 'WP_LOCALIST_OPTION', 'wp-localist-calendar-url' );

	define ('WP_LOCALIST_BLOCK_HTTP', (WP_HTTP_BLOCK_EXTERNAL && (strpos(WP_ACCESSIBLE_HOSTS, 'calendar.ecu.edu') !== false)));

	// Setup the shortcode
	// Disabling API calls prevents actual shortcode and widget from being loaded
	// Instead registers widget and shortcode that displays downtime message
	if(!WP_LOCALIST_BLOCK_HTTP){
		require_once(plugin_dir_path( __FILE__ ) . 'includes/shortcode.php');
	} else {
		// Downtime shortcode / widget
		require_once(plugin_dir_path( __FILE__ ) . 'includes/inactive/inactive.php');
	}

	// Setup the widget
	require_once(plugin_dir_path( __FILE__ ) . 'includes/widget.php');

	// Enqueue the global styles
	add_action('wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_global_styles', 15, 0);



	// Includes global if API calls are disabled to still have admin menu settings available
	// Global file included in shortcode and widget
	if(WP_LOCALIST_BLOCK_HTTP){
		require_once(plugin_dir_path( __FILE__ ) . 'includes/global.php');
	}
	if( is_admin() ) {

		// Include administrative functions
		require_once(plugin_dir_path( __FILE__ ) . 'includes/admin.php');

		// Enqueue the admin styles
		add_action('init', __NAMESPACE__ . '\enqueue_admin_styles', 15, 0);

		// Register the uninstall function.
	 	register_uninstall_hook( plugin_dir_path( __FILE__ ), __NAMESPACE__ . '\uninstall_plugin' );


		// Register the menu for the plugin
		if(is_multisite()) {
			add_action( 'network_admin_menu', function(){
				add_submenu_page('settings.php', 'Localist Calendar', 'Localist Calendar', 'manage_options', 'wp-localist-network', __NAMESPACE__ . '\settings_form'  );
			},10,0);
		} else {
			add_action('admin_menu', function(){
				add_submenu_page( 'options-general.php', 'Localist Calendar', 'Localist Calendar', 'manage_options', 'wp-localist-admin', __NAMESPACE__ . '\settings_form' );
			},10,0);
		}
	}