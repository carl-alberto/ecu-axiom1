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

get_search_form();
get_template_part( 'inc/search-results/search-results-statistics', get_post_format() );
?>
<div id="search-result-wrapper" class="mt-2 mb-2">
    <div class="row">
        <div class="col-xs-12">
<?php
while ( have_posts() ) {
    the_post();
    get_template_part( 'inc/search-results/search-results-excerpt-header', get_post_format() );
    get_template_part( 'inc/search-results/search-results-excerpt-content', get_post_format() );
    //get_template_part( 'inc/search-results/search-results-excerpt-footer', get_post_format() );
}
get_template_part( 'inc/search-results/search-results-pagination', get_post_format() );
?>
        </div> <!-- .col -->
    </div> <!-- .row -->
</div> <!-- search-result-wrapper -->
