<?php 
/**
 * JSPlus
 *
 * @package     JSPlus
 * @author      ATWebDev
 * @copyright   2019 East Carolina University
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: JSPlus
 * Plugin URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Description: Adds JSPlus to the tiny mce editor.  
 * Version:     1.0.0
 * Author:      ATWebDev
 * Author URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Text Domain: ldap-login
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
namespace JSPlus;

defined( 'ABSPATH' ) || exit;

/**
 * 
 * 
 * The acutal JSPlus files are in the plugins folder.   Be sure to delete that alone with this when the time comes!
 * 
 * 
 */


/**
 * Enables jsplus options in the mce editor.
 *
 * @see https://developer.wordpress.org/reference/hooks/mce_external_plugins/
 */
add_filter( 'mce_external_plugins',  __NAMESPACE__ . '\mce_external_plugins' );
function mce_external_plugins( $plugin_array ) {
	// Add the JS.Plus plugins for bootstrap.
	$plugin_array['jsplusInclude'] = plugins_url( '/jsplus/jsplusInclude/plugin.js' );
	$plugin_array['jsplus_bootstrap_alert'] = plugins_url( '/jsplus/jsplus_bootstrap_alert/plugin.js' );
	$plugin_array['jsplus_bootstrap_breadcrumbs'] = plugins_url( '/jsplus/jsplus_bootstrap_breadcrumbs/plugin.js' );
	$plugin_array['jsplus_bootstrap_button'] = plugins_url( '/jsplus/jsplus_bootstrap_button/plugin.js' );
	$plugin_array['jsplusBootstrapTools'] = plugins_url( '/jsplus/jsplusBootstrapTools/plugin.js' );

	return $plugin_array;
}

/**
 * Filters the TinyMCE config before init.
 *
 * @see https://developer.wordpress.org/reference/hooks/mce_buttons/
 */
add_filter( 'mce_buttons_3', __NAMESPACE__ . '\mce_buttons_3' );
function mce_buttons_3($buttons){
	
	if(!is_array($buttons)) {
		return;
	}

	// Add the JS.Plus buttons.
	array_push( $buttons, 'jsplus_bootstrap_alert' );
	array_push( $buttons, 'jsplus_bootstrap_button' );
	array_push( $buttons, 'jsplus_bootstrap_icons' );
	array_push( $buttons, 'jsplus_bootstrap_label' );
	array_push( $buttons, '|' );
	array_push( $buttons, 'jsplusShowBlocks' );
	array_push( $buttons, 'jsplusBootstrapToolsRowAdd' );
	array_push( $buttons, 'jsplusBootstrapToolsRowAddBefore ' );
	array_push( $buttons, 'jsplusBootstrapToolsRowAddAfter' );
	array_push( $buttons, 'jsplusBootstrapToolsRowDelete' );
	array_push( $buttons, 'jsplusBootstrapToolsRowMoveUp' );
	array_push( $buttons, 'jsplusBootstrapToolsRowMoveDown' );
	array_push( $buttons, 'jsplusBootstrapToolsColEdit' );
	array_push( $buttons, 'jsplusBootstrapToolsColAdd' );
	array_push( $buttons, 'jsplusBootstrapToolsColAddBefore' );
	array_push( $buttons, 'jsplusBootstrapToolsColAddAfter' );
	array_push( $buttons, 'jsplusBootstrapToolsColDelete' );
	array_push( $buttons, 'jsplusBootstrapToolsColMoveLeft' );
	array_push( $buttons, 'jsplusBootstrapToolsColMoveRight' );


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

	// http://js.plus/products/bootstrap-tools/bootstrap-include-css-js-plugin
	// Use our customized bootstrap css that fixes issues between bootstrap and Wordpress Admin menus.
	$init['jsplusInclude'] = '{
		url: "/wp-content/mu-plugins/includes/jsplus/css/bootstrap/",
		includeCssToGlobalDoc: false,
		includeJsToGlobalDoc: false,
		includeJQuery: false,
		includeTheme: true,
		includeIeFix: true,
		previewStyles: true,
		framework: "b4",
	}';
	$init['jsplusShowBlocks'] = '{
		"enabledByDefault":  true,
		"addPaddings": false
	}';

	
	 return $init;
}
