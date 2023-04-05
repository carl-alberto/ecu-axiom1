<?php

namespace Site_Management;

Use \Mu_Plugins\Form as Form;

defined( 'ABSPATH' ) OR exit;


// Autoloader for the plugin
spl_autoload_register(function ($class_name) {

	/**
	 * Because of past negative experience did not want to rely on a convention of file/class names
	 * to be able to load a file.   Also didn't want to do parsing of the class title to determine file
	 * path.   So I settled on a array lookup with file path specified.
	 */
	$class_map = [		

		'Site_Management\Add_User_Form' 			=> __DIR__ . '/class-add-user-form.php',
		'Site_Management\Add_Group_Network_Form' 	=> __DIR__ . '/class-add-group-network-form.php',
		'Site_Management\Init_Site_Form'			=> __DIR__ . '/class-init-site-form.php',
		'Site_Management\Init_Site'					=> __DIR__ . '/class-init-site.php',
		'Site_Management\Init_Multisite_Form'		=> __DIR__ . '/class-init-multisite-form.php',
		'Site_Management\Create_Site_Form'			=> __DIR__ . '/class-create-site-form.php',
	];

	if(array_key_exists($class_name, $class_map)) {
	    require_once $class_map[$class_name];
	}
});

/**
 * Removes plugin menu for users.
 *
 * @see https://developer.wordpress.org/reference/hooks/admin_menu/
 */
add_action( 'admin_menu', __NAMESPACE__ . '\remove_menus' );
function remove_menus(){
    // Have to remove this because the site origin plugin uses the manage_options capability 
    // to add a menu to the pluings page.   This menu should only be shown to ITCS Support.
    if(!current_user_can( 'activate_plugins' ))   
        remove_menu_page( 'plugins.php' );             
}

/**
 * Admin redirects for hijacked wordpress and plugin menus
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
 */	
add_action( 'admin_init',  __NAMESPACE__ . '\admin_redirects' );
function admin_redirects(){
	switch($_SERVER['PHP_SELF']){

		case '/wp-admin/network/site-new.php':
			wp_redirect(admin_url('network/admin.php?page=create-site'));
			break;

		case '/wp-admin/network/user-new.php':
			wp_redirect(admin_url('network/users.php?page=wp-add-user-network'));
			break;

		case '/wp-admin/user-new.php':
			wp_redirect(admin_url('admin.php?page=wp-add-user-site'));
			break;
	}
}

/**
 * This action is used to add extra submenus and menu options to the admin panel's menu structure.
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/admin_menu
 */
add_action('admin_menu', __NAMESPACE__ . '\site_admin_menu',20,0);
function site_admin_menu() {

	// Remove WP add new user
	remove_submenu_page( 'users.php', 'user-new.php' );

	// User Menu on a site
	add_users_page('Add Users', 'Add Users', 'itcs_support', 'wp-add-user-site', __NAMESPACE__ . '\add_user_site_form' );
}
	/**
	 * Admin Menu Callback Functions
	 */
	function add_user_site_form() {
		if(!$form = Form::get_state()) 	$form = new Add_User_Form();
		include( __DIR__ . '/partials/add-user-site-form.php');
	}

/**
 * This action is used to add extra submenus and menu options to the network admin panel's menu structure.
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/network_admin_menu
 */
add_action('network_admin_menu', __NAMESPACE__ . '\network_admin_menu',20,0);
function network_admin_menu() {

	// Remove WP add new user
	remove_submenu_page( 'users.php', 'user-new.php' );

	// User Menu on the network screen
	add_users_page(	'Add Users', 'Add Users', 'itcs_support', 'wp-add-user-network', __NAMESPACE__ . '\add_user_network_form' );
	add_users_page(	'Add AD Groups', 'Add AD Groups', 'itcs_support', 'wp-add-group', __NAMESPACE__ . '\add_group_form' );

	add_submenu_page('sites.php', 'Add New', 'Add New', 'itcs_support', 'create-site',	__NAMESPACE__ . '\create_site_form'	);
	remove_submenu_page( 'sites.php', 'site-new.php' );

	add_menu_page('Available Tools', 'Tools', 'itcs_support', 'site-management', __NAMESPACE__ . '\site_management_home', 'dashicons-admin-tools' );
	add_submenu_page('site-management',	'Init Multi-Site',	'Multi-Site Init',	'manage_network',	'multisite-init',	__NAMESPACE__ . '\multi_site_init_form'	);
	add_submenu_page('site-management',	'Init Site', 'Init Site', 'manage_network', 'site-init',	__NAMESPACE__ . '\site_init_form' );
}

	/**
	 * Network Admin Menu Callback Functions
	 */
	function add_user_network_form() {
		if(!$form = Form::get_state()) 	$form = new Add_User_Form();
		include( __DIR__ . '/partials/add-user-network-form.php');
	}
	function add_group_form() {
		if(!$form = Form::get_state()) 	$form = new Add_Group_Network_Form();
		include( __DIR__ . '/partials/add-group-form.php');
	}
	function create_site_form() {
		if(!$form = Form::get_state()) 	$form = new Create_Site_Form();
		include( __DIR__ . '/partials/create-site-form.php');
	}
	function site_management_home() {
		include( __DIR__ . '/partials/site-management.php');
	}
	function multi_site_init_form() {
		if(!$form = Form::get_state()) 	$form = new Init_Multisite_Form();
		include( __DIR__ . '/partials/init-multisite-form.php');
	}
	function site_init_form() {
		if(!$form = Form::get_state()) 	$form = new Init_Site_Form();
		include( __DIR__ . '/partials/init-site-form.php');
	}

