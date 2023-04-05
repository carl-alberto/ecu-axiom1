<?php 
/**
 * Recently Updated
 *
 * @package     RecentlyUpdated
 * @author      ATWebDev
 * @copyright   2019 East Carolina University
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Recently Updated
 * Plugin URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Description: Adds a recently updated feed for approved post types to the admin dashboard.
 * Version:     1.0.0
 * Author:      ATWebDev
 * Author URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Text Domain: ldap-login
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace Recently_Updated;

defined( 'ABSPATH' ) || exit;

/**
 * Add custom CSS required for changes made in this file
 * 
 * @see https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
 */
add_action('admin_enqueue_scripts', function() { wp_enqueue_style('ecu-recently-updated-style', '/wp-content/mu-plugins/includes/recently-updated/css/style.css'); }, 10,0);

/**
 * Adds Recently Updated feed to admin dashboard.
 *
 * @see https://developer.wordpress.org/reference/hooks/wp_dashboard_setup/
 */
add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\dashboard_widgets', 10, 0 );
function dashboard_widgets() {
	wp_add_dashboard_widget(
		'recently_updated_feed',
		'Recently Updated Feed',
		__NAMESPACE__ . '\recently_updated_feed'
	);
}
function recently_updated_feed() {
	global $wpdb;

	$revisions = $wpdb->get_results("
		SELECT post_parent, post_title, post_author, post_modified
		FROM {$wpdb->prefix}posts
		WHERE post_type = 'revision'
		ORDER BY post_modified DESC
		LIMIT 100"
	);
	if(count($revisions) > 0){
		$results = array();
		$allowed = array('page', 'post', 'service', 'tutorial', 'faq');
		foreach($revisions as $revision){
			if(count($results) > 9)
				break;

			if(!array_key_exists($revision->post_parent, $results)){
				if(in_array(get_post_type($revision->post_parent), $allowed)){
					$results[$revision->post_parent] = $revision;
				}
			};
		}
		if(count($results) > 0){
			$output = '<div id="recently_update_feed_wrap" class="postbox">
			<table id="recently_updated_feed">
				<thead>
					<th>Title</th>
					<th>Editor</th>
					<th>Date Modified</th>
				</thead>
				<tbody>';
			foreach ( $results as $result ){
				$output .= "<tr>
					<td><a href='".get_the_permalink($result->post_parent)."' target='_blank'>{$result->post_title}</a></td>
					<td>".get_the_author_meta('user_nicename', $result->post_author)."</td>
					<td>".date('M jS, Y @ g:iA', strtotime($result->post_modified))."</td>
				</tr>";
			}
			$output .= '</table></div>';
			echo $output;
		} else {
			echo 'No revisions available';
		}
	} else {
		echo 'No revisions available';
	}
}

