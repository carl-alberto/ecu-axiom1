<?php 
namespace Mu_Plugins;

defined( 'ABSPATH' ) || exit;

// Autoloader for the plugin
spl_autoload_register(function ($class_name) {

	/**
	 * Because of past negative experience did not want to rely on a convention of file/class names
	 * to be able to load a file.   Also didn't want to do parsing of the class title to determine file
	 * path.   So I settled on a array lookup with file path specified.
	 */
	$class_map = [		

		// Common functionality useful for forms and can be used thorught the MU or custom plugins.
		// Look at the site-management mu plugin for usuage.
		'Mu_Plugins\Form' => __DIR__ . '/abstract-form.php',
		
	];

	if(array_key_exists($class_name, $class_map)) {
	    require_once $class_map[$class_name];
	}
});

/**
 * Make the heartbeat interval greater
 * 
 * @see https://developer.wordpress.org/reference/hooks/heartbeat_settings/
 */
add_filter( 'heartbeat_settings', __NAMESPACE__ . '\change_heartbeat_settings' );
function change_heartbeat_settings( $settings ) {
    $settings['interval'] = 120; 
    return $settings;
}

/**
 * Only do these things if you are not on the logged in with the super admin account.
 *
 * @see https://developer.wordpress.org/reference/hooks/admin_init/
 * @see https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
 */
add_action('admin_init', __NAMESPACE__ . '\clean_non_admins', 10, 0);
function clean_non_admins() {
	if(!is_super_admin()) {

		// Hide various menu items via css
		add_action('admin_enqueue_scripts', function() { wp_enqueue_style('ecu-user-style', '/wp-content/mu-plugins/includes/css/non-admin-styles.css'); }, 10,0);

		// Hide acf menu
		add_filter('acf/settings/show_admin',  '__return_false');

		// Turn off update checks for plugins and themes
		add_filter( 'auto_update_plugin', '__return_false' );
		add_filter( 'auto_update_theme', '__return_false' );

		// Disable the WP update notificatons and nags.
		// https://jasonjalbuena.com/disable-wordpress-update-notifications/
		add_filter('pre_site_transient_update_core',__NAMESPACE__ . '\remove_core_updates');
		add_filter('pre_site_transient_update_plugins',__NAMESPACE__ . '\remove_core_updates');
		add_filter('pre_site_transient_update_themes',__NAMESPACE__ . '\remove_core_updates');
		
		// Disable siteorigin notice about new widgets after updates
		update_option( 'siteorigin_widgets_new_widgets', array() );
	}
}
function remove_core_updates () {
	global $wp_version;
	return(object) array(
		 'last_checked'=> time(),
		 'version_checked'=> $wp_version,
		 'updates' => array()
	);
}

/**
 * Returns an array of allowed HTML tags and attributes for a given context.
 *
 * @see https://developer.wordpress.org/reference/functions/wp_kses_allowed_html/
 */
add_filter('wp_kses_allowed_html', __NAMESPACE__ . '\allowed_tags');
function allowed_tags($tags) {

	$add_to_all_tags = array(

	    'aria-checked' => true,
	    'aria-controls' => true,
	    'aria-describedby' => true,
        'aria-required' => true,
	    'aria-disabled' => true,
	    'aria-grabbed' => true,
	    'aria-hidden' => true,
        'aria-label' => true,
        'aria-labelledby' => true,
        'aria-pressed' => true,
   	    'aria-selected' => true,
   	    'tabindex' => true,
   	    'v-bind' => true,  // Start VUE Template attrs  
   	    'v-cloak' => true,  
   	    'v-else' => true,  
   	    'v-else-if' => true,  
   	    'v-for' => true,
   	    'v-html' => true,  
   	    'v-if' => true,  
   	    'v-model' => true,  
   	    'v-on' => true,  
   	    'v-once' => true,  
   	    'v-pre' => true,  
   	    'v-show' => true,  
   	    'v-slot' => true,  
   	    'v-text' => true,  
   	    ':alt' => true,  
   	    ':href' => true,  
   	    ':src' => true, // END VUE Template attrs        
     );
 
     foreach($tags as $key => $value) {
         $tags[$key] = array_merge($value, $add_to_all_tags);
     }
	return $tags;
}	

