<?php
namespace OUR\DIRECTORY;

/*
 * Registers scripts and styles used for shortcode
 */
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\register_directory_scripts', 10, 0 );
function register_directory_scripts(){
    wp_register_style( 'directory_style', plugins_url( '../assets/css/ecu-directory.css', __FILE__ ), false, '1.0' );
    wp_enqueue_script('directory_script', plugins_url( '../assets/js/ecu-directory.js', __FILE__ ), array('jquery'));
    wp_localize_script( 'directory_script', ' directory_search_people_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce('ajax-nonce') ) );
    wp_localize_script( 'directory_script', 'directory_search_department_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script( 'directory_script', 'directory_browse_depts_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script( 'directory_script', 'directory_reverse_search_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script( 'directory_script', 'directory_search_pirate_id_ajax ', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

/*
 * Add shortcode
 */
add_shortcode( 'ecu_directory', __NAMESPACE__ . '\directory', 10, 0 );
function directory( $atts) {
	wp_enqueue_script( 'directory_script' );
    wp_enqueue_style( 'directory_style' );
    ob_start(); ?>
    <div id="ecu-directory">
    <?php include('partials/_tabs.php'); ?>
    </div>
    <?php $output = ob_get_contents();
    ob_end_clean();
    return $output;
}