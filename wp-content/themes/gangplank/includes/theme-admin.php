<?php

/**
 * Loads stylesheet to editor
 *
 */
add_editor_style(get_template_directory_uri() . '/css/admin.css');

/**
 * Adds WP menus for options pages
 *
 */
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'ECU Theme Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'ecu-theme-settings',
		'capability'	=> 'theme_settings',
		'redirect'		=> false
	));
	acf_add_options_page(array(
		'page_title' 	=> 'Theme Styles',
		'menu_title'	=> 'Theme Styles',
		'menu_slug' 	=> 'ecu-theme-styles',
		'capability'	=> 'site_css',
		'redirect'		=> false
	));
	if(get_option('stylesheet') == 'gangplank-itcs'){
		acf_add_options_sub_page(array(
			'page_title' => 'ITCS Admin',
			'menu_title' => 'ITCS Admin',
			'parent_slug' => 'ecu-theme-settings'
		));
	}
	$current_user = wp_get_current_user();
	if($current_user->user_login == 'ITCSWebDev'){
		  acf_add_options_sub_page(array(
			  'page_title' => 'Super Admin',
			  'menu_title' => 'Super Admin',
			  'parent_slug' => 'ecu-theme-settings'
		  ));
	};
}

/**
 * Removes default WP dashboard widgets
 *
 */
function dashboard_widgets() {
	global $wp_meta_boxes;

   unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
   unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
   unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
   unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
   unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
   unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
   unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
   unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);

	add_meta_box('id', 'Template Breakdown', 'template_breakdown', 'dashboard', 'side', 'high');
}
add_action( 'wp_dashboard_setup', 'dashboard_widgets' );

/**
 * Prevents the editing of blank template pages if user does not have correct permissions
 *
 */
add_action( 'load-post.php', 'redirect_no_custom_css' );
function redirect_no_custom_css(){
    if(is_admin() && !empty($_GET['post']) && $_GET['action'] == 'edit'){
        if(get_page_template_slug($_GET['post']) == 'page-blank.php' && !current_user_can('administrator') && !current_user_can('page_css')){
            wp_redirect(admin_url() . 'edit.php?post_type=' . get_post_type($_GET['post']) . '&notice=restricted_page&id=' . intval($_GET['post']));
            exit;
        }
    }
}

/**
 * Generates page notice for restricted pages
 *
 */
add_action('admin_notices', 'restricted_page_notice');
function restricted_page_notice(){
    global $pagenow;
    if (($pagenow == 'edit.php') && isset($_GET['notice']) && isset($_GET['id'])) {
        if($notice == 'restricted_page'){
            $author = get_post_field('post_author', $_GET['id']);
            echo '<div class="notice notice-error is-dismissible">
                <p>For help with editing '. get_the_title($_GET['id']) . ' please contact ' . get_author_name($author).'.</p>
             </div>';
        }
    }
}

/**
 * Removes blank_template.php option if user does not have correct permissions
 *
 */
function filter_template_dropdown( $page_templates ) {
    if(!current_user_can('administrator') && !current_user_can('page_css') && isset($page_templates['page-blank.php'])){
        unset($page_templates['page-blank.php']);
    }
    return $page_templates;
}
add_filter( 'theme_page_templates', 'filter_template_dropdown' );

/**
 * Functionality for setting site wide default template
 *
 */
add_filter( 'template_include', 'default_template', 99 );
function default_template($template){
	if(get_page_template_slug() == '' && !is_archive() && !is_home()){
		$type = get_post_type();
		$templates = get_field('default_templates', 'option');
		if($type == 'page'){
			if($default = $templates['admin_page_template']){
				if($position = get_field('sidebar_position')){
					$id = get_the_id();
					if($position == 'left'){
						update_post_meta($id, '_wp_page_template', 'page-sidebar-left.php' );
					} else {
						update_post_meta($id, '_wp_page_template', 'page-sidebar-right.php' );
					}
					delete_post_meta($id, 'sidebar_position');
					wp_redirect(get_the_permalink());
					exit;
				}
				$new_template = locate_template(array($default));
				return $new_template;
			}
		} elseif ($type == 'post'){
			if($default = $templates['admin_post_template']){
				$new_template = locate_template(array($default));
				return $new_template;
			}
		}
	}
	return $template;
}

/**
 * Ticket: 7768100
 * Fix select2 issue from wp_notification_bars and acf
 */
function mtsnb_post_types() {
	if( is_admin() ) return [];
	return [ 'post', 'page' ];
}
add_filter( 'mtsnb_force_bar_post_types', 'mtsnb_post_types', 10, 3 );

/**
 * Displays dashboard widget of theme template usage
 *
 */
function template_breakdown() {
	global $wpdb;

	$default = get_field('admin_page_template', 'option') ? get_field('admin_page_template', 'option') : 'page-sidebar-right.php';

	$templates = $wpdb->get_results("
		SELECT DISTINCT meta_value as template,
		count(*) as count
		FROM {$wpdb->prefix}postmeta
		WHERE meta_key = '_wp_page_template'
		GROUP BY meta_value
	");

	$output = array();
	$defaultCount = 0;
	$total = 0;
	$output[$default] = 0;
	foreach($templates as $template){
		if($template->template != 'default'){
			$output[$template->template] = (int) $template->count;
		} else {
			$defaultCount = $template->count;
		}
		$total += $template->count;
	}

	$output[$default] += $defaultCount;

	krsort($output);
	$html = '<div id="template_breakdown_wrap" class="postbox">
	<table id="template_breakdown">
		<thead>
			<th>Template</th>
			<th>Usage</th>
		</thead>
		<tbody>';
		foreach($output as $template => $count){
			if($total == 0) {
				$percent = 0;
			} else {
				$percent = round(($count / $total) * 100, 2);
			}
			$html .= "<tr>
				<td>".get_template_display_name($template)."</td>
				<td>{$count} / {$total} ({$percent}%)</td>
			</tr>";
		}
	$html .= '</tbody>
	</table>';

	echo $html;
}

/**
 * Determines how templates should be displayed to end users
 * @param  string $template Template slug
 * @return string           Template name
 */
function get_template_display_name($template) {
	switch($template){
		case 'page-blank.php':
			return 'Blank Template';
			break;
		case 'page-services.php':
			return 'ITCS Services Template';
			break;
		case 'page-sidebar-left.php':
			return 'Left Sidebar Template';
			break;
		case 'page-ribbons.php':
			return 'Ribbon Template';
			break;
		case 'page-full-width.php':
			return 'Full Width Template';
			break;
		case 'page-sidebar-right.php':
			return 'Right Sidebar Template';
			break;
		default:
			return 'Misc Template';
	}
}

