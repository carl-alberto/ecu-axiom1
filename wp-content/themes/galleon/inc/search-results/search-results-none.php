<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Galleon
 * @since Galleon 1.0
 */

get_search_form();
get_template_part( 'inc/search-results/search-results-statistics', get_post_format() );
?>
<div id="search-result-wrapper-none" class="mt-2 mb-2">
    <div class="row">
        <div class="col-xs-12">
            <header id="search-results-header-none">
                <div class="entry-header">
                    <h4><?php esc_html_e( 'No Results Found', 'galleon' ); ?></h4>
                </div> <!-- .entry-header -->
            </header> <!-- #search-results-header-none -->
            <article id="search-results-article-none">
                <div class="entry-content">
                    <?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'galleon' ); ?>
                </div> <!-- .entry-content -->
            </article> <!-- #search-results-article-none -->
        </div> <!-- .col-xs-12 -->
    </div> <!-- .row -->
</div> <!-- #search-result-wrapper-none -->
