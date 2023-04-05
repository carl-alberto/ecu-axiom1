<?php
/**
 * Displays the post header
 *
 * @package WordPress
 * @subpackage Galleon
 * @since Galleon 1.0
 */

// Don't show the title if the post-format is `aside` or `status`.
$post_format = get_post_format();

if ( 'aside' === $post_format || 'status' === $post_format ) {
	return;
}
?>
<header id="search-results-content-<?php $has_posts ? the_ID() : 'none'; ?>">
    <div class="entry-header">
        <div class="entry-title default-max-width">
            <?php the_title( sprintf( '<a href="%s"><h4>', esc_url( get_permalink() ) ), '</h4></a>' ); ?>
        </div>
    </div> <!-- .entry-header -->
</header>
