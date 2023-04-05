<?php
// @codingStandardsIgnoreFile
// Legacy
// !!! TODO deprecate
use Envira\Utils\Cropping;

class Envira_Gallery_Common {

	public static $_instance = null;
	public function __construct() {

	}

	/**
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @param mixed $post_id
	 * @return void
	 */
	public function get_config_defaults( $post_id ) {
		return envira_get_config_defaults( $post_id );
	}

	/**
	 * get_config_default function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @param mixed $key
	 * @return void
	 */
	public function get_config_default( $key ) {
		return envira_get_config_default( $key );
	}

	/**
	 * standalone_get_slug function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @param mixed $type
	 * @return void
	 */
	public function standalone_get_slug( $type ) {
		return envira_standalone_get_the_slug( $type );
	}

	/**
	 * get_transient_expiration_time function.
	 *
	 * @access public
	 * @param string $plugin (default: 'envira-gallery')
	 * @return void
	 */
	public function get_transient_expiration_time( $plugin = 'envira-gallery' ) {
		return envira_get_transient_expiration_time( $plugin );
	}

	/**
	 * get_columns function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @return void
	 */
	public function get_columns() {
		return envira_get_columns();
	}

	/**
	 * get_justified_last_row function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @return void
	 */
	public function get_justified_last_row() {
		return envira_get_justified_last_row();
	}

	/**
	 * get_justified_gallery_themes function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @return void
	 */
	public function get_justified_gallery_themes() {
		return envira_get_justified_gallery_themes();
	}

	/**
	 * get_gallery_themes function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @return void
	 */
	public function get_gallery_themes() {
		return envira_get_gallery_themes();
	}

	/**
	 * get_lightbox_themes function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @return void
	 */
	public function get_lightbox_themes() {
		return envira_get_lightbox_themes();
	}

	/**
	 * get_title_displays function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @return void
	 */
	public function get_title_displays() {
		return envira_get_title_displays();
	}

	/**
	 * get_arrows_positions function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @return void
	 */
	public function get_arrows_positions() {
		return envira_get_arrows_positions();
	}

	/**
	 * get_toolbar_positions function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @return void
	 */
	public function get_toolbar_positions() {
		return envira_get_toolbar_positions();
	}

	/**
	 * get_transition_effects function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @return void
	 */
	public function get_transition_effects() {
		return envira_get_transition_effects();
	}

	/**
	 * get_easing_transition_effects function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @return void
	 */
	public function get_easing_transition_effects() {
		return envira_get_easing_transition_effects();
	}

	/**
	 * get_thumbnail_positions function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @return void
	 */
	public function get_thumbnail_positions() {
		return envira_get_thumbnail_positions();
	}

	/**
	 * flush_gallery_caches function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @param mixed  $post_id
	 * @param string $slug (default: '')
	 * @return void
	 */
	public function flush_gallery_caches( $post_id, $slug = '' ) {
		return envira_flush_gallery_caches( $post_id, $slug );
	}

	/**
	 * get_supported_filetypes function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @return void
	 */
	public function get_supported_filetypes() {
		return envira_get_supported_filetypes();
	}

	/**
	 * get_transition_effects_values function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @return void
	 */
	public function get_transition_effects_values() {
		return envira_get_transition_effects_values();
	}

	/**
	 * API method for cropping images.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @global object $wpdb The $wpdb database object.
	 *
	 * @param string $url      The URL of the image to resize.
	 * @param int    $width       The width for cropping the image.
	 * @param int    $height      The height for cropping the image.
	 * @param bool   $crop       Whether or not to crop the image (default yes).
	 * @param string $align    The crop position alignment.
	 * @param bool   $retina     Whether or not to make a retina copy of image.
	 * @param array  $data      Array of gallery data (optional).
	 * @param bool   $force_overwrite      Forces an overwrite even if the thumbnail already exists (useful for applying watermarks)
	 * @return WP_Error|string Return WP_Error on error, URL of resized image on success.
	 */
	public function resize_image( $url, $width = null, $height = null, $crop = true, $align = 'c', $quality = 100, $retina = false, $data = array(), $force_overwrite = false ) {

		return ( new Cropping())->resize_image(
		$url,
		$width,
		$height,
		$crop,
		$align,
		$quality,
		$retina,
		$data,
		$force_overwrite
		);
	}

	/**
	 * Helper method to return common information about an image.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args      List of resizing args to expand for gathering info.
	 * @return WP_Error|string Return WP_Error on error, array of data on success.
	 */
	public function get_image_info( $args ) {
		return envira_get_image_info( $args );
	}

	/**
	 * Helper method for retrieving image sizes.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @global array $_wp_additional_image_sizes Array of registered image sizes.
	 *
	 * @param   bool $wordpress_only     WordPress Only (excludes the default and envira_gallery_random options)
	 * @return  array                       Array of image size data.
	 */
	public function get_image_sizes( $wordpress_only = false ) {

		if ( ! $wordpress_only ) {
			$sizes = array(
				array(
					'value' => 'default',
					'name'  => __( 'Default', 'envira-gallery' ),
				),
			);
		}

		global $_wp_additional_image_sizes;
		$wp_sizes = get_intermediate_image_sizes();
		foreach ( (array) $wp_sizes as $size ) {
			if ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
				$width  = absint( $_wp_additional_image_sizes[ $size ]['width'] );
				$height = absint( $_wp_additional_image_sizes[ $size ]['height'] );
			} else {
				$width  = absint( get_option( $size . '_size_w' ) );
				$height = absint( get_option( $size . '_size_h' ) );
			}

			if ( ! $width && ! $height ) {
				$sizes[] = array(
					'value' => $size,
					'name'  => ucwords( str_replace( array( '-', '_' ), ' ', $size ) ),
				);
			} else {
				$sizes[] = array(
					'value'  => $size,
					'name'   => ucwords( str_replace( array( '-', '_' ), ' ', $size ) ) . ' (' . $width . ' &#215; ' . $height . ')',
					'width'  => $width,
					'height' => $height,
				);
			}
		}
		// Add Option for full image
		$sizes[] = array(
			'value' => 'full',
			'name'  => __( 'Original Image', 'envira-gallery' ),
		);

		// Add Random option
		if ( ! $wordpress_only ) {
			$sizes[] = array(
				'value' => 'envira_gallery_random',
				'name'  => __( 'Random', 'envira-gallery' ),
			);
		}

		return apply_filters( 'envira_gallery_image_sizes', $sizes );

	}

	/**
	 * get_instance function.
	 *
	 * __Depricated since 1.7.0.
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	public static function get_instance() {

		if ( ! isset( self::$_instance ) && ! ( self::$_instance instanceof Envira_Gallery_Common ) ) {

			self::$_instance = new self();
		}

		return self::$_instance;

	}

}
