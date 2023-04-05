<?php
namespace OUR\LEARNINGOUTCOMES;

function custom_post_competency() {
  $labels = array(
    'name'               => _x( 'Competency', 'post type general name' ),
    'singular_name'      => _x( 'Competency', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'college' ),
    'add_new_item'       => __( 'Add New Competency' ),
    'edit_item'          => __( 'Edit Competency' ),
    'new_item'           => __( 'New Competency' ),
    'all_items'          => __( 'All Competencies' ),
    'view_item'          => __( 'View Competency' ),
    'search_items'       => __( 'Search Competencies' ),
    'not_found'          => __( 'No Competencies found' ),
    'not_found_in_trash' => __( 'No Competencies found in the Trash' ),
    //'parent_item_colon'  => ’,
    'menu_name'          => 'Competency'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Defines the different Competencies',
    'public'        => true,
    //'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail' ),
    'has_archive'   => true,
    //'hierarchical'  => true,
  );
  register_post_type( 'competency', $args );
}
add_action( 'init', __NAMESPACE__ . '\custom_post_competency' );


?>