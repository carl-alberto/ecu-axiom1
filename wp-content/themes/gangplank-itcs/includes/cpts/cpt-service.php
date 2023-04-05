<?php
defined( 'ABSPATH' ) OR exit;

// Defines UI element cpt
add_action('init', 'ecu_cpt_services',10,0);
function ecu_cpt_services() {
	$labels = array(
		'name' => 'Services',
		'singular_name' => 'Service',
		'menu_name' => 'Services',
		'name_admin_bar' => 'Service',
		'add_new' => 'Create Service',
		'add_new_item' => 'Create New Service',
		'new_item' => 'New Service',
		'edit_item' => 'Edit Service',
		'view_item' => 'View Service',
		'all_items' => 'All Services',
		'search_items' => 'Search Services',
		'parent_item_colon' => 'Parent Service:',
		'not_found' => 'Service Not Found',
		'not_found_in_trash' => 'Service Not Found in Trash'
	);

	$args = array(
		'labels' => $labels,
		'description' => 'Service CPT for ITCS child theme',
		'public' => true,
		'publicly_queryable' => true,
		'query_var' => true,
		'hierarchical' => true,
		'exclude_from_search' => false,
		'show_ui' => true,
		'supports' => array('title', 'editor', 'revisions', 'excerpt'),
		'has_archive' => true,
		'show_in_rest' => true,
		'rewrite' => array('slug' => 'services/%service-category%'),
		'menu_icon' => 'dashicons-clipboard',
		'capability_type' => array('service', 'services'),
		'map_meta_cap' => true
	);
	register_post_type( 'service', $args);
}

function wp_service_link( $post_link, $id = 0 ){
    $post = get_post($id);
    if ( is_object( $post ) ){
        $terms = wp_get_object_terms( $post->ID, 'service-category' );
        if( $terms ){
            return str_replace( '%service-category%' , $terms[0]->slug , $post_link );
        } else {
			return str_replace( '%service-category%' , 'uncategorized' , $post_link );
		}
		$text = $terms[0]->slug;
    }
    return $post_link;
}
add_filter( 'post_type_link', 'wp_service_link', 1, 3 );

// Updates messages related to UI elements post type
add_filter( 'post_updated_messages', 'ecu_cpt_services_labels' );
function ecu_cpt_services_labels( $messages ) {
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );

	$messages['service'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Service updated.' ),
		2  => __( 'Service field updated.' ),
		3  => __( 'Service deleted.' ),
		4  => __( 'Service updated.' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Service restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Service published.' ),
		7  => __( 'Service saved.' ),
		8  => __( 'Service submitted.' ),
		9  => sprintf(
			__( 'Service scheduled for: <strong>%1$s</strong>.' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Service draft updated.' )
	);

	return $messages;
}
add_action( 'init', 'service_taxonomy', 0,0);
function service_taxonomy() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'                      => _x( 'Categories', 'taxonomy general name'),
		'singular_name'             => _x( 'Category', 'taxonomy singular name'),
		'search_items'              => __( 'Search Categories'),
		'all_items'                 => __( 'All Categories'),
		'parent_item'               => null,
		'parent_item_colon'         => null,
		'edit_item'                 => __( 'Edit Category'),
		'update_item'               => __( 'Update Category'),
		'add_new_item'              => __( 'Add New Category'),
		'new_item_name'             => __( 'New Category Name'),
		'menu_name'                 => __( 'Categories', 'textdomain' ),
        'add_or_remove_items'       => __( 'Add or remove categories'),
		'choose_from_most_used'     => __( 'Choose from the most used categories'),
		'not_found'                 => __( 'No categories found.'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_in_rest' 		=> true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'services' ),
	);

	register_taxonomy( 'service-category', array( 'service' ), $args );
}

add_action( 'init', 'department_taxonomy', 0,0 );
function department_taxonomy() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'                      => _x( 'Departments', 'taxonomy general name'),
		'singular_name'             => _x( 'Department', 'taxonomy singular name'),
		'search_items'              => __( 'Search Departments'),
		'all_items'                 => __( 'All Departments'),
		'parent_item'               => null,
		'parent_item_colon'         => null,
		'edit_item'                 => __( 'Edit Department'),
		'update_item'               => __( 'Update Department'),
		'add_new_item'              => __( 'Add New Department'),
		'new_item_name'             => __( 'New Department Name'),
		'menu_name'                 => __( 'Departments'),
        'add_or_remove_items'       => __( 'Add or remove departments'),
		'choose_from_most_used'     => __( 'Choose from the most used departments'),
		'not_found'                 => __( 'No departments found.'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_in_rest' 		=> true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array(
			'slug' => 'departments',
			'hierarchical' => true),
	);

	register_taxonomy( 'department', array( 'service' ), $args );
}


add_action( 'init', 'audience_taxonomy', 0,0 );
function audience_taxonomy() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'                      => _x( 'Audiences', 'taxonomy general name'),
		'singular_name'             => _x( 'Audience', 'taxonomy singular name'),
		'search_items'              => __( 'Search Audiences'),
		'all_items'                 => __( 'All Audiences'),
		'parent_item'               => null,
		'parent_item_colon'         => null,
		'edit_item'                 => __( 'Edit Audience'),
		'update_item'               => __( 'Update Audience'),
		'add_new_item'              => __( 'Add New Audience'),
		'new_item_name'             => __( 'New Audience Name'),
		'menu_name'                 => __( 'Audiences', 'textdomain' ),
    	'add_or_remove_items'       => __( 'Add or remove audiences'),
		'choose_from_most_used'     => __( 'Choose from the most used audiences'),
		'not_found'                 => __( 'No audiences found.'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest' 		=> true,
		'query_var'         => true,
		'rewrite'           => array('slug' => 'services/audience'),
		'public'			=> true
	);

	register_taxonomy( 'audience', array( 'service' ), $args );
}

add_action( 'init', 'tag_taxonomy', 0,0 );
function tag_taxonomy() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'                      => _x( 'Tags', 'taxonomy general name'),
		'singular_name'             => _x( 'Tag', 'taxonomy singular name'),
		'search_items'              => __( 'Search Categories'),
		'all_items'                 => __( 'All Categories'),
		'parent_item'               => null,
		'parent_item_colon'         => null,
		'edit_item'                 => __( 'Edit Tags'),
		'update_item'               => __( 'Update Tags'),
		'add_new_item'              => __( 'Add New Tag'),
		'new_item_name'             => __( 'New Tag Name'),
		'menu_name'                 => __( 'Tags' ),
        'add_or_remove_items'       => __( 'Add or remove tags'),
		'choose_from_most_used'     => __( 'Choose from the most used tags'),
		'not_found'                 => __( 'No tags found.'),
	);

	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest' 		=> true,
		'query_var'         => true,
		'rewrite'           => false,
		'public'			=> false
	);

	register_taxonomy( 'service-tags', array( 'service' ), $args );
}
