<?php
if(is_category()) {
    //Category archive page
    dynamic_sidebar( 'custom-sidebar-' . get_option('ecu_category_sidebar') );
} elseif(is_author()) {
    //Author archive page
    dynamic_sidebar( 'custom-sidebar-' . get_option('ecu_author_sidebar') );
} elseif (is_date()) {
    //All other archive pages
    dynamic_sidebar( 'custom-sidebar-' . get_option('ecu_date_sidebar') );
} else {
    //Regular post type pages
    $page_sidebar = get_meta('ecu_sidebar');

    if($page_sidebar) {
        // Page Setting
        dynamic_sidebar( 'custom-sidebar-' . $page_sidebar );
    } else {
        $theme_default_sidebar = get_option('ecu_blog_sidebar');
        if($theme_default_sidebar) {
             // Theme Options Setting
            dynamic_sidebar( 'custom-sidebar-' . $theme_default_sidebar );
        } else {
            // Primary Sidebar
            dynamic_sidebar( 'default_sidebar' );
        }
    }
}