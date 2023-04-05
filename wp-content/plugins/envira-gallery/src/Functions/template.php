<?php
/**
 * Template Functions
 *
 * @package Envira Proofing
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper Method to convert Legecy Columns into Layouts.
 *
 * @since 1.9.0
 *
 * @param array $data Gallery data.
 * @param array $gallery_id Gallery ID.
 */
function envira_convert_columns_to_layouts( $data, $gallery_id ) {

	if ( ! is_array( $data ) || ! isset( $gallery_id ) ) {
		return $data;
	}

	if ( intval( $data['config']['columns'] ) === 0 ) {
		$data['config']['layout']  = 'automatic';
		$data['config']['columns'] = '0';

	} elseif ( isset( $data['config']['isotope'] ) && $data['config']['isotope'] ) {
		$data['config']['layout']  = 'mason';
		$data['config']['isotope'] = true;

	} elseif ( intval( $data['config']['columns'] ) > 0 ) {
		$data['config']['layout']  = 'grid';
		$data['config']['isotope'] = false;

	} elseif ( intval( $data['config']['columns'] ) === 1 ) {
		$data['config']['layout']  = 'blogroll';
		$data['config']['isotope'] = false;
		$data['config']['columns'] = '1';
	}

	return $data;

}

/**
 * Override layout settings
 *
 * @since 1.9.0
 *
 * @param array $data Gallery data.
 */
function envira_override_layout_settings( $data ) {

	if ( ! is_array( $data ) ) {
		return $data;
	}

	switch ( $data['config']['layout'] ) {
		case 'blogroll':
			$data['config']['columns'] = '1';
			break;
		case 'automatic':
			$data['config']['columns'] = '0';
			break;
		case 'mason':
			$data['config']['isotope'] = true;
			break;
		case 'grid':
		case 'square':
			$data['config']['isotope'] = false;
			break;
	}

	return $data;

}
