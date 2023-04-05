<?php
/**
 * Envira Utility Functions.
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

use Envira\Frontend\Background;
use Envira\Utils\Mobile_Detect;

if ( ! function_exists( 'array_column' ) ) :
	/**
	 * Fallback for array_column for < php 5.5
	 *
	 * @param array  $input Input Array.
	 * @param string $column_key Column Key.
	 * @param mixed  $index_key Key index.
	 * @return bool|mixed
	 */
	function array_column( array $input, $column_key, $index_key = null ) {

		$array = [];

		foreach ( $input as $value ) {

			if ( ! array_key_exists( $column_key, $value ) ) {

				return false;

			}

			if ( is_null( $index_key ) ) {

				$array[] = $value[ $column_key ];

			} else {

				if ( ! array_key_exists( $index_key, $value ) || ! is_scalar( $value[ $index_key ] ) ) {

					return false;

				}

				$array[ $value[ $index_key ] ] = $value[ $column_key ];

			}
		}

		return $array;

	}

endif;

/**
 * Helper Method for Size Conversions
 *
 * @author Chris Christoff
 * @since 1.7.0
 *
 * @param  unknown $v Ketter.
 * @return int|string
 */
function envira_let_to_num( $v ) {

	$l   = substr( $v, -1 );
	$ret = substr( $v, 0, -1 );

	switch ( strtoupper( $l ) ) {

		case 'P': // fall-through.
		case 'T': // fall-through.
		case 'G': // fall-through.
		case 'M': // fall-through.
		case 'K':
			$ret *= 1024;
			break;

		default:
			break;

	}

	return $ret;
}

/**
 * Helper function to detect mobile.
 *
 * @since 1.7.0
 *
 * @access public
 * @return object
 */
function envira_mobile_detect() {
	return new Envira\Utils\Mobile_Detect();
}

/**
 * Helper Method to check if whitelable is enabled.
 *
 * @since 1.7.0
 *
 * @return bool
 */
function envira_is_whitelabel() {

	return apply_filters( 'envira_whitelabel', false );
}

/**
 * Helper Method to check if is mobile.
 *
 * @return bool
 */
function envira_is_mobile() {
	_deprecated_function( 'envira_is_mobile', '1.9.4.8', 'envira_mobile_detect()->isMobile()' );

	return envira_mobile_detect()->isMobile();
}

/**
 * Utility function for debugging
 *
 * @since 1.7.0
 *
 * @param mixed $data (default: array().
 * @param bool  $export Use var_export.
 *
 * @return string
 */
function envira_pretty_print( $data = [], $export = false ) {
	// phpcs:ignore WordPress.PHP.DevelopmentFunctions
	$parsed_data = $export ? var_export( $data, true ) : print_r( $data, true );

	error_log( PHP_EOL . $parsed_data . PHP_EOL );

	return "<pre>$parsed_data</pre>";
}

/**
 * Helper Method to call background requests
 *
 * @since 1.7.0
 *
 * @access public
 * @param mixed  $data Data to request.
 * @param string $type Type of request.
 * @return bool
 */
function envira_background_request( $data, $type ) {

	if ( ! is_array( $data ) || ! isset( $type ) ) {

		return false;

	}

	$background = new Envira\Frontend\Background();
	$background->background_request( $data, $type );

	return true;
}

/**
 * Utility Function to log errors.
 *
 * @since 1.8.0
 *
 * @access public
 * @param string $content Content to load.
 * @param mixed  $data Data to log.
 * @return void
 */
function envira_log_error( $content = null, $data = null ) {

	if ( ! is_envira_debug_on() ) {

		return;

	} else {

		if ( ! is_array( $data ) ) {

			error_log( strtoupper( $content ) . ':' . PHP_EOL . $data ); // @codingStandardsIgnoreLine

		} else {

			error_log( strtoupper( $content ) . ':' . PHP_EOL . print_r( $data, true ) ); // @codingStandardsIgnoreLine

		}
	}

}

/**
 * Utility Function to return errors.
 *
 * @since 1.8.4
 *
 * @access public
 * @param string $wp_error_id Error ID.
 * @param string $text Error Text.
 * @return WP_Error
 */
function envira_wp_error( $wp_error_id = null, $text = null ) {

	global $wp_error;

	return ! isset( $wp_error ) ? new WP_Error( $wp_error_id, $text ) : $wp_error;

}


if ( ! function_exists( 'envira_curl_available' ) ) {
	/**
	 * Helper Method to check if curl is available.
	 *
	 * @since 1.9.2
	 *
	 * @return bool
	 */
	function envira_curl_available() {
		return extension_loaded( 'curl' );
	}
}

if ( ! function_exists( 'envira_fopen_available' ) ) {
	/**
	 * Helper Method to check if fopen is available.
	 *
	 * @since 1.9.2
	 *
	 * @return bool
	 */
	function envira_fopen_available() {
		return ini_get( 'allow_url_fopen' );
	}
}

/**
 * Check if envira debug is enabled.
 *
 * @since ??
 *
 * @return bool
 */
function is_envira_debug_on() {
	$constant = defined( 'ENVIRA_DEBUG' ) && 'true' === ENVIRA_DEBUG;
	$option   = get_option( 'envira_debug', false );

	return apply_filters( 'is_envira_debug_on', $constant || $option );
}

/**
 * Sanitized script location to include unminified version if debug is enabled.
 *
 * @since ??
 *
 * @param string $script Minified script location.
 * @param string $file Main plugin file.
 *
 * @return string
 */
function envira_script( $script, $file = ENVIRA_FILE ) {
	if ( is_envira_debug_on() ) {
		$script = str_replace( [ '/min/', '-min.js' ], [ '/dist/', '.js' ], $script );
	}

	return plugins_url( $script, $file );
}
