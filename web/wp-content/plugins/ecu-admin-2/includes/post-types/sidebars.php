<?php
defined( 'ABSPATH' ) OR exit;
// Defines sidebar cpt
add_action('init', 'ecu_cpt_sidebar',10,0);
function ecu_cpt_sidebar() {
	$labels = array(
		'name' => 'Sidebars',
		'singular_name' => 'Sidebar',
		'menu_name' => 'Sidebars',
		'name_admin_bar' => 'Sidebar',
		'add_new' => 'Create Sidebar',
		'add_new_item' => 'Create New Sidebar',
		'new_item' => 'New Sidebar',
		'edit_item' => 'Edit Sidebar',
		'view_item' => 'View Sidebar',
		'all_items' => 'All Sidebars',
		'search_items' => 'Search Sidebars',
		'parent_item_colon' => 'Parent Sidebars:',
		'not_found' => 'Sidebar Not Found',
		'not_found_in_trash' => 'Sidebar Not Found in Trash'
	);

	$args =  array(
		'labels' => $labels,
		'description' => 'This is the custom sidebar custom post type',
		'public' => false,
		'exclude_from_search' => false,
		'show_ui' => true,
		'supports' => array('title'),
		'has_archive' => false,
		'rewrite' => false,
		'menu_icon' => 'dashicons-menu',
	);

	if(get_option('stylesheet') == 'gangplank-second-level'){
		$args['capability_type'] = array('sidebar', 'sidebars');
		$args['map_meta_cap'] = true;
	}
	register_post_type( 'sidebar', $args);
}

/**
 * Registers Sidebars based on CPT
 * @return NULL
 */
add_action('init', 'ecu_register_acf_sidebars',10,0);
function ecu_register_acf_sidebars() {
	if(function_exists('get_field')) {
		$sidebars = new \WP_Query(array(
		'post_type' => 'sidebar',
		'orderby' => 'date',
		'posts_per_page' => -1
		));

		while( $sidebars->have_posts() ) :
			$sidebars->the_post();
			register_sidebar(array(
				'name'          => get_the_title(),
				'id'            => 'custom-sidebar-' . get_the_ID(),
				'before_widget' => '<section class="widget %1$s %2$s" aria-label="%1$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widgettitle widget-title">',
				'after_title'   => '</h2>',
			));
		endwhile;
		wp_reset_postdata();
	}
}
