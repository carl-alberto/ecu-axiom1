<?php
namespace Site_Management;

use \Ldap\Ad_User as Ad_User;
use \WP_User as WP_User;
use \Intranet\Intranet as Intranet;
use \SiteOrigin_Widgets_Bundle as SiteOrigin_Widgets_Bundle;

defined( 'ABSPATH' ) OR exit;

class Init_Site {

	public function init_cron(){
		$cron_jobs = get_option('cron');
		$allowed = array(
			'wp_scheduled_delete', // WP Core
			'wp_scheduled_auto_draft_delete', // WP Core
			'update_network_counts', // WP Core
			'delete_expired_transients', // WP Core
			'wp_privacy_delete_old_export_files', // WP Core
			'et_core_page_resource_auto_clear', // Monarch Plugin
			'flow_flow_load_cache', // Flow Flow Plugin
			'flow_flow_load_cache_4disabled', // Flow Flow Plugin
			'wp_cron', // Cron Job to kick off all cron jobs
			'postExpiratorExpire'
		);
		$result = '<h3>Updated Cron Jobs</h3><ul>';
		$result .= '<ul>';
		foreach($cron_jobs as $key => $job) {
			if(!is_array($job)) {
				continue;
			}
			$job_name = array_keys($job);
			$job_name = $job_name[0];

			if(!in_array($job_name, $allowed)){
				wp_clear_scheduled_hook($job_name);
				$result .= '<li>Removed ' . $job_name .' job.</li>';
			} else {
				$result .= '<li>'. $job_name .' is allowed.</li>';
			}
		}
		$result .= '</ul>';
		return $result;
	}

	public function activate_plugins()	{
		$result = '<h3>Activate Site Plugins</h3><ul>';
		// array ( plugin dir / plugin file )
		$plugins = array(
			'ecu-admin-2/ecu-admin-2.php',
			'envira-gallery/envira-gallery.php',
			'so-widgets-bundle/so-widgets-bundle.php',
			'wp-crontrol/wp-crontrol.php',
			'envira-fullscreen/envira-fullscreen.php',
			'envira-pagination/envira-pagination.php',
			'envira-schedule/envira-schedule.php',
			'envira-slideshow/envira-slideshow.php',
			'envira-zip-importer/envira-zip-importer.php',
			'monarch/monarch.php',
			'vendi-tinymce-anchor/vendi-tinymce-anchor.php',
			'ninja-forms/ninja-forms.php',
			'ninja-forms-conditionals/conditionals.php',
			'ninja-forms-excel-export/ninja-forms-excel-export.php',
			'ninja-forms-multi-part/multi-part.php',
			'ninja-forms-uploads/file-uploads.php',
			'ninja-forms-style/ninja-forms-style.php',
			'ninja-forms-save-progress/ninja-forms-save-progress.php',
			'ninja-forms-pdf-submissions/nf-pdf-submissions.php',
			'shortcode-ui/shortcode-ui.php',
			'tablepress/tablepress.php',
			'wp-localist/wp-localist.php',
			'ecu-plugins/ecu-plugins.php',
			'user-role-editor-pro/user-role-editor-pro.php',
			'enable-media-replace/enable-media-replace.php',
			'classic-editor/classic-editor.php',
			'display-posts-shortcode/display-posts-shortcode.php',
			'safe-svg/safe-svg.php',
			'post-expirator/post-expirator.php',
			'ecu-so-widgets/ecu-so-widgets.php',
			'social-media-meta/social-media-meta.php',
			'advanced-custom-fields-pro/acf.php',
		);

		foreach ($plugins as $plugin) {
			if( ! is_plugin_active( $plugin  ) ) {
				$error = activate_plugin($plugin, '');
				if ( !is_wp_error( $error ) ) {
					$result .= '<li>' . $plugin . ' activated.</li>';
				} else {
					$result .= '<li>Error: ' . $plugin . ' ' . $error->get_error_message() . '</li>';
				}
		    } else {
				$result .= '<li>' . $plugin . ' already activated.</li>';
		    }
		}
		$result .= '</ul>';
		return $result;
	}

