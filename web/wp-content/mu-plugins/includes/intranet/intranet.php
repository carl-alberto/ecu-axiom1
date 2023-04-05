<?php

/**
 * Intranet
 *
 * @package     Intranet
 * @author      ATWebDev
 * @copyright   2019 East Carolina University
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Disable-Login
 * Plugin URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Description: Allows a site to be turned into an intranet that requires login to view.
 * Version:     1.0.0
 * Author:      ATWebDev
 * Author URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Text Domain: intranet
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace Intranet;

/**
 * WordPress will automatically include all files in the MU plugin directory in alphabetcial order.
 *
 * Use the file to control the loading of the plugins.
 */
defined( 'ABSPATH' ) OR exit;

// Autoloader for the plugin
spl_autoload_register(function ($class_name) {

	/**
	 * Because of past negative experience did not want to rely on a convention of file/class names
	 * to be able to load a file.   Also didn't want to do parsing of the class title to determine file
	 * path.   So I settled on a array lookup with file path specified.
	 */
	$class_map = [

		// ECU Intranet
		'Intranet\Intranet' 	=> __DIR__ . '/includes/class-intranet.php',
		'Intranet\Settings' 	=> __DIR__ . '/includes/trait-intranet-settings.php',
		'Intranet\Site_Form' 	=> __DIR__ . '/includes/class-intranet-site-form.php',

	];

	if(array_key_exists($class_name, $class_map)) {
	    require_once $class_map[$class_name];
	}
});

if(is_admin()) {
	include( __DIR__ . '/includes/admin.php');
}

$intranet = new Intranet();

if($intranet->is_enabled()) {
	// Display login message
	add_filter('login_message', array($intranet, 'login_message'));

	// Force users to login if appropriate.
	add_action('template_redirect', array($intranet, 'force_login'));

	// Discourage crawlers
	add_filter( 'robots_txt', array($intranet, 'robots'), 99, 2);

	// Add authentication
	add_filter( 'authenticate', array($intranet, 'authenticate'), 10, 3);
}