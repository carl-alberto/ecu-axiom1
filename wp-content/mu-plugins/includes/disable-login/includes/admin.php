<?php

namespace Disable_Login;
Use \Mu_Plugins\Form as Form;

spl_autoload_register(function ($class_name) {

	/**
	 * Because of past negative experience did not want to rely on a convention of file/class names
	 * to be able to load a file.   Also didn't want to do parsing of the class title to determine file
	 * path.   So I settled on a array lookup with file path specified.
	 */
	$class_map = [		

		'Disable_Login\Login_Form' => __DIR__ . '/class-login-form.php',

	];

	if(array_key_exists($class_name, $class_map)) {
	    require_once $class_map[$class_name];
	}
});


/**
 * This action is used to add extra submenus and menu options to the admin panel's menu structure.
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/admin_menu
 */
add_action('admin_menu', __NAMESPACE__ . '\site_admin_menu',20,0);
function site_admin_menu() {

	// Disable Login Site Menu
	add_options_page( 'Disable Login', 'Disable Login', 'itcs_support', 'wp-disable-login', __NAMESPACE__ . '\login_site_form' );
}
	/**
	 * Admin Menu Callback Functions
	 */
	function login_site_form() {
		if(!$form = Form::get_state()) 	$form = new Login_Form();
		$form->init_site();
		include( __DIR__ . '/partials/login-site-form.php');
	}

/**
 * This action is used to add extra submenus and menu options to the network admin panel's menu structure.
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/network_admin_menu
 */
add_action('network_admin_menu', __NAMESPACE__ . '\network_admin_menu',20,0);
function network_admin_menu() {
	add_submenu_page('settings.php', 'Disable Login', 'Disable Login', 'manage_network', 'disable-login', __NAMESPACE__ . '\login_network_form');
}

	/**
	 * Network Admin Menu Callback Functions
	 */
	function login_network_form() {
		if(!$form = Form::get_state()) 	$form = new Login_Form();
		$form->init_network();
		include( __DIR__ . '/partials/login-network-form.php');
	}
	

/**
 * Processes form submissions for the admin screen
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/admin_post_(action)
 */

add_action( 'admin_post_site_login_form', __NAMESPACE__ . '\site_login_form_process', 10, 0 );
function site_login_form_process() {

	// Process any submission
	if (check_admin_referer( 'wp_management_login', 'wp-management-login' ) ) {
		$form = new Login_Form();
		$form->set_disabled($_POST['disable_login']);
		$form->process();
		$form->set_state();
	}
	wp_redirect( admin_url('admin.php?page=wp-disable-login') );
    exit;
}

/**
 * Processes form submissions for the network admin screen
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/admin_post_(action)
 */
add_action( 'admin_post_network_login_form', __NAMESPACE__ . '\network_login_form_process', 10, 0 );
function network_login_form_process() {

	// Process any submission
	if (check_admin_referer( 'wp_management_network_login', 'wp-management-network-login' ) ) {
		$form = new Login_Form();
		$form->set_disabled($_POST['disable_login']);
		$form->process_network();
		$form->set_state();
	}
	wp_redirect( admin_url('network/settings.php?page=disable-login') );
    exit;
}