/**
 * Filters the TinyMCE buttons.
 *
 * @see https://developer.wordpress.org/reference/hooks/mce_buttons/
 */
add_filter( 'mce_buttons', __NAMESPACE__ . '\mce_buttons_1' );
function mce_buttons_1($buttons){
	$remove = 'formatselect';
    if ( ( $key = array_search( $remove, $buttons ) ) !== false ) {
		unset( $buttons[$key] );
	}
	array_unshift( $buttons, 'styleselect' );

	return $buttons;
}

/**
 * Filters the TinyMCE config before init.
 *
 * @see https://developer.wordpress.org/reference/hooks/mce_buttons/
 */
add_filter( 'tiny_mce_before_init', __NAMESPACE__ . '\format_tinymce' );
function format_tinymce($init){
	
	if($_SERVER['PHP_SELF'] == '/wp-admin/widgets.php'){ 
		// Stop the Site Origin Editor Widget from breaking.
		return $init;
	}

	// Give Creative Services and admins more HTML attributes then a normal user is able to use.
	if(!current_user_can('administrator') && !current_user_can('page_css')){
        //HTML Tags to allow, [*] denotes all attributes are allowed.
        $init['valid_elements'] = "role[*],a[*],abbr[*],acronym[*],address[*],article[*],aside[*],audio[*],b[*],blockquote[*],br[*],button[*],caption[*],center[*],cite[*],code[*],col[*],colgroup[*],datalist[*],dd[*],del[*],details[*],dfn[*],dir[*],div[*],dl[*],dt[*],em[*],fieldset[*],figcaption[*],figure[*],h2[*],h3[*],h4[*],h5[*],h6[*],hr[*],i[*],img[*],ins[*],kbd[*],label[*],legend[*],li[*],mark[*],noscript[*],ol[*],optgroup[*],option[*],p[*],picture[*],pre[*],progress[*],q[*],s[*],small[*],span[*],strong[*],sub[*],sup[*],time[*],u[*],ul[*],video[*]";
    }
	
	// Custom Formats Dropdown
	$init['style_formats'] =
		'[{ "title": "Headers", "items": [
			{ "title": "Header 2", "block": "h2"},
			{ "title": "Header 3", "block": "h3"},
			{ "title": "Header 4", "block": "h4"},
			{ "title": "Header 5", "block": "h5"},
			{ "title": "Header 6", "block": "h6"},
		] },

		{ "title": "Size", "items": [
			{ "title": "Largest", inline: "span", classes: "ecu-h3"},
			{ "title": "Larger", inline: "span", classes: "ecu-h4"},
			{ "title": "Large", inline: "span", classes: "ecu-h5"},
		] },

		{ "title": "Inline", "items": [
			{ "title": "Underline", "icon": "underline", "format": "underline" },
			{ "title": "Superscript", "icon": "superscript", "format": "superscript" },
			{ "title": "Subscript", "icon": "subscript", "format": "subscript" },
			{ "title": "Code", "icon": "code", "format": "code" },
			{ "title": "Emphasis", "icon": "italic", inline: "span", classes: "ecu-em" }
		] },

		{ "title": "Blocks", "items": [
			{ "title": "Paragraph", "format": "p" },
			{ "title": "Pre", "format": "pre" },
					{ "title": "Lead Paragraph", "inline" : "span", "classes" : "lead"}
		] },
		
		{"title": "Buttons", "items": [
			{ "title": "Ribbon Button", "selector": "a", "classes":"btn-ribbon-simple"},
			{ "title": "Ribbon Button w/ Arrow", "selector": "a", "classes":"btn-ribbon"}
		]}
	]';

	 return $init;
}
