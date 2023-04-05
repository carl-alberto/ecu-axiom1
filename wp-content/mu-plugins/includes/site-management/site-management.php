<?php
/**
 * WP Site Management
 *
 * @package     WPSiteManagement
 * @author      ATWebDev
 * @copyright   2019 East Carolina University
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: WP Site Management
 * Plugin URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Description: Provides various administrative features for use by the admins of the ECU CMS
 * Version:     1.0.0
 * Author:      ATWebDev
 * Author URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Text Domain: wp-site-management
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace Site;
use \WP_Error as WP_Error;

defined( 'ABSPATH' ) OR exit;

if ( is_admin() ) {
	include( __DIR__ . '/includes/admin.php');
}