<?php
defined( 'ABSPATH' ) OR exit;

// Defines UI element cpt
add_action('init', 'ecu_cpt_tutorials',10,0);
function ecu_cpt_tutorials() {
	$labels = array(
		'name' => 'Tutorials',
		'singular_name' => 'Tutorial',
		'menu_name' => 'Tutorials',
		'name_admin_bar' => 'Tutorial',
		'add_new' => 'Create Tutorial',
		'add_new_item' => 'Create New Tutorial',
		'new_item' => 'New Tutorial',
		'edit_item' => 'Edit Tutorial',
		'view_item' => 'View Tutorial',
		'all_items' => 'All Tutorials',
		'search_items' => 'Search Tutorials',
		'parent_item_colon' => 'Parent Service:',
		'not_found' => 'Tutorial Not Found',
		'not_found_in_trash' => 'Tutorial Not Found in Trash'
	);

	$args = array(
		'labels' => $labels,
		'description' => 'Tutorial CPT for ITCS child theme',
		'public' => true,
		'exclude_from_search' => false,
		'show_ui' => true,
		'supports' => array('title', 'editor', 'revisions'),
		'has_archive' => false,
		// 'rewrite' => array( 'slug' => 'tutorials' ),
		'menu_icon' => 'dashicons-book-alt',
		'capability_type' => array('tutorial', 'tutorials'),
		'map_meta_cap' => true
	);
	register_post_type( 'tutorial', $args);
}

// Updates messages related to UI elements post type
add_filter( 'post_updated_messages', 'ecu_cpt_tutorials_labels' );
function ecu_cpt_tutorials_labels( $messages ) {
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );

	$messages['tutorial'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Tutorial updated.' ),
		2  => __( 'Tutorial field updated.' ),
		3  => __( 'Tutorial deleted.' ),
		4  => __( 'Tutorial updated.' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Tutorial restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Tutorial published.' ),
		7  => __( 'Tutorial saved.' ),
		8  => __( 'Tutorial submitted.' ),
		9  => sprintf(
			__( 'Tutorial scheduled for: <strong>%1$s</strong>.' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Tutorial draft updated.' )
	);

	return $messages;
}

add_action('manage_tutorial_posts_columns', 'ecu_tutorial_add_columns');
function ecu_tutorial_add_columns($columns){
  return array_merge($columns,
    array(
      'service' => __('Service')
    )
  );
}

// Outputs content for ui_elements
add_action('manage_tutorial_posts_custom_column', 'ecu_tutorial_output_columns', 10, 2);
function ecu_tutorial_output_columns($column, $post_id){
  switch($column){
    case 'service':
      $service = get_field('service', $post_id);
	  if(is_integer($service)){
		  echo "<a href='".get_the_permalink($service)."' target='_blank'>".get_the_title($service)."</a>";
	  }
    break;
  }
}
