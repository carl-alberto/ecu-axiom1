<?php
/*
    Plugin Name: Our Learning Outcomes
    Description: Custom Learning outcomes site [learning_outcomes /]
    Version:     1.0.0
    Author:      http://www.ecu.edu
    Author URI:  http://www.ecu.edu
    Text Domain: learning-outcomes
*/

namespace OUR\LEARNINGOUTCOMES;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// Setup the shortcodes
require_once(plugin_dir_path( __FILE__ ) . 'includes/shortcode/shortcode.php');
//include the post types
require_once(plugin_dir_path( __FILE__ ) . 'includes/cpt/cpts.php');

/*
 * Registers scripts and styles used for shortcode
 */
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_learning_outcomes_scripts' );
function enqueue_learning_outcomes_scripts(){
    wp_register_script( 'learning_outcomes_script', plugins_url( 'js/learning_outcomes.js', __FILE__ ), array( 'jquery' ), '1.0' );
    wp_register_style( 'learning_outcomes_style', plugins_url( 'css/learning_outcomes.css', __FILE__ ), false);
}

add_theme_support( 'post-thumbnails', array( 'college' ) );


/*
 * Creates the admin menus
 *
 */
function learning_outcomes_menus(){
    //remoive custom post types from admin menu
    remove_menu_page( 'edit.php?post_type=college' );
    remove_menu_page( 'edit.php?post_type=program' );
    remove_menu_page( 'edit.php?post_type=competency' );
    // remove_menu_page( 'edit.php?post_type=learning_outcome' );

    //create learning outcomes admin menu group
    add_menu_page('Learning Outcomes', 'Learning Outcomes', 'manage_options', 'lo-menu', '',
'dashicons-welcome-learn-more' );
    //hack to prevent top layer menu from sliding down into second level
    add_submenu_page('lo-menu', 'Learning Outcomes', 'Learning Outcomes', 'manage_options', 'lo-menu');
    remove_submenu_page('lo-menu','lo-menu');
    //actual menu items
    // add_submenu_page('lo-menu', 'Learning Outcomes', 'Learning Outcomes', 'manage_options', 'edit.php?post_type=learning_outcome' );
    add_submenu_page('lo-menu', 'Competencies', 'Competencies', 'manage_options', 'edit.php?post_type=competency' );
    add_submenu_page('lo-menu', 'Colleges', 'Colleges', 'manage_options', 'edit.php?post_type=college' );
    add_submenu_page('lo-menu', 'Programs', 'Programs', 'manage_options', 'edit.php?post_type=program' );
}
add_action( 'admin_menu', __NAMESPACE__ . '\learning_outcomes_menus' );

/*
 * Applies locally included single-physician-location.php template to physician location post type
 */
add_filter( 'single_template', __NAMESPACE__ . '\load_learning_outcomes_template' );
function load_learning_outcomes_template( $template ) {
    global $post;
    if ( 'program' === $post->post_type ) {
        return plugin_dir_path( __FILE__ ) . 'includes/views/single-program.php';
    }
    if ( 'college' === $post->post_type ) {
        return plugin_dir_path( __FILE__ ) . 'includes/views/single-college.php';
    }
    if ( 'competency' === $post->post_type ) {
        return plugin_dir_path( __FILE__ ) . 'includes/views/single-competency.php';
    }
    return $template;
}





?>