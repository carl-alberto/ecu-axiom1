<?php
defined( 'ABSPATH' ) OR exit;

add_action( 'wp_before_admin_bar_render', 'ecu_admin_bar_render',10,0 );
function ecu_admin_bar_render() {
    global $wp_admin_bar;
    if ( ! is_network_admin() && ! is_user_admin() ) {
    	$wp_admin_bar->remove_menu('comments');
    }
}