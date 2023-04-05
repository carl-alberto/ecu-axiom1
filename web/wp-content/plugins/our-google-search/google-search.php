<?php

namespace OUR\GOOGLE\SEARCH;

/**
 *  Plugin Name: Our Google Search
 *  Description: Custom Google search widget and shortcode [google_search_field /]
 *  Version:     1.0.0
 *  Author:      http://www.ecu.edu
 *  Text Domain: our-google-search
 */

 /**
 * This plugin adds a custom end point to capture a google search result and redirects to a custom template that displays
 * the search results. Since this is does not need query vars this is the preferred method.
 * Really good article about WP rewrites https://www.daggerhartlab.com/wordpress-rewrite-api-examples/
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) OR exit;

// Autoloader for the plugin
spl_autoload_register(function ($class_name) {
	if('OUR\GOOGLE\SEARCH\Widget' === $class_name) {
	    require_once __DIR__ . '/includes/class-widget.php';
	}
});

// Plugin activation / deactivation
register_activation_hook( __FILE__, 'flush_rewrite_rules' );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

// Create page endpoint for search results
add_action( 'init', function() {
    add_rewrite_endpoint( 'search-results', EP_PAGES);
}, 10, 0);

// Redirect when search detected.
add_action( 'template_redirect', function() {
    global $wp_query;

    if( $wp_query->query_vars['pagename'] === 'search-results' ){
        include plugin_dir_path( __FILE__ ) . 'includes/templates/search-results.php';
        die;
    }
}, 10, 0);

// Enqueue styles and scripts
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_scripts', 10, 0);
function enqueue_scripts(){
    global $wp_query;

    wp_register_script('google_search_script', 'https://cse.google.com/cse.js?cx=009803953143912655678:qepjjts9jxg', array(), '1.0', true );
    wp_register_script('google_search_change_placeholder', plugins_url( 'js/google-search-change-placeholder.js', __FILE__ ), array(), '1.0', true );
    wp_register_style('google_search_style', plugins_url( 'css/google-search.css', __FILE__ ), false);

    // Enqueues scripts and styles on search-results page
    if( $wp_query->query_vars['pagename'] === 'search-results' ){
        wp_enqueue_script( 'google_search_script' );
        wp_enqueue_script( 'google_search_change_placeholder');
        wp_enqueue_style( 'google_search_style' );
    }
}

// Filters just the google_search_script to add async to tag
add_filter( 'script_loader_tag', __NAMESPACE__ . '\add_async', 10, 3 );
function add_async( $tag, $handle, $src ) {
    if ( $handle !== 'google_search_script' ) {
        return $tag;
    }

    return "<script src='$src' async></script>";
}

// Register Shortcode
add_shortcode( 'google_search_field', function ($atts) {
	wp_enqueue_script( 'google_search_script' );
	wp_enqueue_script( 'google_search_change_placeholder');
    wp_enqueue_style( 'google_search_style' );
    ob_start(); ?>
    <div class="gcse-searchbox-only" data-resultsUrl="<?php echo site_url('/search-results'); ?>" ></div>

    <?php $output = ob_get_contents();
    ob_end_clean();
    return $output;
});

// Register The Widget
add_action( 'widgets_init', function () {
    register_widget( 'OUR\GOOGLE\SEARCH\Widget' );
}, 10, 0);