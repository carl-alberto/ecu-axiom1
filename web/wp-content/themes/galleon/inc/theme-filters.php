<?php defined( 'ABSPATH' ) || exit;

/**
 *
 * Defines modifications made to existing functions via filters
 *
 */


// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

/**
 * Changes the excerpt length from 50 words to 30
 *
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/excerpt_length
 */
add_filter( 'excerpt_length', 'mod_excerpt_length' );
function mod_excerpt_length( $length ) {
	return 30;
}

/**
 * Uses template chosen in site settings to apply to pages using 'Default Template'
 *
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/template_include
 */
add_filter( 'template_include', 'default_template', 99 );
function default_template( $template ){
    $ecu_page_template = get_option('ecu_page_template');
    $ecu_post_template = get_option('ecu_post_template');

	if( get_page_template_slug() == '' && is_singular( 'page' ) && $ecu_page_template ){
        return locate_template( format_template_page_slug( $ecu_page_template ) );
    }
    if( get_page_template_slug() == '' && is_singular( 'post' ) && $ecu_post_template ){
        return locate_template( format_template_post_slug( $ecu_post_template ));
	}
	return $template;
}

function format_template_page_slug( $slug ){
    switch( $slug ){
        case 'full-width':
            return 'page.php';
        case 'sidebar-left':
            update_post_meta( get_the_id(), 'ecu_sidebar_position', false );
            return 'page-sidebar.php';
        case 'sidebar-right':
            update_post_meta( get_the_id(), 'ecu_sidebar_position', true );
            return 'page-sidebar.php';
        default:
            return 'page.php';
    }
}

function format_template_post_slug( $slug ){
    switch( $slug ){
        case 'full-width':
            return 'single-post.php';
        case 'sidebar-left':
            update_post_meta( get_the_id(), 'ecu_sidebar_position', false );
            return 'single-post-sidebar.php';
        case 'sidebar-right':
            update_post_meta( get_the_id(), 'ecu_sidebar_position', true );
            return 'single-post-sidebar.php';
        default:
            return 'single-post.php';
    }
}

/**
 * Formats default archive title text by removing the word 'Archive'
 *
 * @see https://developer.wordpress.org/reference/hooks/get_the_archive_title/
 */
add_filter( 'get_the_archive_title', 'format_archive_title');
function format_archive_title( $title ) {
    if( is_author() ){
        $title = get_the_author();
    } elseif ( is_category() ||  is_tax() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = get_the_author();
    } elseif ( is_date() ){
        $title = str_replace( [ 'Year: ', 'Month: ' ], '', $title );
    }

    $title = str_replace('Archives: ', '', $title);

    return $title ;
}

/**
 * Replace the username in author links generated in the theme with the users display name
 */
add_filter('author_link', 'ecu_filter_author', 10, 3);
function ecu_filter_author($link, $author_id, $author_nicename) {
	return str_replace($author_nicename, sanitize_title(get_the_author_meta('display_name', $author_id)), $link);
}

/* IN ORDER TO VALIDATE you must add namespace   */
add_action('rss2_ns', 'my_rss2_ns');
function my_rss2_ns(){
    echo 'xmlns:customfields="'.  get_bloginfo('wpurl').'"'."\n";
}

//And then prefix the field name item with the custom namespace In this example, I've used "mycustomfields" See below:

/*  add elements    */
add_action('rss2_item', 'ecu_custom_fields');
function ecu_custom_fields() {
    $field ='featured-author';
    $post_id = get_the_ID();
    if ($value = wp_get_post_terms($post_id,$field,true)) {
        foreach ($value as $obj) {
            echo "<customfields:{$field}>{$obj->name}</customfields:{$field}>\n";
        }
    }
}
