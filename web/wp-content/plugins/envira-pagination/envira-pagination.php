<?php
/**
 * Plugin Name: Envira Gallery - Pagination Addon
 * Plugin URI:  http://enviragallery.com
 * Description: Enables pagination capabilities for Envira galleries and albums.
 * Author:      Envira Gallery Team
 * Author URI:  http://enviragallery.com
 * Version:     1.7.12
 * Text Domain: envira-pagination
 * Domain Path: languages
 *
 * @package Envira Gallery
 * @subpackage Envira_Pagination.
 *
 * Envira Gallery is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Envira Gallery is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Envira Gallery. If not, see <http://www.gnu.org/licenses/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'envira_license_checker' ) && false === envira_license_checker() ) {
	return false;
}

use Envira\Utils\Updater as Updater;

/**
 * Main plugin class.
 *
 * @since 1.0.0
 *
 * @package Envira_Pagination
 * @author  Envira Gallery Team <support@enviragallery.com>
 */
class Envira_Pagination {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = '1.7.12';

	/**
	 * The name of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $plugin_name = 'Envira Pagination';

	/**
	 * Unique plugin slug identifier.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $plugin_slug = 'envira-pagination';

	/**
	 * Plugin file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Load the plugin textdomain.
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		// Load the plugin.
		add_action( 'envira_gallery_init', array( $this, 'init' ), 99 );

		// Load the updater.
		add_action( 'envira_gallery_updater', array( $this, 'updater' ), 10, 1 );

	}

	/**
	 * Loads the plugin textdomain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( $this->plugin_slug, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * Loads the plugin into WordPress.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Load admin only components.
		if ( is_admin() ) {
			$this->require_admin();
		}

		// Load global components.
		$this->require_global();

	}

	/**
	 * Loads all admin related files into scope.
	 *
	 * @since 1.0.0
	 */
	public function require_admin() {

		require plugin_dir_path( __FILE__ ) . 'includes/admin/metaboxes.php';

	}

	/**
	 * Initializes the addon updater.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key The user license key.
	 */
	public function updater( $key ) {

		$args = array(
			'plugin_name' => $this->plugin_name,
			'plugin_slug' => $this->plugin_slug,
			'plugin_path' => plugin_basename( __FILE__ ),
			'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . $this->plugin_slug,
			'remote_url'  => 'https://enviragallery.com/',
			'version'     => $this->version,
			'key'         => $key,
		);

		$updater = new Updater( $args );

	}

	/**
	 * Loads all global files into scope.
	 *
	 * @since 1.0.0
	 */
	public function require_global() {

		require plugin_dir_path( __FILE__ ) . 'includes/global/ajax.php';
		require plugin_dir_path( __FILE__ ) . 'includes/global/common.php';
		require plugin_dir_path( __FILE__ ) . 'includes/global/shortcode.php';

	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @return object The Envira_Albums object.
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Pagination ) ) {
			self::$instance = new Envira_Pagination();
		}

		return self::$instance;

	}

}

// Load the main plugin class.
$envira_pagination = Envira_Pagination::get_instance();
