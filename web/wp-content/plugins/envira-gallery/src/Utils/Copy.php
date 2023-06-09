<?php
/**
 * Copy class.
 *
 * @since 1.0.0
 *
 * @package Envira_Gallery
 * @author  Envira Team
 */

namespace Envira\Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

/**
 * Helper Class to copy photos between galleries.
 *
 * @since 1.7.0
 */
class Copy {

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
	 * Holds any plugin error messages.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $errors = [];

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Import a gallery.
		$this->copy_photos();

		// Copy photos from one gallery to another.
		add_action( 'init', [ $this, 'copy_photos' ] );
		add_action( 'admin_notices', [ $this, 'notices' ] );

	}

	/**
	 * Imports an Envira gallery.
	 *
	 * @since 1.0.0
	 *
	 * @return null Return early (possibly setting errors) if failing proper checks to import the gallery.
	 */
	public function copy_photos() {

		if ( ! $this->verify_copy_gallery() ) {
			return;
		}

		if ( ! $this->can_copy_images() ) {
			$this->errors[] = __( 'Sorry, but you lack the permissions to copy images.', 'envira-gallery' );
			return;
		}

		if ( empty( $_POST['envira_copy_target_gallery'] ) ) { // @codingStandardsIgnoreLine
			$this->errors[] = __( 'Sorry, but there was an error.', 'envira-gallery' );
			return;
		}

		$existing_images     = [];
		$total_images_copied = 0;

		// Retrieve the JSON contents of the file. If that fails, return an error.
		$gallery_copy_to   = intval( $_POST['envira_copy_target_gallery'] ); // @codingStandardsIgnoreLine
		$gallery_copy_from = intval( $_GET['post'] ); // @codingStandardsIgnoreLine
		// Get Gallery Data.
		$gallery_data_copy_to   = envira_get_gallery( $gallery_copy_to );
		$gallery_data_copy_from = envira_get_gallery( $gallery_copy_from );
		if ( ! $gallery_data_copy_to || ! $gallery_data_copy_from ) {
			$this->errors[] = __( 'Sorry, but there was an error.', 'envira-gallery' );
			return;
		}
		// Make sure there is a gallery array. If there is not, then this makes things simple.
		if ( ! isset( $gallery_data_copy_to['gallery'] ) || empty( $gallery_data_copy_to['gallery'] ) ) {
			$gallery_data_copy_to['gallery'] = [];
			$gallery_data_copy_to['gallery'] = $gallery_data_copy_from['gallery'];
			$total_images_copied             = count( $gallery_data_copy_from['gallery'] );
			// Simply add the images from the copy_from.
		} else {
			// We need to add unique items to the gallery array.
			foreach ( $gallery_data_copy_to['gallery'] as $gallery_data_copy_to_key => $gallery_data_copy_to_info ) {
				$existing_images[] = $gallery_data_copy_to_key;
			}
			if ( ! empty( $gallery_data_copy_from['gallery'] ) ) {
				foreach ( $gallery_data_copy_from['gallery'] as $gallery_data_copy_from_key => $gallery_data_copy_from_info ) {
					if ( ! in_array( $gallery_data_copy_from_key, $existing_images, true ) ) {
						$gallery_data_copy_to['gallery'][ $gallery_data_copy_from_key ] = $gallery_data_copy_from_info;
						$total_images_copied++;
					}
				}
			}
		}

		if ( 0 === $total_images_copied ) {
			$this->errors   = [];
			$this->errors[] = __( 'Sorry, no images were copied. Check and see if you are trying to copy duplicated images.', 'envira-gallery' );
			return;
		}

		// Update the meta for the post that is receiving the gallery.
		update_post_meta( $gallery_copy_to, '_eg_gallery_data', $gallery_data_copy_to );

	}

	/**
	 * Determines if a gallery import nonce is valid and verified.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the nonce is valid, false otherwise.
	 */
	public function verify_copy_gallery() {

		return isset( $_POST['envira-gallery-copy-images'] ) && wp_verify_nonce( $_POST['envira-gallery-copy-images'], 'envira-gallery-copy-images' ); // @codingStandardsIgnoreLine

	}

	/**
	 * Determines if the user can actually import the gallery.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the user can import the gallery, false otherwise.
	 */
	public function can_copy_images() {

		$manage_options = current_user_can( 'manage_options' );
		return apply_filters( 'envira_gallery_copy_images_cap', $manage_options );

	}

	/**
	 * Outputs any errors or notices generated by the class.
	 *
	 * @since 1.0.0
	 */
	public function notices() {

		if ( ! empty( $this->errors ) ) :
			?>
		<div id="message" class="error">
			<p><?php echo esc_html( implode( '<br>', $this->errors ) ); ?></p>
		</div>
			<?php
		endif;

		// If a gallery has been copied, create a notice for the status.
		if ( empty( $this->errors ) && isset( $_POST['envira-gallery-copy-images'] ) && $_POST['envira-gallery-copy-images'] ) : // @codingStandardsIgnoreLine
			?>
		<div id="message" class="updated">
			<p><?php echo esc_html( __( 'Photos copied.', 'envira-gallery' ) ); ?></p>
		</div>
			<?php
		endif;

	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @return object The Envira_Gallery_Copy object.
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Gallery_Copy ) ) {
			self::$instance = new Envira_Gallery_Copy();
		}

		return self::$instance;

	}

}
