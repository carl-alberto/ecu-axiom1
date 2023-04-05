<?php

/**
 * Disable Login
 *
 * @package     DisableLogin
 * @author      ATWebDev
 * @copyright   2019 East Carolina University
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Disable-Login
 * Plugin URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Description: Will only allow users with Administrator or ITCS Support roles to login to site.
 * Version:     1.0.0
 * Author:      ATWebDev
 * Author URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Text Domain: disable-login
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace Disable_Login;
use \WP_Error as WP_Error;

defined( 'ABSPATH' ) OR exit;

if(is_admin()) {
	include( __DIR__ . '/includes/admin.php');
}

//Check if site login is disabled.
if(get_option('wp-site-management-site-disable-login') || get_site_option('wp-site-management-network-disable-login', false, false)) {
	add_filter('login_message', __NAMESPACE__ . '\disabled_login_message');
	add_filter('authenticate', __NAMESPACE__ . '\disable_login_check', 100, 3);
}

/**
 * Used to return the login disable message to be displayed above the login form when the login is 
 * disabled. 
 *
 * @return string	Login disabled message
 */
function disabled_login_message() {
    return "
    <h1 class='login-msg'>
    	Login Disabled
    </h1>
    <br />
    <p id='subtitle'>
    	We are working on your website and had to disable the login.  Please submit a <a href='https://ecu.teamdynamix.com/TDClient/Requests/ServiceCatalog?CategoryID=3482'>support ticket</a> if you need assistance.
    </p><br />";
}


/**
 * Checks if disable login is turned on and keeps user from logging in if they are not admins or itcs support.  
 *
 * @param  object $user     	A WP User object or NULL normally, but ignored by this function.
 * @param  string $username 	The username that was in the login field
 * @param  string $password 	The password that was in the passwords field
 * @return WP User | WP Error	Returns either a WP User object if the user validates or a WP Error object if there was an error validating
 */
function disable_login_check( $user, $username = NULL, $password = NULL ) {

	// If it is a wp error object then authentication/authorization failed and they should not be logged in.
	if(is_wp_error($user)) {
		return $user;
	}

	$disable_site_login = get_option('wp-site-management-site-disable-login');
	$disable_network_login = get_site_option('wp-site-management-network-disable-login', false, false);

	/** 
	 * If login disabled then stop login process and show approriate message for 
	 * non super admins and itcs support users
	 */
	if($disable_site_login || $disable_network_login) {
		if(isset($user) && !is_super_admin($user->ID) && !in_array('itcs_support', $user->roles)) {
			return new WP_Error( 'error',
       	 	__( 'Sorry, no logins are allowed right now.' ));
       	}
    }

    return $user;	
}