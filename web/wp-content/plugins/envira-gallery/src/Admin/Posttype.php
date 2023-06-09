<?php
/**
 * Posttype admin class.
 *
 * @since 1.7.0
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

namespace Envira\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Posttype admin class.
 *
 * @since 1.7.0
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */
class Posttype {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.7.0
	 */
	public function __construct() {

		// Update post type messages.
		add_filter( 'post_updated_messages', [ $this, 'messages' ] );

		// Force the menu icon to be scaled to proper size (for Retina displays).
		add_action( 'admin_head', [ $this, 'menu_icon' ] );

		// Add the Universal Header.
		add_action( 'in_admin_header', [ $this, 'admin_header' ], 100 );

	}

	/**
	 * Outputs the Envira Gallery Header.
	 *
	 * @since 1.5.0
	 */
	public function admin_header() {

		// Get the current screen, and check whether we're viewing the Envira or Envira Album Post Types.
		$screen = get_current_screen();
		if ( 'envira' !== $screen->post_type && 'envira_album' !== $screen->post_type ) {
			return;
		}
		if ( defined( 'ENVIRA_ADMIN_PREVIEW' ) ) {
			return;
		}
		// If here, we're on an Envira Gallery or Album screen, so output the header.
		envira_load_admin_partial(
			'header',
			[
				'logo' => plugins_url( 'assets/images/envira-logo-color.svg', ENVIRA_FILE ),
			]
		);

	}

	/**
	 * Contextualizes the post updated messages.
	 *
	 * @since 1.7.0
	 *
	 * @global object $post    The current post object.
	 * @param array $messages  Array of default post updated messages.
	 * @return array $messages Amended array of post updated messages.
	 */
	public function messages( $messages ) {

		global $post;

		// Contextualize the messages.
		$envira_messages    = [
			0  => '',
			1  => __( 'Envira gallery updated.', 'envira-gallery' ),
			2  => __( 'Envira gallery custom field updated.', 'envira-gallery' ),
			3  => __( 'Envira gallery custom field deleted.', 'envira-gallery' ),
			4  => __( 'Envira gallery updated.', 'envira-gallery' ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Envira gallery restored to revision from %s.', 'envira-gallery' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // @codingStandardsIgnoreLine
			6  => __( 'Envira gallery published.', 'envira-gallery' ),
			7  => __( 'Envira gallery saved.', 'envira-gallery' ),
			8  => __( 'Envira gallery submitted.', 'envira-gallery' ),
			/* translators: %s: date time */
			9  => sprintf( __( 'Envira gallery scheduled for: <strong>%1$s</strong>.', 'envira-gallery' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
			10 => __( 'Envira gallery draft updated.', 'envira-gallery' ),
		];
		$messages['envira'] = apply_filters( 'envira_gallery_messages', $envira_messages );

		return $messages;

	}

	/**
	 * Forces the Envira menu icon width/height for Retina devices.
	 *
	 * @since 1.7.0
	 */
	public function menu_icon() {

		?>
		<style type="text/css">#menu-posts-envira .wp-menu-image img { width: 16px; height: 16px; }</style>
		<?php

	}

}
