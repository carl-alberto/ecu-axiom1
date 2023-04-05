<?php

/**
 * Filters list of page templates for a theme.   This will remove the legacy templates that are kept
 * for support of the old theme until they are removed by deleting the files physically.
 *
 * @since 4.9.6
 *
 * @param string[]     $post_templates Array of template header names keyed by the template file name.
*/
add_filter('theme_templates', 'ecu_filter_legacy_theme_templates');
function ecu_filter_legacy_theme_templates($templates) {
    unset($templates['page-sidebar-left.php']);
    unset($templates['page-sidebar-right.php']);
    unset($templates['page-ribbons.php']);
    unset($templates['page-blank.php']);
    return $templates;
}

/**
 * Converts a post or page acf fields to non acf fields for use in galleon.
 *
 * It will only run once and the ACF field data is preserved in case you need to revert to gangplank.
 */
add_action('the_post', 'ecu_post_migration');
function ecu_post_migration($post) {

    if( !class_exists( 'ACF' ) ) return;

    if( 'post' == $post->post_type || 'page' == $post->post_type ) {
        $migrated = get_post_meta( $post->ID, 'post-is-migrated', false );
        if(!$migrated) {
            update_post_meta( $post->ID, 'post-is-migrated', true );
            ecu_migrate_post($post);
        }
    }
}

function is_ecu_post_migrated() {
    return get_post_meta( get_the_ID(), 'post-is-migrated', false );
}

/**
 * Migrates individual ACF post fields to custom meta fields
 * This code is a refactor of /themes/galleon/inc/theme-activation.php
 */
function ecu_migrate_post( $post ){

    $hasBanner = ecu_migrate_banner( $post );

    $template = get_page_template_slug( $post->ID );

    if( 'page' == $post->post_type ) {
        if( $template === 'page-sidebar-left.php' || $template === 'page-sidebar-right.php' ){
            update_post_meta( $post->ID, '_wp_page_template', 'page-sidebar.php' );
        } elseif ($template === 'page-full-width.php') {
            update_post_meta( $post->ID, '_wp_page_template', 'page.php' );
        }
    }

    if( 'post' == $post->post_type ) {

        if( $template === 'page-sidebar-left.php' || $template === 'page-sidebar-right.php' ){
            update_post_meta( $post->ID, '_wp_page_template', 'single-post-sidebar.php' );
        } elseif ($template === 'page-full-width.php') {
            update_post_meta( $post->ID, '_wp_page_template', 'single-post.php' );
        }
    }

    // pages
    $banner_image = get_field( 'banner_image', $post->ID );            // 760
    $h1_title = get_field( 'h1_title', $post->ID );                    // 'Alternate Page Title'
    $banner_full_width = get_field( 'banner_full_width', $post->ID );  // false
    $sidebar_selector = get_field( 'sidebar_selector', $post->ID );    // 1834 || false
    $hide_h1_title = get_field( 'hide_h1_title', $post->ID );          // false
    $back_to_top = get_field( 'back_to_top', $post->ID );              // false

    if( !$hasBanner && !empty( $banner_image['url'] ) )
        update_post_meta( $post->ID, 'ecu_banner', json_encode(
            [
                'type' => 'image',
                'image' => $banner_image['url']
            ]
        ) );

    update_post_meta( $post->ID, 'ecu_alt_title', $h1_title );
    update_post_meta( $post->ID, 'ecu_banner_full', $banner_full_width );
    update_post_meta( $post->ID, 'ecu_sidebar', $sidebar_selector );
    update_post_meta( $post->ID, 'ecu_hide_h1', $hide_h1_title );
    update_post_meta( $post->ID, 'ecu_to_top', $back_to_top );
    update_post_meta( $post->ID, 'ecu_sidebar_position', $template === 'page-sidebar-left.php' ? false : true );

    if( $post->post_type !== 'post' ) return;

    // posts
    $secondary_title = get_field( 'secondary_title', $post->ID );      // 'Secondary Title'
    $external_post = get_field( 'external_post', $post->ID );          // 'https://www.google.com/'

    update_post_meta( $post->ID, 'ecu_subtitle', $secondary_title );
    update_post_meta( $post->ID, 'ecu_spark', $external_post );
}

