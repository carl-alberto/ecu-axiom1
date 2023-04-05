<?php
/**
 * The searchform.php template.
 *
 * Used any time that get_search_form() is called.
 *
 * @link https://developer.wordpress.org/reference/functions/wp_unique_id/
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 *
 * @package WordPress
 * @subpackage Galleon
 * @since Galleon 1.0
 */

$galleon_unique_id = wp_unique_id( 'search-form-' );
$galleon_aria_label = !empty( $args['aria_label'] ) ? 'aria-label="' . esc_attr( $args['aria_label'] ) . '"' : '';
?>
<div class="clearfix">
    <form class="form-inline" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" <?php echo $galleon_aria_label; ?> role="search">    
        <div class="form-group">
            <label for="<?php echo esc_attr( $galleon_unique_id ); ?>" class="sr-only">Search</label>
            <input type="search" name="s" id="<?php echo esc_attr( $galleon_unique_id ); ?>" class="form-control mb-2 mr-sm-2" value="<?php echo get_search_query(); ?>" placeholder="Enter your query" />
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary mb-2">
                <?php echo esc_attr_x( 'Search', 'submit button', 'galleon' ); ?>
            </button>
        </div>
    </form>
</div>