	public function init_widgets()	{
		// configure site origin widgets
		$result = '<h3>Init Site Widgets</h3><ul>';
		//Deactivate Unwanted Widgets
		$undesired_so_widgets = array(
			'accordion',
			'tabs',
			'contact',
			'google-map',
			'price-table',
			'image-grid',
			'post-carousel',
			'layout-slider',
			'simple-masonry',
			'social-media-buttons',
			'testimonial',
			'video',
			'slider',
		);
		//Activate Wanted Widgets
		$desired_so_widgets = array(
			'button',
			'ecu-alert',
			'features',
			'headline',
			'cta',
			'editor',
			'hero',
			'icon',
			'image',
			'taxonomy',
		);
		$so = SiteOrigin_Widgets_Bundle::single();
		$so->get_widget_folders(); // init so the foreach won't have warning when activating.
		foreach ($undesired_so_widgets as $widget){
			$so->deactivate_widget($widget);
			$result .= '<li>Site Origin ' . $widget . ' widget deactivated</li>';
		}
		foreach ($desired_so_widgets as $widget){
			$so->activate_widget($widget);
			$result .= '<li>Site Origin ' . $widget . ' widget activated</li>';
		}
		$result .= '</ul>';
		return $result;
	}

	public function init_roles() {

		$result = '<h3>Add User Roles</h3>';
		$result .= '<ul>';

		$intranet = new Intranet();
		$intranet->plugin_activation();
		$result .= '<li>Intranet roles capabilities reset and roles added!</li>';

		//add role. if already exists then check capabilities and add any that are missing.
		$capabilities = get_role( 'editor' )->capabilities;
		$capabilities['manage_options'] = true;
		$capabilities['assign_feed_blacklist_terms'] = true;
		$capabilities['assign_feed_item_terms'] = true;
		$capabilities['assign_feed_source_terms'] = true;
		$capabilities['assign_feed_template_terms'] = true;
		$capabilities['create_envira_galleries'] = true;
		$capabilities['delete_envira_galleries'] = true;
		$capabilities['delete_envira_gallery'] = true;
		$capabilities['delete_feed_blacklist'] = true;
		$capabilities['delete_feed_blacklist_terms'] = true;
		$capabilities['delete_feed_blacklists'] = true;
		$capabilities['delete_feed_item'] = true;
		$capabilities['delete_feed_item_terms'] = true;
		$capabilities['delete_feed_items'] = true;
		$capabilities['delete_feed_source'] = true;
		$capabilities['delete_feed_source_terms'] = true;
		$capabilities['delete_feed_sources'] = true;
		$capabilities['delete_feed_template'] = true;
		$capabilities['delete_feed_template_terms'] = true;
		$capabilities['delete_feed_templates'] = true;
		$capabilities['delete_others_envira_galleries'] = true;
		$capabilities['delete_others_feed_blacklists'] = true;
		$capabilities['delete_others_feed_items'] = true;
		$capabilities['delete_others_feed_sources'] = true;
		$capabilities['delete_others_feed_templates'] = true;
		$capabilities['delete_private_envira_galleries'] = true;
		$capabilities['delete_private_feed_blacklists'] = true;
		$capabilities['delete_private_feed_items'] = true;
		$capabilities['delete_private_feed_sources'] = true;
		$capabilities['delete_private_feed_templates'] = true;
		$capabilities['delete_published_envira_galleries'] = true;
		$capabilities['delete_published_feed_blacklists'] = true;
		$capabilities['delete_published_feed_items'] = true;
		$capabilities['delete_published_feed_sources'] = true;
		$capabilities['delete_published_feed_templates'] = true;
		$capabilities['delete_tablepress_tables'] = true;
		$capabilities['edit_envira_galleries'] = true;
		$capabilities['edit_envira_gallery'] = true;
		$capabilities['edit_feed_blacklist'] = true;
		$capabilities['edit_feed_blacklist_terms'] = true;
		$capabilities['edit_feed_blacklists'] = true;
		$capabilities['edit_feed_item'] = true;
		$capabilities['edit_feed_item_terms'] = true;
		$capabilities['edit_feed_items'] = true;
		$capabilities['edit_feed_source'] = true;
		$capabilities['edit_feed_source_terms'] = true;
		$capabilities['edit_feed_sources'] = true;
		$capabilities['edit_feed_template'] = true;
		$capabilities['edit_feed_template_terms'] = true;
		$capabilities['edit_feed_templates'] = true;
		$capabilities['edit_other_envira_galleries'] = true;
		$capabilities['edit_other_envira_gallery'] = true;
		$capabilities['edit_other_tablepress_tables'] = true;
		$capabilities['edit_others_envira_galleries'] = true;
		$capabilities['edit_others_feed_blacklists'] = true;
		$capabilities['edit_others_feed_items'] = true;
		$capabilities['edit_others_feed_sources'] = true;
		$capabilities['edit_others_feed_templates'] = true;
		$capabilities['edit_others_pages'] = true;
		$capabilities['edit_others_posts'] = true;
		$capabilities['edit_others_tablepress_tables'] = true;
		$capabilities['edit_pages'] = true;
		$capabilities['edit_plugins'] = true;
		$capabilities['edit_posts'] = true;
		$capabilities['edit_private_envira_galleries'] = true;
		$capabilities['edit_private_feed_blacklists'] = true;
		$capabilities['edit_private_feed_items'] = true;
		$capabilities['edit_private_feed_sources'] = true;
		$capabilities['edit_private_feed_templates'] = true;
		$capabilities['edit_private_pages'] = true;
		$capabilities['edit_private_posts'] = true;
		$capabilities['edit_published_envira_galleries'] = true;
		$capabilities['edit_published_feed_blacklists'] = true;
		$capabilities['edit_published_feed_items'] = true;
		$capabilities['edit_published_feed_sources'] = true;
		$capabilities['edit_published_feed_templates'] = true;
		$capabilities['edit_published_pages'] = true;
		$capabilities['edit_published_posts'] = true;
		$capabilities['edit_tablepress_tables'] = true;
		$capabilities['edit_theme_options'] = true;
		$capabilities['manage_categories'] = true;
		$capabilities['manage_feed_blacklist_terms'] = true;
		$capabilities['manage_feed_item_terms'] = true;
		$capabilities['manage_feed_settings'] = true;
		$capabilities['manage_feed_source_terms'] = true;
		$capabilities['manage_feed_template_terms'] = true;
		$capabilities['manage_links'] = true;
		$capabilities['publish_envira_galleries'] = true;
		$capabilities['publish_feed_blacklists'] = true;
		$capabilities['publish_feed_items'] = true;
		$capabilities['publish_feed_sources'] = true;
		$capabilities['publish_feed_templates'] = true;
		$capabilities['publish_tablepress_tables'] = true;
		$capabilities['publishpress_future_expire_post'] = true;
		$capabilities['read'] = true;
		$capabilities['read_envira_gallery'] = true;
		$capabilities['read_feed_blacklist'] = true;
		$capabilities['read_feed_item'] = true;
		$capabilities['read_feed_source'] = true;
		$capabilities['read_feed_template'] = true;
		$capabilities['read_private_envira_galleries'] = true;
		$capabilities['read_private_feed_blacklists'] = true;
		$capabilities['read_private_feed_items'] = true;
		$capabilities['read_private_feed_sources'] = true;
		$capabilities['read_private_feed_templates'] = true;
		$capabilities['read_private_galleries'] = true;
		$capabilities['read_private_tablepress_tables'] = true;
		$capabilities['tablepress_access_about_screen'] = true;
		$capabilities['tablepress_access_options_screen'] = true;
		$capabilities['tablepress_add_tables'] = true;
		$capabilities['tablepress_copy_tables'] = true;
		$capabilities['tablepress_delete_tables'] = true;
		$capabilities['tablepress_edit_tables'] = true;
		$capabilities['tablepress_export_tables'] = true;
		$capabilities['tablepress_import_tables'] = true;
		$capabilities['tablepress_list_tables'] = true;
		$capabilities['theme_settings'] = true;
		$capabilities['upload_files'] = true;
		$capabilities['wpseo_bulk_edit'] = true;
		$capabilities['wpseo_edit_advanced_metadata'] = true;

		if($role = get_role('blog_owner')) {
			$add = array_diff_assoc ($capabilities, $role->capabilities);
			foreach($add as $cap => $value) {
				$role->add_cap( $cap, $value );
				$result .= '<li>' . $cap . ' added to ' . $role->name . ' role!</li>';
			}
			$remove = array_diff_assoc ($role->capabilities, $capabilities);
			foreach($remove as $cap => $value) {
				$role->remove_cap( $cap );
				$result .= '<li>' . $cap . ' removed from ' . $role->name . ' role!</li>';
			}
			$result .= '<li>' . $role->name . ' role capabilities reset!</li>';
		} else {
			add_role( 'blog_owner', 'Blog Owner',  $capabilities);
			$result .= '<li>blog_owner role Added!</li>';
		}

		// creative services
		$capabilities['activate_plugins'] = true;
		$capabilities['edit_users'] = true;
		$capabilities['list_users'] = true;
		$capabilities['promote_users'] = true;
		$capabilities['switch_themes'] = true;
		$capabilities['page_css'] = true; // Allows user to use blank template and add css on post/pages
		$capabilities['site_css'] = true; // Allows user to use a site wide css menu
		if($role = get_role('creative_services')) {
			$add = array_diff_assoc ($capabilities, $role->capabilities);
			foreach($add as $cap => $value) {
				$role->add_cap( $cap, $value );
				$result .= '<li>' . $cap . ' added to ' . $role->name . ' role!</li>';
			}
			$remove = array_diff_assoc ($role->capabilities, $capabilities);
			foreach($remove as $cap => $value) {
				$role->remove_cap( $cap );
				$result .= '<li>' . $cap . ' removed from ' . $role->name . ' role!</li>';
			}
			$result .= '<li>' . $role->name . ' role capabilities reset!</li>';
		} else {
			add_role( 'creative_services', 'Creative Services',  $capabilities);
			$result .= '<li>creative_services role Added!</li>';
		}

		// ITCS Support are admins with a few additonal capabilites.
		$capabilities = get_role( 'administrator' )->capabilities;
		$capabilities['activate_plugins'] = true;
		$capabilities['assign_feed_blacklist_terms'] = true;
		$capabilities['assign_feed_item_terms'] = true;
		$capabilities['assign_feed_source_terms'] = true;
		$capabilities['assign_feed_template_terms'] = true;
		$capabilities['create_sites'] = true;
		$capabilities['create_envira_galleries'] = true;
		$capabilities['delete_envira_galleries'] = true;
		$capabilities['delete_envira_gallery'] = true;
		$capabilities['delete_feed_blacklist'] = true;
		$capabilities['delete_feed_blacklist_terms'] = true;
		$capabilities['delete_feed_blacklists'] = true;
		$capabilities['delete_feed_item'] = true;
		$capabilities['delete_feed_item_terms'] = true;
		$capabilities['delete_feed_items'] = true;
		$capabilities['delete_feed_source'] = true;
		$capabilities['delete_feed_source_terms'] = true;
		$capabilities['delete_feed_sources'] = true;
		$capabilities['delete_feed_template'] = true;
		$capabilities['delete_feed_template_terms'] = true;
		$capabilities['delete_feed_templates'] = true;
		$capabilities['delete_others_envira_galleries'] = true;
		$capabilities['delete_others_feed_blacklists'] = true;
		$capabilities['delete_others_feed_items'] = true;
		$capabilities['delete_others_feed_sources'] = true;
		$capabilities['delete_others_feed_templates'] = true;
		$capabilities['delete_others_pages'] = true;
		$capabilities['delete_others_posts'] = true;
		$capabilities['delete_pages'] = true;
		$capabilities['delete_plugins'] = true;
		$capabilities['delete_posts'] = true;
		$capabilities['delete_private_envira_galleries'] = true;
		$capabilities['delete_private_feed_blacklists'] = true;
		$capabilities['delete_private_feed_items'] = true;
		$capabilities['delete_private_feed_sources'] = true;
		$capabilities['delete_private_feed_templates'] = true;
		$capabilities['delete_private_pages'] = true;
		$capabilities['delete_private_posts'] = true;
		$capabilities['delete_published_envira_galleries'] = true;
		$capabilities['delete_published_feed_blacklists'] = true;
		$capabilities['delete_published_feed_items'] = true;
		$capabilities['delete_published_feed_sources'] = true;
		$capabilities['delete_published_feed_templates'] = true;
		$capabilities['delete_published_pages'] = true;
		$capabilities['delete_published_posts'] = true;
		$capabilities['delete_sites'] = true;
		$capabilities['delete_tablepress_tables'] = true;
		$capabilities['delete_users'] = true;
		$capabilities['edit_dashboard'] = true;
		$capabilities['edit_envira_galleries'] = true;
		$capabilities['edit_envira_gallery'] = true;
		$capabilities['edit_feed_blacklist'] = true;
		$capabilities['edit_feed_blacklist_terms'] = true;
		$capabilities['edit_feed_blacklists'] = true;
		$capabilities['edit_feed_item'] = true;
		$capabilities['edit_feed_item_terms'] = true;
		$capabilities['edit_feed_items'] = true;
		$capabilities['edit_feed_source'] = true;
		$capabilities['edit_feed_source_terms'] = true;
		$capabilities['edit_feed_sources'] = true;
		$capabilities['edit_feed_template'] = true;
		$capabilities['edit_feed_template_terms'] = true;
		$capabilities['edit_feed_templates'] = true;
		$capabilities['edit_other_envira_galleries'] = true;
		$capabilities['edit_other_envira_gallery'] = true;
		$capabilities['edit_other_tablepress_tables'] = true;
		$capabilities['edit_others_envira_galleries'] = true;
		$capabilities['edit_others_feed_blacklists'] = true;
		$capabilities['edit_others_feed_items'] = true;
		$capabilities['edit_others_feed_sources'] = true;
		$capabilities['edit_others_feed_templates'] = true;
		$capabilities['edit_others_pages'] = true;
		$capabilities['edit_others_posts'] = true;
		$capabilities['edit_others_tablepress_tables'] = true;
		$capabilities['edit_pages'] = true;
		$capabilities['edit_plugins'] = true;
		$capabilities['edit_posts'] = true;
		$capabilities['edit_private_envira_galleries'] = true;
		$capabilities['edit_private_feed_blacklists'] = true;
		$capabilities['edit_private_feed_items'] = true;
		$capabilities['edit_private_feed_sources'] = true;
		$capabilities['edit_private_feed_templates'] = true;
		$capabilities['edit_private_pages'] = true;
		$capabilities['edit_private_posts'] = true;
		$capabilities['edit_published_envira_galleries'] = true;
		$capabilities['edit_published_feed_blacklists'] = true;
		$capabilities['edit_published_feed_items'] = true;
		$capabilities['edit_published_feed_sources'] = true;
		$capabilities['edit_published_feed_templates'] = true;
		$capabilities['edit_published_pages'] = true;
		$capabilities['edit_published_posts'] = true;
		$capabilities['edit_tablepress_tables'] = true;
		$capabilities['edit_theme_options'] = true;
		$capabilities['edit_themes'] = true;
		$capabilities['edit_users'] = true;
		$capabilities['et_support_center'] = true;
		$capabilities['et_support_center_documentation'] = true;
		$capabilities['et_support_center_logs'] = true;
		$capabilities['et_support_center_remote_access'] = true;
		$capabilities['et_support_center_safe_mode'] = true;
		$capabilities['et_support_center_system'] = true;
		$capabilities['export'] = true;
		$capabilities['import'] = true;
		$capabilities['manage_categories'] = true;
		$capabilities['manage_feed_blacklist_terms'] = true;
		$capabilities['manage_feed_item_terms'] = true;
		$capabilities['manage_feed_settings'] = true;
		$capabilities['manage_feed_source_terms'] = true;
		$capabilities['manage_feed_template_terms'] = true;
		$capabilities['manage_js'] = true;
		$capabilities['manage_links'] = true;
		$capabilities['manage_network'] = true;
		$capabilities['manage_network_options'] = true;
		$capabilities['manage_network_plugins'] = true;
		$capabilities['manage_network_themes'] = true;
		$capabilities['manage_network_users'] = true;
		$capabilities['manage_options'] = true;
		$capabilities['manage_sites'] = true;
		$capabilities['moderate_comments'] = true;
		$capabilities['nf_sub'] = true;
		$capabilities['promote_users'] = true;
		$capabilities['publish_envira_galleries'] = true;
		$capabilities['publish_feed_blacklists'] = true;
		$capabilities['publish_feed_items'] = true;
		$capabilities['publish_feed_sources'] = true;
		$capabilities['publish_feed_templates'] = true;
		$capabilities['publish_pages'] = true;
		$capabilities['publish_posts'] = true;
		$capabilities['publish_tablepress_tables'] = true;
		$capabilities['read'] = true;
		$capabilities['read_envira_gallery'] = true;
		$capabilities['read_feed_blacklist'] = true;
		$capabilities['read_feed_item'] = true;
		$capabilities['read_feed_source'] = true;
		$capabilities['read_feed_template'] = true;
		$capabilities['read_private_envira_galleries'] = true;
		$capabilities['read_private_feed_blacklists'] = true;
		$capabilities['read_private_feed_items'] = true;
		$capabilities['read_private_feed_sources'] = true;
		$capabilities['read_private_feed_templates'] = true;
		$capabilities['read_private_galleries'] = true;
		$capabilities['read_private_pages'] = true;
		$capabilities['read_private_posts'] = true;
		$capabilities['read_private_tablepress_tables'] = true;
		$capabilities['remove_users'] = true;
		$capabilities['switch_themes'] = true;
		$capabilities['tablepress_access_about_screen'] = true;
		$capabilities['tablepress_access_options_screen'] = true;
		$capabilities['tablepress_add_tables'] = true;
		$capabilities['tablepress_copy_tables'] = true;
		$capabilities['tablepress_delete_tables'] = true;
		$capabilities['tablepress_edit_options'] = true;
		$capabilities['tablepress_edit_tables'] = true;
		$capabilities['tablepress_export_tables'] = true;
		$capabilities['tablepress_import_tables'] = true;
		$capabilities['tablepress_list_tables'] = true;
		$capabilities['theme_settings'] = true;
		$capabilities['unfiltered_upload'] = true;
		$capabilities['upload_files'] = true;
		$capabilities['ure_manage_options'] = true;
		$capabilities['view_site_health_checks'] = true;
		$capabilities['wp_manage_intranet'] = true;
		$capabilities['wpseo_bulk_edit'] = true;
		$capabilities['wpseo_edit_advanced_metadata'] = true;
		$capabilities['wpseo_manage_options'] = true;
		$capabilities['page_css'] = true; // Allows user to use blank template and add css on post/pages
		$capabilities['site_css'] = true; // Allows user to use a site wide css menu
		$capabilities['tablepress_import_tables_wptr'] = true;

		//add role. if already exists then check capabilities and add any that are missing.
		if($role = get_role('itcs_support')) {
			$add = array_diff_assoc ($capabilities, $role->capabilities);
			foreach($add as $cap => $value) {
				$role->add_cap( $cap, $value );
				$result .= '<li>' . $cap . ' added to ' . $role->name . ' role!</li>';
			}
			$remove = array_diff_assoc ($role->capabilities, $capabilities);
			foreach($remove as $cap => $value) {
				$role->remove_cap( $cap );
				$result .= '<li>' . $cap . ' removed from ' . $role->name . ' role!</li>';
			}
			$result .= '<li>' . $role->name . ' role capabilities reset!</li>';
		} else {
			add_role( 'itcs_support', 'ITCS Support', $capabilities);
			$result .= '<li>itcs_support role added!</li>';
		}

		$result .= '</ul>';

		return $result;
	}

