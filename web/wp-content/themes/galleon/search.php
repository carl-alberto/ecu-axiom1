<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package WordPress
 * @subpackage Galleon
 * @since Galleon 1.0
 */

get_header();

if ( have_posts() ) {
    get_template_part( 'inc/search-results/search-results', get_post_format() );
} else {
    get_template_part( 'inc/search-results/search-results-none' );
}

get_footer();
?>