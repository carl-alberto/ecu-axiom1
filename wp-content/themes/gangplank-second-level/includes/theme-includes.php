<?php
add_action( 'wp_enqueue_scripts', 'load_second_level_resources', 11,0 );
function load_second_level_resources(){
	wp_enqueue_style('second-level-theme-styles', get_template_directory_uri() . '-second-level/css/theme.css');
	if (is_user_logged_in()) {
		wp_enqueue_style('admin-theme-styles', get_template_directory_uri() . '-second-level/css/admin/navbar-bump.css');
	}

	//load scripts for nav
	wp_enqueue_script('nav-js', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery'), null, true);
}
