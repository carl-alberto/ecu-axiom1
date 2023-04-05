<?php
namespace OUR\LEARNINGOUTCOMES;

function custom_post_college() {
    $labels = array(
        'name'               => _x( 'Colleges', 'post type general name' ),
        'singular_name'      => _x( 'College', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'college' ),
        'add_new_item'       => __( 'Add New College' ),
        'edit_item'          => __( 'Edit College' ),
        'new_item'           => __( 'New College' ),
        'all_items'          => __( 'All Colleges' ),
        'view_item'          => __( 'View College' ),
        'search_items'       => __( 'Search Colleges' ),
        'not_found'          => __( 'No Colleges found' ),
        'not_found_in_trash' => __( 'No Colleges found in the Trash' ),
        //'parent_item_colon'  => ’,
        'menu_name'          => 'College'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Defines the different Colleges',
        'public'        => true,
        //'menu_position' => 5,
        'hierarchical'  => true,
        'supports'      => array( 'title', 'editor', 'thumbnail' ),
        'has_archive'   => true,
    );
    register_post_type( 'college', $args );
}
add_action( 'init', __NAMESPACE__ . '\custom_post_college' );


?>