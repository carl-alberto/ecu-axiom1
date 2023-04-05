<?php
/**
 * Our MU Plugin
 *
 * @package     MUPlugin
 * @author      ITCSWebDev
 * @copyright   East Carolina University
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Our MU Plugin
 * Plugin URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Description: Provides various features and settings for all of ECU CMS
 * Version:     1.0.0
 * Author:      ITCSWebDev
 * Author URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Text Domain: our-mu-plugin
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */


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

		// These are public classes that could be used by themes or plugins.

		// ECU Database Library
		'Database\Database' 	=> __DIR__ . '/includes/database/abstract-database.php',
		'Database\Tools' 		=> __DIR__ . '/includes/database/class-tools.php',
		'Database\Homepage' 	=> __DIR__ . '/includes/database/class-homepage.php',
		'Database\Directory' 	=> __DIR__ . '/includes/database/class-directory.php',

		// ECU LDAP Library
		'Ldap\Ad' 			=> __DIR__ . '/includes/ldap/class-ad.php',
		'Ldap\Ad_User' 		=> __DIR__ . '/includes/ldap/class-ad-user.php',
		'Ldap\Ad_Group' 	=> __DIR__ . '/includes/ldap/class-ad-group.php',
		'Ldap\Ad_Search'	=> __DIR__ . '/includes/ldap/class-ad-search.php'
		
	];

	if(array_key_exists($class_name, $class_map)) {
	    require_once $class_map[$class_name];
	}
});

// Singular changes to admin WP functionality via actions/filters
// Be sure to use the approriate admin hook and not just the init hook. 
// If the hook fires on the frontend then please use public.php.
include( __DIR__ . '/includes/admin.php' );

// Singular changes to public WP functionality via actions/filters
include( __DIR__ . '/includes/public.php' );

// Singular changes to plugin functionality via actions/filters
include( __DIR__ . '/includes/plugins.php' );

// Funcionality that requires more then an action/filter
// Group them into folders that describe what they do
include( __DIR__ . '/includes/jsplus/jsplus.php' );
include( __DIR__ . '/includes/recently-updated/recently-updated.php' );
include( __DIR__ . '/includes/disable-login/disable-login.php' );
include( __DIR__ . '/includes/ldap-login/ldap-login.php' );
include( __DIR__ . '/includes/intranet/intranet.php' );
include( __DIR__ . '/includes/duplicate-posts/duplicate-posts.php' );
include( __DIR__ . '/includes/site-management/site-management.php' );