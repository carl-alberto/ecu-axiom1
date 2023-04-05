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

global $wp_query;

$posts_count = (int) $wp_query->found_posts;
$posts_count_formatted = number_format( $posts_count );
$has_posts = $posts_count > 0 ? true : false;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="mb-1">
            <span class="text-muted">
                <small>
                    <?php
                    if ( $has_posts ) {
                        $message = 'About ' . $posts_count_formatted . ' results for ';
                    } else {
                        $message = 'No results found for ';
                    }

                    echo $message . '&quot; <span class="page-description search-term">' . esc_html( get_search_query() ) . '</span> &quot;';
                    ?>
                </small>
            </span>
        </div>
        <hr class="mb-4" />
    </div> <!-- .col -->
</div> <!-- .row -->
