<?php
/**
 * Compression Api
 *
 * @since 1.9.2
 *
 * @package Envira Gallery
 */

namespace Envira\Compress;

use Envira\Utils\Exception as Envira_Exception;
/**
 * Api to handle image compression
 *
 * @since 1.9.2
 */
class Compressor {

	/**
	 * API URL
	 *
	 * @since 1.9.2
	 *
	 * @var string
	 */
	private $api_url = 'https://compress.enviragallery.com/shrink';

	/**
	 * Class Constructor
	 *
	 * @since 1.9.2
	 */
	public function __construct() {
		$this->license = envira_get_license_key();

	}

	/**
	 * Helper Method for compression
	 *
	 * @since 1.9.2
	 *
	 * @return bool
	 */
	public function can_compress() {

		if ( ! isset( $this->license ) || '' === $this->license ) {
			return true;
		}

		return true;
	}

	/**
	 * Helper Method to send image to buffer
	 *
	 * @since 1.9.2
	 *
	 * @param string $file file.
	 * @param string $filetype Filetype ext.
	 * @return string
	 */
	public function to_buffer( $file, $filetype ) {

		$image = file_get_contents( $file );
		$data  = '';

		switch ( $filetype ) {
			case 'jpg':
			case 'jpeg':
				$data = 'data:image/jpg;base64,' . base64_encode( $image );
				break;
			case 'png':
				$data = 'data:image/png;base64,' . base64_encode( $image );
				break;
			case 'webp':
				$data = 'data:image/webp;base64,' . base64_encode( $image );
				break;
		}

		return $data;

	}

	/**
	 * Image Compresor
	 *
	 * @param string $file File.
	 * @param array  $opts Compression options.
	 * @return array
	 * @throws Envira_Exception Envira Exception.
	 */
	public function compress_image( $file, $opts = [] ) {

		if ( ! $this->can_compress() ) {
			throw new Envira_Exception( __( 'License is Invalid.', 'envira-gallery' ), 'LicenseError' );
		}
		if ( ! file_exists( $file ) ) {
			throw new Envira_Exception( __( 'File does not exist.', 'envira-gallery' ), 'FileError' );
		}

		if ( ! is_writable( $file ) ) {
			throw new Envira_Exception( __( 'File is not writable.', 'envira-gallery' ), 'WriteError' );

		}

		$filetype = wp_check_filetype( $file );
		$filesize = filesize( $file );

		try {
			$data = [
				'data'    => $this->to_buffer( $file, $filetype['ext'] ),
				'options' => $opts,
			];

			$response = $this->remote_request( $data );

			if ( ! $response || ! is_array( $response ) ) {
				throw new Envira_Exception( __( 'Invalid API Response.', 'envira-gallery' ), 'ApiError' );
			}
		} catch ( Envira_Exception $err ) {
			envira_log_error( 'Envira Compression API Error:', $err );
			return false;

		}

		if ( '' === $response['image'] ) {
			throw new Envira_Exception( __( 'Invalid Image Response.', 'envira-gallery' ), 'InvalidImage' );
		}

		$base_64_image = trim( $response['image'] );
		$base_64_image = str_replace( 'data:image/png;base64,', '', $base_64_image );
		$base_64_image = str_replace( 'data:image/jpg;base64,', '', $base_64_image );
		$base_64_image = str_replace( 'data:image/jpeg;base64,', '', $base_64_image );
		$base_64_image = str_replace( 'data:image/webp;base64,', '', $base_64_image );
		$base_64_image = str_replace( ' ', '+', $base_64_image );

		// Image Data.
		$image_data = base64_decode( $base_64_image );
		$filesize   = file_put_contents( $file, $image_data );

		return $response['image'];

	}

	/**
	 * Helper Method for remote request.
	 *
	 * @param array $body Body request.
	 * @param array $headers Headers Request.
	 * @throws Envira_Exception API Error Exception.
	 * @return mixed
	 */
	public function remote_request( $body = [], $headers = [] ) {

		$body = $body;
		$url  = $this->api_url;
		// Build the headers of the request.
		$headers = wp_parse_args(
			$headers,
			[
				'Content-Type' => 'application/json',
			]
		);

		$post = [
			'headers'     => $headers,
			'body'        => $body,
			'method'      => 'POST',
			'data_format' => 'body',
			'timeout'     => 10000,
			'headers'     => [],
			'cookies'     => [],
		];

		// Perform the query and retrieve the response.
		$response      = wp_remote_post( $url, $post );
		$response_code = wp_remote_retrieve_response_code( $response ); /* log this for API issues */
		$response_body = wp_remote_retrieve_body( $response );

		// Bail out early if there are any errors.
		if ( 200 !== $response_code ) {
			throw new Envira_Exception( __( 'Api Unreachable.', 'envira-gallery' ), 'ApiError' );
		}

		// Return the json decoded content.
		return json_decode( $response_body, true );
	}

}
