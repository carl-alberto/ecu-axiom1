<?php
/**
 * Envira Compression Container.
 *
 * @since 1.9.2
 *
 * @package Envira Gallery
 */

namespace Envira\Compress;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

use Envira\Compress\Settings as Settings;
use Envira\Compress\Image as Envira_Image;
/**
 * Compression Container Class
 *
 * @since 1.9.2
 */
final class Container {

	/**
	 * Class Constructor
	 *
	 * @since 1.9.2
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Compression Init Method
	 *
	 * @since 1.9.2
	 *
	 * @return void
	 */
	public function init() {

		if ( ! \envira_license_checker() ) {
			return;
		}

		if ( defined( 'ENVIRA_COMPRESSION_STANDALONE' ) ) {
			return;
		}

		if ( is_admin() ) {
			$settings = new Settings();
		}

		add_action( 'envira_gallery_insert_image_complete', [ &$this, 'handle_envira_compression' ], 12, 2 );
		add_filter( 'wp_generate_attachment_metadata', [ $this, 'handle_attachement_compression' ], 12, 2 );
		add_action( 'wp_ajax_envira_async_optimize_media', [ &$this, 'compress_on_upload' ] );

	}

	/**
	 * Helper Method to compress envira images only.
	 *
	 * @param array $metadata Attachment Metadata.
	 * @param int   $attachment_id Attachment Id.
	 * @return void
	 */
	public function handle_envira_compression( $metadata, $attachment_id ) {

		if ( 'envira' === \envira_get_setting( 'compression_setting', 'disabled' ) ) {
			$this->async_upload( $metadata, $attachment_id );
		}

	}

	/**
	 * Undocumented function
	 *
	 * @param array $metadata Metadata array.
	 * @param int   $attachment_id Attachment ID.
	 * @return array
	 */
	public function handle_attachement_compression( $metadata, $attachment_id ) {

		if ( 'all_media' === \envira_get_setting( 'compression_setting', 'disabled' ) ) {
			$this->async_upload( $metadata, $attachment_id );
		}

		return $metadata;
	}

	/**
	 * Async Compress Images.
	 *
	 * @return void
	 */
	public function compress_on_upload() {

		check_admin_referer( 'envira_compress_media', 'nonce' );

		if ( \current_user_can( 'upload_files' ) ) {

			$attachment_id = isset( $_POST['attachment_id'] ) ? intval( wp_unslash( $_POST['attachment_id'] ) ) : '';
			$metadata      = isset( $_POST['attachment_id'] ) ? wp_unslash( $_POST['metadata'] ) : array(); // @codingStandardsIgnoreLine

			if ( $metadata ) {
				$image    = new Envira_Image( $attachment_id, $metadata );
				$compress = $image->compress();
			}
		}

	}

	/**
	 * Undocumented function
	 *
	 * @param array $metadata Attachment Metadata.
	 * @param int   $attachment_id Attachment Id.
	 * @return void
	 */
	public function async_upload( $metadata, $attachment_id ) {

		if ( ! is_numeric( $attachment_id ) ) {
			return;
		}

		if ( ! is_array( $metadata ) ) {
			return;
		}

		$body = [
			'action'        => 'envira_async_optimize_media',
			'nonce'         => \wp_create_nonce( 'envira_compress_media' ),
			'metadata'      => $metadata,
			'attachment_id' => $attachment_id,
		];

		$args = [
			'timeout'   => 0.1,
			'blocking'  => false,
			'body'      => $body,
			'cookies'   => isset( $_COOKIE ) && is_array( $_COOKIE ) ? $_COOKIE : [],
			'sslverify' => false,
		];

		\wp_remote_post( admin_url( 'admin-ajax.php' ), $args );

	}
}
