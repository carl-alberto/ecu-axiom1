<?php
/**
 * Envira Image Object for Compression
 *
 * @since 1.9.2
 *
 * @package Envira Gallery
 */

namespace Envira\Compress;

use Envira\Compress\Compressor as Compressor;
use Envira\Compress\Image_Size as Envira_Size;
use Envira\Utils\Exception as Envira_Exception;

/**
 * Image Object
 *
 * @since 1.9.2
 */
class Image {
	const ORIGINAL = 'full';
	/**
	 * Attachment Metadata
	 *
	 * @var array
	 */
	private $metadata;

	/**
	 * WordPress Metadata
	 *
	 * @var array
	 */
	public $wp_meta;
	/**
	 * Attachment Sizes
	 *
	 * @since 1.9.2
	 *
	 * @var array
	 */
	private $sizes = [];
	/**
	 * Class Constructor
	 *
	 * @since 1.9.2
	 *
	 * @param int   $id Attachment ID.
	 * @param array $wp_metadata WordPress Metadata.
	 */
	public function __construct( $id, $wp_metadata = null ) {
		$this->id         = $id;
		$this->attachment = \get_post( $id );
		$this->wp_meta    = $wp_metadata;
		$this->metadata   = $this->get_wp_metadata();
	}

	/**
	 * Helper Method to get Attachment ID;
	 *
	 * @since 1.9.2
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}


	/**
	 * Helper Method to get Attachment Name
	 *
	 * @since 1.9.2
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Set Attachment Metadata
	 *
	 * @since 1.9.2
	 *
	 * @return void
	 */
	public function get_wp_metadata() {

		if ( ! is_array( $this->metadata ) ) {
			$this->metadata = wp_get_attachment_metadata( $this->id );
		}

		if ( ! is_array( $this->metadata ) ) {
			return;
		}

		if ( ! isset( $this->metadata['file'] ) ) {
			return;
		}

		if ( ! $this->file_type_allowed() ) {
			return;
		}

		$envira_sizes = envira_get_setting( 'compression_sizes', [] );

		$upload_dir  = wp_upload_dir();
		$path_prefix = $upload_dir['basedir'] . '/';
		$pathinfo    = pathinfo( $this->metadata['file'] );

		if ( isset( $pathinfo['dirname'] ) ) {
			$path_prefix .= $pathinfo['dirname'] . '/';
		}

		$path_parts = explode( '/', $this->metadata['file'] );
		$this->name = end( $path_parts );
		$filename   = $path_prefix . $this->name;
		$is_scaled  = strpos( $this->name, '-scaled' );

		if ( array_key_exists( 'full', $envira_sizes ) ) {
			if ( false !== $is_scaled ) {

				$this->sizes['original_scaled'] = new Envira_Size( $filename );
				$this->sizes[ self::ORIGINAL ]  = new Envira_Size( str_replace( '-scaled', '', $filename ) );

			} else {
				$this->sizes[ self::ORIGINAL ] = new Envira_Size( $filename );
			}
		}

		if ( isset( $this->metadata['sizes'] ) && is_array( $this->metadata['sizes'] ) ) {
			foreach ( $this->metadata['sizes'] as $size_name => $info ) {

				if ( ! array_key_exists( $size_name, $envira_sizes ) ) {
					continue;
				}

				$this->sizes[ $size_name ] = new Envira_Size( $path_prefix . $info['file'] );
			}
		}
	}

	/**
	 * Helper Method to return Metadata
	 *
	 * @since 1.9.2
	 *
	 * @return array
	 */
	public function get_metadata() {
		return $this->metadata;
	}

	/**
	 * Helper Method to check if File type is allowed.
	 *
	 * @since 1.9.2
	 *
	 * @return boolean
	 */
	public function file_type_allowed() {
		return in_array( $this->get_mime_type(), [ 'image/jpeg', 'image/png', 'image/webp' ], true );
	}

	/**
	 * Helper Method to get Mime Type.
	 *
	 * @since 1.9.2
	 *
	 * @return string
	 */
	public function get_mime_type() {
		return get_post_mime_type( $this->id );
	}

	/**
	 * Helper Method to Compress All Image sizes.
	 *
	 * @since 1.9.2
	 *
	 * @return array
	 */
	public function compress() {

		$compresor = new Compressor();
		$sucess    = 0;
		$failed    = 0;
		// Compress Options.
		$opts = [
			'license_key'       => envira_get_license_key(),
			'jpeg_quality'      => apply_filters( 'envira_compress_jpeg_quality', 90, 'envira_compress' ),
			'preserve_metadata' => boolval( envira_get_setting( 'compression_preserve_metadata ' ) ),
			'file_type'         => $this->get_mime_type(),
			'compression_level' => envira_get_setting( 'compression_level', 'lossless' ),
		];

		foreach ( $this->sizes as $size_name => $info ) {
			try {
				$response = $compresor->compress_image( $info->filename, $opts );
				// !!! TODO ADD META
				$sucess++;
			} catch ( Envira_Exception $e ) {
				$failed++;
				continue;
			}
		}

		return [
			'sucess' => $sucess,
			'failed' => $failed,
		];

	}

}
