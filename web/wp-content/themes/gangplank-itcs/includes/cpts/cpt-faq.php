<?php
defined( 'ABSPATH' ) OR exit;

// Defines UI element cpt
add_action('init', 'ecu_cpt_faqs',10,0);
function ecu_cpt_faqs() {
	$labels = array(
		'name' => 'FAQs',
		'singular_name' => 'FAQ',
		'menu_name' => 'FAQs',
		'name_admin_bar' => 'FAQ',
		'add_new' => 'Create FAQ',
		'add_new_item' => 'Create New FAQ',
		'new_item' => 'New FAQ',
		'edit_item' => 'Edit FAQ',
		'view_item' => 'View FAQ',
		'all_items' => 'All FAQs',
		'search_items' => 'Search FAQs',
		'parent_item_colon' => 'Parent FAQ:',
		'not_found' => 'FAQ Not Found',
		'not_found_in_trash' => 'FAQ Not Found in Trash'
	);

	$args = array(
		'labels' => $labels,
		'description' => 'FAQ CPT for ITCS child theme',
		'public' => true,
		'exclude_from_search' => false,
		'show_ui' => true,
		'supports' => array('title', 'editor', 'revisions'),
		'has_archive' => false,
		// 'rewrite' => array( 'slug' => 'faqs' ),
		'menu_icon' => 'dashicons-format-chat',
		'capability_type' => array('faq', 'faqs'),
		'map_meta_cap' => true
	);
	register_post_type( 'faq', $args);
}

// Updates messages related to UI elements post type
add_filter( 'post_updated_messages', 'ecu_cpt_faqs_labels' );
function ecu_cpt_faqs_labels( $messages ) {
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );

	$messages['faq'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'FAQ updated.' ),
		2  => __( 'FAQ field updated.' ),
		3  => __( 'FAQ deleted.' ),
		4  => __( 'FAQ updated.' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'FAQ restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'FAQ published.' ),
		7  => __( 'FAQ saved.' ),
		8  => __( 'FAQ submitted.' ),
		9  => sprintf(
			__( 'FAQ scheduled for: <strong>%1$s</strong>.' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
		),
		10 => __( 'FAQ draft updated.' )
	);

	return $messages;
}

// Sets custom column for ui_elements cpt
add_action('manage_faq_posts_columns', 'ecu_faq_add_columns');
function ecu_faq_add_columns($columns){
  return array_merge($columns,
    array(
      'service' => __('Service')
    )
  );
}

// Outputs content for ui_elements
add_action('manage_faq_posts_custom_column', 'ecu_faq_output_columns', 10, 2);
function ecu_faq_output_columns($column, $post_id){
  switch($column){
    case 'service':
      $service = get_field('service', $post_id);
	  if(is_integer($service)){
		  echo "<a href='".get_the_permalink($service)."' target='_blank'>".get_the_title($service)."</a>";
	  }
    break;
  }
}