	public function init_cs_users() {

		$result = '<h3>Add Creative Services Users</h3>';

		$users = array(
			'butlerr18',
			'cessnara18',
			'irwind16',
			'syersk14',
			'webbb16',
			'webstergl18'
		);

		$result .= '<ul>';

		foreach($users as $id) {

			$user = new Ad_User($id);

			$r = $user->add_user_to_blog(get_current_blog_id(), 'creative_services' );
			if(is_wp_error($r)) {
				$result .= '<li><strong>Error:</strong> ' . $r->get_error_message() . '</li>';
			} else {
				$result .= '<li>' . $r . '</li>';
			}
			// Add roles that the user doesn't have
			$user = new WP_User( $user->get_wp_id() );
			$user->set_role('creative_services');
			$result .= '<li>' . $id . ' has been assigned the creative_services role on blog.</li>';
		}
		$result .= '</ul>';
		return $result;

	}

	public function init_itcs_users() {

		$result = '<h3>Add ITCS Support Users</h3>';

		$users = array(
			'ballengeem',
			'krochmalnyd',
			'lucasc',
			'williamsjoy17',
			'hazelipd21',
			'hudnellk22'
		);

		$result .= '<ul>';

		foreach($users as $id) {

			$user = new Ad_User($id);

			$r = $user->add_user_to_blog(get_current_blog_id(), 'itcs_support' );
			if(is_wp_error($r)) {
				$result .= '<li><strong>Error:</strong> ' . $r->get_error_message() . '</li>';
			} else {
				$result .= '<li>' . $r . '</li>';
			}
			// Add roles that the user doesn't have
			$user = new WP_User( $user->get_wp_id() );
			$user->set_role('itcs_support');
			$result .= '<li>' . $id . ' has been assigned the itcs_support role on blog.</li>';
			$user->add_role(Intranet::ADMIN_ROLE);
			$result .= '<li>' . $id . ' has been assigned the ' . Intranet::ADMIN_ROLE . ' role on blog.</li>';
		}
		$result .= '</ul>';
		return $result;
	}

