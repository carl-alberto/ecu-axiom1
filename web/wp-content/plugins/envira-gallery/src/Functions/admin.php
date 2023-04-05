<?php
/**
 * Envira Admin Functions.
 *
 * @since 1.7.0
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

/**
 * Load Admin Template Partials
 *
 * @since 1.7.0
 *
 * @access public
 * @param mixed $template Template to load.
 * @param array $data (default: array()) Data to pass to template.
 * @return bool did the template load.
 */
function envira_load_admin_partial( $template, $data = [] ) {

	$dir = trailingslashit( plugin_dir_path( ENVIRA_FILE ) . 'src/Views/partials' );

	if ( file_exists( $dir . $template . '.php' ) ) {

		require_once $dir . $template . '.php';
		return true;
	}

	return false;

}

/**
 * Helper Method to check if is envira page
 *
 * @since 1.9.0
 *
 * @return boolean
 */
function is_envira_page() {

	if ( ! is_admin() ) {
		return false;
	}
	if ( ! function_exists( 'get_current_screen' ) ) {
		return false;
	}

	$current_screen = get_current_screen();

	if ( ! isset( $current_screen->post_type ) || 'envira' !== $current_screen->post_type ) {
		return false;
	}

	return true;

}

/**
 * Get user license Key
 *
 * @since 1.7.0
 *
 * @access public
 * @return string|bool
 */
function envira_get_license_key() {

	$option = get_option( 'envira_gallery' );
	$key    = false;

	if ( empty( $option['key'] ) ) {

		if ( defined( 'ENVIRA_LICENSE_KEY' ) ) {

			$key = ENVIRA_LICENSE_KEY;

		}
	} else {

		$key = $option['key'];

	}

	return apply_filters( 'envira_gallery_license_key', $key );

}

/**
 * Returns the license key type for Envira.
 *
 * @since 1.7.0
 *
 * @return string $type The user's license key type for Envira.
 */
function envira_get_license_key_type() {

	$option = get_option( 'envira_gallery' );
	return $option['type'];

}

/**
 * Returns possible license key error flag.
 *
 * @since 1.7.0
 *
 * @return bool True if there are license key errors, false otherwise.
 */
function envira_get_license_key_errors() {

	$option = get_option( 'envira_gallery' );
	return isset( $option['is_expired'] ) && $option['is_expired'] || isset( $option['is_disabled'] ) && $option['is_disabled'] || isset( $option['is_invalid'] ) && $option['is_invalid'];

}

/**
 * Called whenever an upgrade button / link is displayed in Lite, this function will
 * check if there's a shareasale ID specified.
 *
 * There are three ways to specify an ID, ordered by highest to lowest priority
 * - add_filter( 'envira_gallery_shareasale_id', function() { return 1234; } );
 * - define( 'ENVIRA_GALLERY_SHAREASALE_ID', 1234 );
 * - get_option( 'envira_gallery_shareasale_id' ); (with the option being in the wp_options table)
 *
 * If an ID is present, returns the ShareASale link with the affiliate ID, and tells
 * ShareASale to then redirect to enviragallery.com/lite
 *
 * If no ID is present, just returns the enviragallery.com/lite URL with UTM tracking.
 *
 * @since 1.5.0
 *
 * @param string $url Url.
 * @param string $medium Tracking Medium.
 * @param string $button Tracking Location.
 * @param bool   $append append.
 */
function envira_get_upgrade_link( $url = false, $medium = 'default', $button = 'default', $append = false ) {

	// Check if there's a constant.
	$shareasale_id = '';

	if ( defined( 'ENVIRA_GALLERY_SHAREASALE_ID' ) ) {
		$shareasale_id = ENVIRA_GALLERY_SHAREASALE_ID;
	}

	// If there's no constant, check if there's an option.
	if ( empty( $shareasale_id ) ) {
		$shareasale_id = get_option( 'envira_gallery_shareasale_id', '' );
	}

	// Whether we have an ID or not, filter the ID.
	$shareasale_id = apply_filters( 'envira_gallery_shareasale_id', $shareasale_id );

	$source = class_exists( 'X_Bootstrap' ) ? 'themeco' : 'proplugin';
	$source = apply_filters( 'envira_tracking_src', $source );

	if ( defined( 'ENVIRA_GALLERY_TRACKING_SRC' ) ) {
		$source = ENVIRA_GALLERY_TRACKING_SRC;
	}

	// If at this point we still don't have an ID, we really don't have one!
	// Just return the standard upgrade URL.
	if ( empty( $shareasale_id ) ) {
		if ( false === filter_var( $url, FILTER_VALIDATE_URL ) ) {
			// prevent a possible typo.
			$url = false;
		}
		$url = ( false !== $url ) ? trailingslashit( esc_url( $url ) ) : 'https://enviragallery.com/pricing/';
		return $url . '?utm_source=' . $source . '&utm_medium=' . $medium . '&utm_campaign=' . $button . $append;
	}

	$clean_url = str_replace( wp_parse_url( $url, PHP_URL_SCHEME ) . '://', '', $url );

	// If here, we have a ShareASale ID
	// Return ShareASale URL with redirect.
	return 'http://www.shareasale.com/r.cfm?u=' . $shareasale_id . '&b=566240&m=51693&afftrack=&urllink=' . rawurlencode( $clean_url );

}

/**
 * Flag to determine if the GD library has been compiled.
 *
 * @since 1.7.0
 *
 * @return bool True if has proper extension, false otherwise.
 */
function envira_has_gd_extension() {

	return extension_loaded( 'gd' ) && function_exists( 'gd_info' );

}

/**
 * Flag to determine if the Imagick library has been compiled.
 *
 * @since 1.7.0
 *
 * @return bool True if has proper extension, false otherwise.
 */
function envira_has_imagick_extension() {

	return extension_loaded( 'imagick' );

}

/**
 * Returns an Array of Registered Publishers.
 *
 * @since 1.7.0
 *
 * @access public
 * @return array
 */
function envira_get_publishers() {

	$publishers = [];

	return apply_filters( 'envira_publishers', $publishers );

}

/**
 * Returns an array of registered Importers.
 *
 * @since 1.7.0
 *
 * @access public
 * @return array
 */
function envira_get_importers() {

	$importers = [];

	return apply_filters( 'envira_importers', $importers );

}

/**
 * Returns the post types to skip for loading Envira metaboxes.
 *
 * @since 1.7.0
 *
 * @return array Array of skipped posttypes.
 */
function envira_get_skipped_posttypes() {

	$skipped_posttypes = [ 'attachment', 'revision', 'nav_menu_item', 'soliloquy', 'soliloquyv2', 'envira_album' ];
	return apply_filters( 'envira_gallery_skipped_posttypes', $skipped_posttypes );

}

/**
 * Helper Method to check license status
 *
 * @return bool
 */
function envira_license_checker() {

	$key    = ( function_exists( 'envira_get_license_key' ) ) ? envira_get_license_key() : false;
	$option = get_option( 'envira_gallery' );

	if ( ! $key || ( isset( $option['is_expired'] ) && $option['is_expired'] ) ) {
		return false;
	}

	return true;

}
