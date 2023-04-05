<?php
defined( 'ABSPATH' ) OR exit;

// create two taxonomies, genres and writers for the post type "book"
add_action( 'init', 'featured_author_taxonomy', 0, 0 );
function featured_author_taxonomy() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'                      => _x( 'Featured Authors', 'taxonomy general name'),
		'singular_name'             => _x( 'Author', 'taxonomy singular name'),
		'search_items'              => __( 'Search Authors'),
		'all_items'                 => __( 'All Authors'),
		'parent_item'               => null,
		'parent_item_colon'         => null,
		'edit_item'                 => __( 'Edit Author'),
		'update_item'               => __( 'Update Author'),
		'add_new_item'              => __( 'Add New Author'),
		'new_item_name'             => __( 'New Author Name'),
		'menu_name'                 => __( 'Authors', 'textdomain' ),
    'add_or_remove_items'       => __( 'Add or remove authors'),
		'choose_from_most_used'     => __( 'Choose from the most used authors'),
		'not_found'                 => __( 'No authors found.'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'featured-author' ),
	);

	register_taxonomy( 'featured-author', array( 'post' ), $args );
}
