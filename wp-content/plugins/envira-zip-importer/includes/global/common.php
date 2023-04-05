<?php
/**
 * Common class.
 *
 * @since 1.0.0
 *
 * @package Envira_Gallery
 * @subpackage Envira Zip Importer
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

/**
 * Common class.
 *
 * @since 1.0.0
 */
class Envira_ZIP_Importer_Common {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

	}

	/**
	 * Returns an array of supported ZIP archives.
	 *
	 * @since 1.0.0
	 */
	public function get_supported_filetypes() {

		$supported_filetypes = array(
			'zip',
		);

		return apply_filters( 'envira_zip_importer_supported_filetypes', $supported_filetypes );

	}

	/**
	 * Returns the tmp directory within the WordPress /uploads folder
	 * that this Addon can use to unzip ZIP files to
	 *
	 * @since 1.0.0
	 */
	public function get_tmp_dir() {

		$destination = wp_upload_dir();
		$tmp_dir     = $destination['basedir'] . '/envira-tmp';

		return apply_filters( 'envira_zip_importer_tmp_dir', $tmp_dir, $destination );

	}

	/**
	 * Returns the tmp directory within the WordPress /uploads folder
	 * that this Addon can use to unzip ZIP files to
	 *
	 * @since 1.0.0
	 */
	public function get_tmp_url() {

		$temp_url = content_url( 'uploads/envira-tmp' );

		$destination = false;

		return apply_filters( 'envira_zip_importer_tmp_url', $temp_url, $destination );

	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @return object The Envira_ZIP_Importer_Common object.
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_ZIP_Importer_Common ) ) {
			self::$instance = new Envira_ZIP_Importer_Common();
		}

		return self::$instance;

	}

}

// Load the Common class.
$envira_zip_importer_common = Envira_ZIP_Importer_Common::get_instance();
