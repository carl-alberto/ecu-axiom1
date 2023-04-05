<?php

namespace Intranet;
Use \Mu_Plugins\Form as Form;

defined( 'ABSPATH' ) OR exit;

/**
 * This action is used to add extra submenus and menu options to the admin panel's menu structure.
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/admin_menu
 */
add_action('admin_menu', __NAMESPACE__ . '\site_admin_menu',20,0);
function site_admin_menu() {

	// Intranet Menu
	add_options_page( 'Intranet Setttings', 'Intranet', 'itcs_support', 'wp-intranet', __NAMESPACE__ . '\intranet_form' );

}
	/**
	 * Admin Menu Callback Functions
	 */
	function intranet_form() {
		if(!$form = Form::get_state()) 	$form = new Site_Form();
		include 'partials/intranet-form.php';
	}

/**
 * Processes form submissions for the admin screen
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/admin_post_(action)
 */

add_action( 'admin_post_intranet_form', __NAMESPACE__ . '\intranet_form_process', 10, 0 );
function intranet_form_process() {

	// Process any submission
	if (check_admin_referer( 'wp_intranet_management', 'wp-intranet-management' ) ) {
		$form = new Site_Form();
		// Save Settings
		$form->set_type($_POST['wp_intranet']['type']);
		$form->set_enabled($_POST['wp_intranet']['enabled']);
		$form->set_ad_accounts($_POST['wp_intranet']['accounts']);
		$form->set_ad_groups($_POST['wp_intranet']['groups']);
		$form->process();
		$form->set_state();

        // Remove all users with only the intranet role if the intranet is not enabled.
        if (!$form->get_enabled()) {
        	$intranet = new Intranet();
        	$intranet->remove_users();
        }
	}
	wp_redirect( admin_url('admin.php?page=wp-intranet') );
    exit;
}