function ecu_migrate_banner( $post ){

    $banner_type = get_field( 'banner_type', $post->ID );

    switch( $banner_type ){
        case 'slideshow':
            $data['type'] = 'slideshow';
            $data['slides'] = [];
            $slides = get_field( 'slideshow', $post->ID );

            foreach( $slides as $slide ){
                $slide_obj = [];
                $slide_obj['type'] = $slide['slide_type'];

                if( $slide['slide_type'] === 'image' ){
                    $slide_obj['image'] = $slide['image']['url'];

                    if( $slide['enable_caption'] ){
                        $slide_obj['caption'] = $slide['caption']['description'];
                        $slide_obj['caption_position'] = $slide['caption']['position'];
                        $slide_obj['heading'] = $slide['caption']['title'];
                    }
                } else {
                    $slide_obj['post'] = $slide['post']->ID;
                    $slide_obj['caption_position'] = $slide['caption_position'];
                }
                $data['slides'][] = $slide_obj;
            }
            break;
        case 'video':
            $data['type'] = 'video';
            $banner_video = get_field( 'video', $post->ID );
            $data['video'] = attachment_url_to_postid( $banner_video );
            break;
        case 'posts':
            $data['type'] = 'posts';
            $data['category'] = get_field( 'category', $post->ID );
            $data['caption_position'] = get_field( 'caption_position', $post->ID );
            $data['number_of_posts'] = get_field( 'number_of_posts', $post->ID );
            break;
        case 'image':
            $data['type'] = 'image';
            $banner_image = get_field( 'banner_image', $post->ID );
            $data['image'] = $banner_image['url'];
            break;
        default:
            return false;

    }

    update_post_meta( $post->ID, 'ecu_banner', json_encode( $data ) );
    return true;
}


/**
 * Loads ACF field settings into site
 *
 * Without this, no ACF fields will showup anywhere!
 */
add_action( 'init', 'load_acf_fields');
function load_acf_fields() {
    if(class_exists('ACF')){
        $option = get_field('super_acf', 'option');
        $parent = get_template_directory() . '/fields/acf-fields.php';
        $child = get_stylesheet_directory() . '/fields/acf-fields.php';
        //$super = get_template_directory() . '/fields/superadmin.php';
        switch($option){
            case 'parent':
                if(file_exists($parent)) include_once($parent);
                break;
        case 'child':
            if(file_exists($child)) include_once($child);
            break;
        case 'both':
            if(file_exists($child) && file_exists($parent))
                include_once($child);
                include_once($parent);
            break;
        case 'none':
            break;
        default:
            if(file_exists($parent))
                include_once($parent);
            break;
        }
    }
}

/**
 *
 * Includes legacy styles stylesheet to support deprecated elements
 *
 */
add_action( 'wp_enqueue_scripts', 'legacy_scripts' );
function legacy_scripts(){
    //if( !has_blocks() ){
        wp_enqueue_style( 'legacy-styles', DIST_URL . '/legacy.css', [], filemtime( DIST_PATH . '/legacy.css' ) );
    //}
}

/**
 *
 * Filter to strip out certain tags from ribbon callouts
 * No restrictions in place for gutenberg
 *
 */
function callout_tags($content) {
    return strip_tags($content, '<strong><p><em><span><del><a><ul><ol><li><blockquote><h2><h3><h4><h5><h6><pre><br><hr><i>');
}

/**
 *
 * Includes footer menu or link farm for footer
 *
 */
function legacy_footer(){
    $footer_type = get_field('footer_type', 'option');
    switch($footer_type){
      case 'link_farm':
        if($link_farm = get_field('footer_link_farm', 'option')){
          echo do_shortcode("[link_farm link_farm_id='{$link_farm}' /]");
        }
      break;
      case 'menu':
        include_once('legacy/footer-menu.php');
        break;
      default:
        break;
    }
}