	//https://codex.wordpress.org/Option_Reference
	function init_options()	{
		$result = '<h3>Set Site Options</h3><ul>';

		// Cleanup of an option no longer used 7/1/2020.  Can remove in 2021
    	delete_option('ecu-admin-site-init');

		//disallow comments and pingbacks
		update_option('default_pingback_flag', '1');
		$result .= '<li>Attempt to notify any blogs linked to from the article </li>';

		update_option('require_name_email', '1');
		$result .= '<li>Comment author must fill out name and email</li>';

		update_option('default_comment_status', 'closed');
		$result .= '<li>Disallow Comments</li>';

		update_option('default_ping_status', 'closed');
		$result .= '<li>Disallow Pingbacks</li>';

		update_option('comment_registration', '1');
		$result .= '<li>Users must be registered and logged in to comment    </li>';

		update_option('moderation_notify', '1');
		$result .= '<li>Email When A comment is held for moderation</li>';

		update_option('comments_notify', '1');
		$result .= '<li>Email When Anyone posts a comment   </li>';

		update_option('comment_moderation', '1');
		$result .= '<li>Comment must be manually approved</li>';

		update_option('comment_whitelist', '1');
		$result .= '<li>Comment author must have a previously approved comment</li>';

		update_option('close_comments_days_old', '1');
		update_option('close_comments_for_old_posts', '1');
		$result .= '<li>Automatically close comments on articles older than 1 day </li>';

		update_option('thread_comments', '');
		$result .= '<li>Disable threaded comments</li>';

		return $result;
	}
}