/**
 * Processes form submissions for the admin screen
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/admin_post_(action)
 */

add_action( 'admin_post_add_user_site_form', __NAMESPACE__ . '\add_user_site_form_process', 10, 0 );
function add_user_site_form_process() {
		
	// Process any submission
	if (check_admin_referer( 'wp_add_user_site', 'wp-add-user-site' ) ) {
		$form = new Add_User_Form();
		$form->set_pirate_ids($_POST['pirate_ids']);
		$form->set_role($_POST['role']);
		$form->set_blogs($_POST['blogs']);
		$form->process(); 
		$form->set_state();
	}	
	wp_redirect( admin_url('admin.php?page=wp-add-user-site') );
    exit;	
}

/**
 * Processes form submissions for the network admin screen
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/admin_post_(action)
 */

add_action( 'admin_post_add_user_network_form', __NAMESPACE__ . '\add_user_network_form_process', 10, 0 );
function add_user_network_form_process() {

	// Process any submission
	if (check_admin_referer( 'wp_add_user_network', 'wp-add-user-network' ) ) {
		$form = new Add_User_Form();
		$form->set_pirate_ids($_POST['pirate_ids']);
		$form->set_role($_POST['role']);
		$form->set_blogs($_POST['blogs']);
		$form->process();
		$form->set_state();
	}
	wp_redirect( admin_url('network/users.php?page=wp-add-user-network') );
    exit;
}


add_action( 'admin_post_add_group_form', __NAMESPACE__ . '\add_group_form_process', 10, 0 );
function add_group_form_process() {

	// Process any submission
	if (check_admin_referer( 'wp_add_group', 'wp-add-group' ) ) {
		$form = new Add_Group_Network_Form();
		$form->set_ad_groups($_POST['ad_groups']);
		$form->set_recursive($_POST['recursive']);
		$form->set_role($_POST['role']);
		$form->set_blogs($_POST['blogs']);
		$form->process();
		$form->set_state();
	}
	wp_redirect( admin_url('network/users.php?page=wp-add-group') );
    exit;
}

add_action( 'admin_post_create_site_form', __NAMESPACE__ . '\create_site_form_process', 10, 0 );
function create_site_form_process() {
 	
   if (check_admin_referer( 'create_site', 'create-site' )) {
   	
   		$form = new Create_Site_Form();
	    $form->set_pirate_id($_POST['pirate_id']);
	    $pos = strpos($_POST['site_url'], '/');

	    if ($pos === false) {
	    	$site_domain = $_POST['site_url'];
	    } else {
	    	$site_domain = substr($_POST['site_url'] , 0, $pos);
	    	$site_path = substr($_POST['site_url'] , ($pos+1));
	    }

	    $form->set_site_domain($site_domain);
	    $form->set_site_path($site_path);
	    $form->set_site_title($_POST['site_title']);
	    $form->set_notify_admin($_POST['notify_admin']);
	    $form->process();
		$form->set_state();

		if (!$form->has_errors()) {
			$form->set_message('<div id="message" class="updated notice is-dismissible"><p>New Site Process Complete!  Check the results below for any issues ( search for "error").</p></div>');
		} else {
			$form->set_message('<div id="message" class="error notice is-dismissible"><p>There was an error!</p></div>');
		}
  	}
	wp_redirect( admin_url('network/admin.php?page=create-site') );
	exit;
}

add_action( 'admin_post_init_site_form', __NAMESPACE__ . '\init_site_form_process', 10, 0 );
function init_site_form_process() {
	
	if (check_admin_referer( 'site_init', 'site-init' ) ) {
		$form = new Init_Site_Form();
		$form->set_site($_POST['site_id']);
		$form->set_options($_POST['options']);
		$form->set_roles($_POST['roles']);
		$form->set_plugins($_POST['plugins']);
		$form->set_cs($_POST['cs']);
		$form->set_roles($_POST['roles']);
		$form->set_widgets($_POST['widgets']);
		$form->set_cron($_POST['cron']);
		$form->set_itcs($_POST['itcs']);
		$form->process();
		wp_cache_flush();
		$form->set_state();
	} 
	wp_redirect( admin_url('network/admin.php?page=site-init') );
    exit;
}

add_action( 'admin_post_init_multisite_form', __NAMESPACE__ . '\init_multisite_form_process', 10, 0 );
function init_multisite_form_process() {
	
	if (check_admin_referer( 'multisite_init', 'multisite-init' ) ) {
		$form = new Init_Multisite_Form();
		$form->process();
		$form->set_message('<div id="message" class="updated notice is-dismissible"><p>Multi Site Initalization Complete!  Check the results below for any issues ( search for "error").</p></div>');
		wp_cache_flush();
		$form->set_state();
	} 
	wp_redirect( admin_url('network/admin.php?page=multisite-init') );
    exit;
}