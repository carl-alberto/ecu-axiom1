<?php
/**
 * Metabox class.
 *
 * @since 1.0.0
 *
 * @package Envira_ZIP_Importer
 * @author  David Bisset
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Metabox class.
 *
 * @since 1.0.0
 *
 * @package Envira_ZIP_Importer
 * @author  Envira Team
 */
class Envira_ZIP_Importer_Metaboxes {

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

		// Base.
		$this->base = Envira_ZIP_Importer::get_instance();

		// Envira Gallery.
		add_filter( 'envira_gallery_supported_file_types', array( $this, 'supported_file_types' ) );

	}

	/**
	 * Extends the default list of supported upload file types to include ZIP files
	 *
	 * @since 1.0.0
	 *
	 * @param array $supported_file_types Supported File Types.
	 * @return array Supported File Types
	 */
	public function supported_file_types( $supported_file_types ) {

		// Grab array of supported file types from Common class.
		$supported_file_types_arr = Envira_ZIP_Importer_Common::get_instance()->get_supported_filetypes();

		// Convert to a string.
		$supported_file_types_str = implode( ',', $supported_file_types_arr );

		// Add to supported array.
		$supported_file_types[] = array(
			'title'      => __( 'ZIP Files', 'envira-zip-importer' ),
			'extensions' => $supported_file_types_str . ',' . strtoupper( $supported_file_types_str ),
		);

		return $supported_file_types;

	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @return object The Envira_ZIP_Importer_Metaboxes object.
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_ZIP_Importer_Metaboxes ) ) {
			self::$instance = new Envira_ZIP_Importer_Metaboxes();
		}

		return self::$instance;

	}

}

// Load the metabox class.
$envira_zip_importer_metaboxes = Envira_ZIP_Importer_Metaboxes::get_instance();
