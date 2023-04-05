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
?>
<article id="search-results-content-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content">
        <?php
        if ( !empty( get_the_excerpt() )) {
            display_search_post_thumbnail();
            the_excerpt();
        } else {
			echo '<div class="mt-2 mb-4">' . esc_html('Description not available.') . '</div>';
        }
        ?>
    </div> <!-- .entry-content -->
</article> <!-- #search-results-content-${ID} -->
