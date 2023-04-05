<?php
/*
    Plugin Name: Our Custom Output
    Description: Allows for the addition of Javascript, HTML, or custom meta tags to the content and theme head section.  Used mainly for JS embeds. Shortcode is [output-element id="##"]
    Version:     1.0.0
    Author:      http://www.ecu.edu
    Author URI:  http://www.ecu.edu
    Text Domain: our-output-element
*/

namespace OUR\OUTPUT\ELEMENT;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

//include the post type and shortcode for the output element
require_once(plugin_dir_path( __FILE__ ) . 'includes/output-element.php');

//include the head include
require_once(plugin_dir_path( __FILE__ ) . 'includes/output-head.php');

/**
 * Plugin Activation.
 *
 * Add CPT capabilites to the administrator role.
 */
function plugin_activation() {
    // get the the role object
    $admin_role = get_role( 'administrator' );
    // grant the unfiltered_html capability
    $admin_role->add_cap( 'delete_output_elements' );
    $admin_role->add_cap( 'delete_others_output_elements', true );
    $admin_role->add_cap( 'delete_private_output_elements', true );
    $admin_role->add_cap( 'delete_published_output_elements', true );
    $admin_role->add_cap( 'edit_output_elements', true );
    $admin_role->add_cap( 'edit_others_output_elements', true );
    $admin_role->add_cap( 'edit_private_output_elements', true );
    $admin_role->add_cap( 'edit_published_output_elements', true );
    $admin_role->add_cap( 'publish_output_elements', true );
    $admin_role->add_cap( 'read_private_output_elements', true );

}
register_activation_hook( __FILE__, __NAMESPACE__ . '\plugin_activation' );

/**
 * Remove the capabilites from the administrator role and delete the option to store the
 * head include option.
 */
function plugin_deactivation() {
   // get the the role object
    $admin_role = get_role( 'administrator' );

    $admin_role->remove_cap( 'delete_output_elements');
    $admin_role->remove_cap( 'delete_others_output_elements');
    $admin_role->remove_cap( 'delete_private_output_elements');
    $admin_role->remove_cap( 'delete_published_output_elements');
    $admin_role->remove_cap( 'edit_output_elements');
    $admin_role->remove_cap( 'edit_others_output_elements');
    $admin_role->remove_cap( 'edit_private_output_elements');
    $admin_role->remove_cap( 'edit_published_output_elements');
    $admin_role->remove_cap( 'publish_output_elements');
    $admin_role->remove_cap( 'read_private_output_elements');

    delete_option('custom_output_head');
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\plugin_deactivation' );