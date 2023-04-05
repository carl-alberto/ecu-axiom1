<?php
defined( 'ABSPATH' ) OR exit;

// Defines UI element cpt
add_action('init', 'ecu_cpt_projects',10,0);
function ecu_cpt_projects() {
	$labels = array(
		'name' => 'Projects',
		'singular_name' => 'Project',
		'menu_name' => 'Projects',
		'name_admin_bar' => 'Project',
		'add_new' => 'Create Project',
		'add_new_item' => 'Create New Project',
		'new_item' => 'New Project',
		'edit_item' => 'Edit Project',
		'view_item' => 'View Project',
		'all_items' => 'All Projects',
		'search_items' => 'Search Projects',
		'parent_item_colon' => 'Parent Project:',
		'not_found' => 'Project Not Found',
		'not_found_in_trash' => 'Project Not Found in Trash'
	);

	$args = array(
		'labels' => $labels,
		'description' => 'Project CPT for ITCS child theme',
		'public' => true,
		'exclude_from_search' => false,
		'show_ui' => true,
		'supports' => array('title', 'revisions'),
		'has_archive' => true,
		'rewrite' => array('slug' => 'strategic-plan/%goals%'),
		'menu_icon' => 'dashicons-chart-pie',
		'capability_type' => array('project', 'projects'),
		'map_meta_cap' => true
	);
	register_post_type( 'project', $args);
}

function wp_project_link( $post_link, $id = 0 ){
    $post = get_post($id);
    if ( is_object( $post ) ){
        $terms = wp_get_object_terms( $post->ID, 'goals' );
        if( $terms ){
            return str_replace( '%goals%' , $terms[0]->slug , $post_link );
        } else {
			return str_replace( '%goals%' , 'uncategorized' , $post_link );
		}
		$text = $terms[0]->slug;
    }
    return $post_link;
}
add_filter( 'post_type_link', 'wp_project_link', 1, 3 );

// Updates messages related to UI elements post type
add_filter( 'post_updated_messages', 'ecu_cpt_projects_labels' );
function ecu_cpt_projects_labels( $messages ) {
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );

	$messages['project'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Project updated.' ),
		2  => __( 'Project field updated.' ),
		3  => __( 'Project deleted.' ),
		4  => __( 'Project updated.' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Project restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Project published.' ),
		7  => __( 'Project saved.' ),
		8  => __( 'Project submitted.' ),
		9  => sprintf(
			__( 'Project scheduled for: <strong>%1$s</strong>.' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Project draft updated.' )
	);

	return $messages;
}

add_action( 'init', 'project_taxonomy', 0, 0 );
function project_taxonomy() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'                      => _x( 'Goals', 'taxonomy general name'),
		'singular_name'             => _x( 'Goal', 'taxonomy singular name'),
		'search_items'              => __( 'Search Goals'),
		'all_items'                 => __( 'All Goals'),
		'parent_item'               => null,
		'parent_item_colon'         => null,
		'edit_item'                 => __( 'Edit Goal'),
		'update_item'               => __( 'Update Goal'),
		'add_new_item'              => __( 'Add New Goal'),
		'new_item_name'             => __( 'New Goal Name'),
		'menu_name'                 => __( 'Goals'),
        'add_or_remove_items'       => __( 'Add or remove goals'),
		'choose_from_most_used'     => __( 'Choose from the most used goals'),
		'not_found'                 => __( 'No goals found.'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'			=> array('slug' => 'strategic-plan')
	);

	register_taxonomy( 'goals', array( 'project' ), $args );
}
