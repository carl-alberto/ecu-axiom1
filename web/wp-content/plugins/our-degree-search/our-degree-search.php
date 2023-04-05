<?php
namespace OUR\DEGREESEARCH;

/*
    Plugin Name: Our Degree Search
    Description: Custom Degree Explorer search shortcode [degree_search /]
    Version:     1.0.0
    Text Domain: our-degree-search
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
 * Registers degree search field shortcode
 */
add_shortcode( 'degree_search', __NAMESPACE__ . '\degree_search' );
function degree_search( $atts) {
    wp_enqueue_style( 'degree_search_style' );
    wp_enqueue_script( 'degree_search_script' );
    ob_start(); ?>

    <div class="degree-explorer-search">
		<div class="degree-explorer-search-width">
			<div class="degree-explorer-search-inner">
				<div class="input-group mb-3">
				<label id="searchboxlabel" for="searchBox" class="sr-only accessible" >Search ECU Degrees that are offered based on what you are interested in</label>
				  	<input id="searchBox" type="text" class="form-control" placeholder="What are you interested in?" aria-describedby='searchboxlabel'>
					<div class="input-group-append">
				    	<button id="searchBtn" class="btn btn-default" type="button">Search</button>
				  	</div>
				</div>
			</div>
		</div>
	</div>

    <?php $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

/**
 * Enqueue styles and scripts
 */
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_degree_search_scripts', 10, 0);
function enqueue_degree_search_scripts(){
    global $wp_query;
    wp_register_style('degree_search_style', plugins_url( 'css/degree-search.css', __FILE__ ), false);
    wp_register_script('degree_search_script', plugins_url( 'js/degree-search.js', __FILE__ ), array(), '1.0', true );
    wp_localize_script( 'degree_search_script', 'degree_search_script_obj', array( 'env' => TOPSITE_ENV ) );
}

?>