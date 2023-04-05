<?php

/**
 * Global resources loaded on front and back end
 */

add_theme_support( 'post-thumbnails', [ 'post', 'page' ] );
add_image_size( 'search-post-thumbnail', 150, 150 );

include_once( 'inc/theme-activation.php');

include_once( 'inc/theme-actions.php');

include_once( 'inc/theme-filters.php');

include_once( 'inc/theme-helper.php');

include_once( 'inc/theme-legacy.php');

include_once( 'inc/post/meta.php');

if( is_admin() ){

    /**
     * Resources loaded on wp-admin
     */
    include_once( 'inc/admin/theme-admin.php');

} else {

    /**
     * Resources loaded on frontend
     */

    include_once( 'inc/theme-functions.php');

    include_once( 'inc/class-wp-bootstrap-navwalker.php');

}
