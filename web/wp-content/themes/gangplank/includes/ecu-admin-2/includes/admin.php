<?php
defined( 'ABSPATH' ) OR exit;

// Enqueue Scripts and set up redirects for non admin users
if(is_admin()) {
 	add_action('admin_enqueue_scripts', 'ecu_admin_enqueue_admin_scripts', 11, 0);
} else {
	add_action('admin_enqueue_scripts', 'ecu_admin_enqueue_public_scripts',10,0);
}

function ecu_admin_enqueue_admin_scripts() {
	wp_register_script('ecu-admin2-scripts', '/wp-content/themes/gangplank/includes/ecu-admin-2/js/ecu-admin-scripts.js', 'jquery', '1.0');
	wp_enqueue_script('ecu-admin2-scripts');

	wp_register_script('ecu-admin2-public-scripts', '/wp-content/themes/gangplank/includes/ecu-admin-2/js/ecu-public-scripts.js', 'jquery', '1.0');

    if(class_exists('ACF')){
        $array = array();
        $temps = get_field('default_templates', 'option');
        if(is_array($temps)){
            $array['page'] = $temps['admin_page_template'] ? $temps['admin_page_template'] : false;
            $array['post'] = $temps['admin_post_template'] ? $temps['admin_post_template'] : false;
            wp_localize_script( 'ecu-admin-public-scripts', 'templates', $array);
            wp_enqueue_script('ecu-admin-public-scripts');
        }
    }

    wp_enqueue_script('match-height', '/wp-content/themes/gangplank/includes/ecu-admin-2/js/match-height.min.js', 'jquery', '1.0');
	wp_enqueue_style('ecu-admin2-style', '/wp-content/themes/gangplank/includes/ecu-admin-2/css/ecu-admin.css');	
}

function ecu_admin_enqueue_public_scripts() {
	wp_deregister_script('jquery');
	wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', false, null);
	wp_enqueue_script('jquery');
}

//Sets the page title
add_filter( 'wp_title', 'ecu_site_title', 10, 3 );
/**
  * Sets the page titles to follow the standard of page | site | ECU
  * @author Miguel Vega <vegaherreram13@ecu.edu>
  *
  * @param string $title       Existing page title.
  * @param string $sep         Optional. Separator character(s). Default is `–` if not set.
  * @param string $seplocation Optional. Separator location - "left" or "right". Default is "right" if not set.
  * @return string Page title, formatted depending on context.
  */
function ecu_site_title($title, $sep = '&raquo;', $seplocation = '') {
	global $post;
	$delim = ' | ';

	$title_meta = array(get_bloginfo('name'), 'ECU');
	if (function_exists('is_tag') && is_tag()) {
		array_unshift($title_meta, single_tag_title("Tag Archive for ", false));
	} elseif (is_home()) {
		array_unshift($title_meta, get_the_title(get_option('page_for_posts')));
	} elseif (is_search()) {
		array_unshift($title_meta, 'Search for &quot;'.wp_specialchars($s).'&quot;');
	} elseif (!(is_404()) && (is_single()) || (is_page())) {
		array_unshift($title_meta, get_the_title($post->ID));
	} elseif (is_404()) {
		array_unshift($title_meta, 'Not Found');
	} elseif (is_author())	{
		array_unshift($title_meta, 'Author: ' . get_the_author());
	} elseif (is_archive()) {
		array_unshift($title_meta, get_the_archive_title());
	}
	return implode($title_meta, $delim);
}

// Add custom cron interval
add_filter( 'cron_schedules', 'ecu_custom_cron_interval' );
function ecu_custom_cron_interval($schedules) {
	if(!isset($schedules['15_minutes'])){
		$schedules['15_minutes'] = array(
				'display' => __( 'Every 15 minutes', 'textdomain' ),
				'interval' => 900,
		);
		return $schedules;
	}
}

// Change appearance of login page
add_action( 'login_enqueue_scripts', 'ecu_enqueue_login',10,0 );
function ecu_enqueue_login() {
	wp_enqueue_style( 'ecu-login2-styles', '/wp-content/themes/gangplank/includes/ecu-admin-2/css/ecu-login.css');
}

// Removes comment reply script
add_action('init','clean_header',10,0);
function clean_header(){
  wp_deregister_script( 'comment-reply' );
}

// https://github.ecu.edu/WebDev/wordpress-cms/issues/493
add_action( 'admin_menu', 'clean_menu', 999);
function clean_menu(){
    $user = wp_get_current_user();
	if ( in_array( 'blog_owner', $user->roles ) ) {
	remove_menu_page('edit-comments.php');
        remove_menu_page('plugins.php');
        remove_submenu_page('plugins.php', 'plugins.php?page=so-widgets-plugins');
        remove_submenu_page('themes.php', 'customize.php?return=' . urlencode($_SERVER['REQUEST_URI']));
        remove_submenu_page('tools.php', 'import.php');
        remove_submenu_page('tools.php', 'export.php');
	}
	
}
