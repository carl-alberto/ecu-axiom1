<?php

namespace OUR\OUTPUT\ELEMENT;

add_action( 'init', __NAMESPACE__ . '\register_cpt' );
function register_cpt() {

    $labels = array(
        'name'               => __( 'Output Elements', 'our-output-element' ),
        'singular_name'      => __( 'Output Element', 'our-output-element' ),
        'add_new'            => __( 'Add New', 'our-output-element' ),
        'add_new_item'       => __( 'Add New Output Element', 'our-output-element' ),
        'edit_item'          => __( 'Edit Output Element', 'our-output-element' ),
        'new_item'           => __( 'New Output Element', 'our-output-element' ),
        'all_items'          => __( 'All Output Elements', 'our-output-element' ),
        'view_item'          => __( 'View Output Element', 'our-output-element' ),
        'search_items'       => __( 'Search Output Elements', 'our-output-element' ),
        'not_found'          => __( 'No Output Elements found', 'our-output-element' ),
        'not_found_in_trash' => __( 'No Output Elements found in the Trash', 'our-output-element' ),
        'menu_name'          => __( 'Output Element', 'our-output-element')
    );

    $args = array(
        'labels'        => $labels,
        'capability_type' => array('output_element','output_elements'), //custom capability type
        'map_meta_cap'    => true, //map_meta_cap must be true
        'description'   => 'Custom output elements.    Use the [output_element id="##" /] to display content',
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'supports'      => array( 'title', 'editor'),
    );

    register_post_type( 'output_element', $args );
}

/**
 * Registers js output element shortcode
 */
add_shortcode( 'output_element', __NAMESPACE__ . '\shortcode' );
function shortcode( $atts) {
    $content_post = get_post($atts['id']);
    $content = $content_post->post_content;
    if ( !empty($content) ) {
        ob_start();
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);
        echo $content;
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}

add_filter( 'manage_output_element_posts_columns', __NAMESPACE__ . '\shortcode_title_column' );
function shortcode_title_column( $columns ) {
  $columns['shortcode'] = __( 'Shortcode' );
  return $columns;
}

add_action( 'manage_output_element_posts_custom_column', __NAMESPACE__ . '\shortcode_column', 10, 2);
function shortcode_column( $column, $post_id ) {
    if ( 'shortcode' === $column ) {
        echo "[output_element id='" . $post_id . "' /]";
    }
}
