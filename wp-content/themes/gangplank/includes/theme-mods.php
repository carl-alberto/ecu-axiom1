<?php

/**
 * Removes ability to set favicon in WP customizer
 *
 */
function remove_site_icon($wp_customize) {
    $wp_customize->remove_control('site_icon');
}
add_action( 'customize_register', 'remove_site_icon', 20, 1 );

/**
 * Formats default archive title text
 *
 */
add_filter( 'get_the_archive_title', 'format_archive_title');
function format_archive_title($title) {
    $var = get_query_var('taxonomy');
    if( is_category() ||  is_tax()) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = get_the_author();
    } elseif( is_date()) {
        $title = str_replace(array('Year: ', 'Month: '), '', $title);
    }
    $title = str_replace('Archives: ', '', $title);

    if($var == 'goals' || $var == 'department'){
        return $title;
    } else {
        return $title . ' Archives';
    }
}

/**
 * Removes default editor formatting for blank page template
 *
 */
add_action('wp_head', 'remove_auto_p');
function remove_auto_p(){
    if(is_page_template('page-blank.php')){
        remove_filter('the_content', 'wpautop');
        remove_filter('the_excerpt', 'wpautop');
        remove_filter ('acf_the_content', 'wpautop');

        add_filter( 'the_content', 'wpautop_nobr' );
        add_filter( 'the_excerpt', 'wpautop_nobr' );
        add_filter( 'acf_the_content', 'wpautop_nobr' );
    }
}

/**
 * Removes adding <p> tags in content
 * @param  string $content Post content
 * @return string          The content with no additional <p> tags
 */
function wpautop_nobr( $content ) {
    return wpautop( $content, false );
}

/**
 * Adds additional image size for mobile banners
 *
 */
add_image_size('banner_xs', 768, 384, array('center', 'center'));

/**
 * Adds styleselect dropdown to ACF WYSIWYG editors
 *
 */
add_filter( 'acf/fields/wysiwyg/toolbars' , 'basic_toolbar'  );
function basic_toolbar( $toolbars ){
	array_unshift($toolbars['Basic'][1], 'styleselect');

	return $toolbars;
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
    $field = 'featured-author';
    $post_id = get_the_ID();
    if ($value = wp_get_post_terms($post_id,$field,true)) {
        foreach ($value as $obj) {
             echo "<customfields:{$field}>{$obj->name}</customfields:{$field}>\n";
        }
    }
}