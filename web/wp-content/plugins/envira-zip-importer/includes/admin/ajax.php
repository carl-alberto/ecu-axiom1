<?php
/**
 * Ajax class.
 *
 * @since 1.0.0
 *
 * @package Envira_Gallery
 * @subpackage Envira Zip Importer
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

/**
 * AJAX class.
 *
 * @since 1.0.0
 */
class Envira_ZIP_Importer_Ajax {

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

		// Hooks and Filters to prepare gallery data and output gallery data.
		add_filter( 'envira_gallery_ajax_item_data', array( $this, 'expand_zip_file' ), 10, 3 );
		add_filter( 'envira_gallery_ajax_get_gallery_item_html', array( $this, 'get_gallery_item_html' ), 10, 4 );

	}

	/**
	 * Hooks into the prepare gallery data process, which is fired when a file is uploaded
	 * to an Envira Gallery, and checks if the attachment is a ZIP file.
	 *
	 * If so, expands the ZIP file and imports all images as new items.
	 * If not, just returns the gallery metadata as it'll be an image that's already been prepared
	 * by Envira Gallery.
	 *
	 * @since 1.0.0
	 *
	 * @param array $gallery_data Gallery Data.
	 * @param array $attachment Media Library Attachment.
	 * @param int   $attachment_id Attachment ID.
	 * @return array Gallery Data
	 */
	public function expand_zip_file( $gallery_data, $attachment, $attachment_id ) {

		// Check attachment belongs to this Addon.
		if ( ! $this->attachment_created_by_addon( $attachment_id ) ) {
			return $gallery_data;
		}

		// Setup WP_Filesystem.
		$tmp_dir = Envira_ZIP_Importer_Common::get_instance()->get_tmp_dir();

		if ( ! defined( 'FS_METHOD' ) ) {

			define( 'FS_METHOD', 'direct' );

		}

		if ( ! defined( 'FS_CHMOD_DIR' ) ) {

			define( 'FS_CHMOD_DIR', 0755 );

		}

		if ( ! defined( 'FS_METHOD' ) ) {

			define( 'FS_CHMOD_FILE', 0666 );

		}

		$folder      = false;
		$folder_name = false;

		require_once ABSPATH . 'wp-admin/includes/image.php';
		global $wp_filesystem;
		WP_Filesystem();

		// Delete the envira-tmp directory, so we create a new empty one.
		$wp_filesystem->delete( $tmp_dir, true );

		// Create an envira-tmp directory inside /uploads if it doesn't already exist.
		if ( ! $wp_filesystem->is_dir( $tmp_dir ) ) {
			$result = $wp_filesystem->mkdir( $tmp_dir );
			if ( ! $result ) {
				return $gallery_data;
			}
		}

		// Unzip the uploaded file into the envira-tmp directory.
		$destination = wp_upload_dir();

		$filename = get_post_meta( $attachment_id, '_wp_attached_file', true );

		$filename_path = $destination['basedir'] . '/' . $filename;

		$result = unzip_file( $filename_path, $tmp_dir );
		if ( is_wp_error( $result ) ) {

			return $gallery_data;
		}

		// Unzip was OK - check there were some files in the ZIP file.
		$existing_files = $wp_filesystem->dirlist( $tmp_dir, true, true );
		if ( count( $existing_files ) === 0 ) {

			return $gallery_data;
		}

		// Get Envira's supported filetypes.
		$supported_image_types = Envira_Gallery_Common::get_instance()->get_supported_filetypes();
		if ( ! is_array( $supported_image_types ) ) {

			return $gallery_data;

		}

		$supported_image_types      = $supported_image_types[0]; // First set are the Envira Images.
		$supported_image_extensions = explode( ',', $supported_image_types['extensions'] );

		// go through each file, which is stored deeper in the $existing_files array.
		$files = $existing_files;

		// account for the possiblity that this is a zipped folder containing (if it is, then files would be a level down ).
		if ( empty( $files['files'] ) ) {
			$folder = reset( $files );
			if ( 'd' === strtolower( $folder['type'] ) ) {
				$folder_name = key( $files ) . '/';
				if ( ! empty( $folder['files'] ) ) {
					$files = $folder['files'];
				}
			}
		}

		// Iterate through the expanded archive to find any images,
		// to add to the Media Library and this Gallery.
		foreach ( $files as $file ) {

			// Check item is a file.
			if ( 'f' !== $file['type'] ) {

				continue;
			}

			// Check file is an image.
			$envira_zip_importer_common = Envira_ZIP_Importer_Common::get_instance();
			$file_path                  = $envira_zip_importer_common->get_tmp_url() . '/' . $folder_name . $file['name'];
			$file_info                  = pathinfo( $file_path );
			if ( ! in_array( $file_info['extension'], $supported_image_extensions, true ) ) {

				continue;
			}

			// File is an image - store in Media Library.
			$get_file    = wp_remote_get( $file_path );
			$upload_file = wp_upload_bits( $file_info['basename'], null, wp_remote_retrieve_body( $get_file ) );

			if ( is_wp_error( $upload_file ) || ! empty( $upload_file['error'] ) ) {

				continue;
			}

			// Store as attachment.
			$mime_filetype = wp_check_filetype( basename( $upload_file['file'] ), null );
			$attachment    = array(
				'guid'           => $upload_file['url'],
				'post_mime_type' => $mime_filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $upload_file['file'] ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);

			$attach_id = wp_insert_attachment( $attachment, $upload_file['file'], $gallery_data['id'] );

			if ( is_wp_error( $attach_id ) ) {

				continue;
			}

			// Generate metadata.
			$attach_data = wp_generate_attachment_metadata( $attach_id, $upload_file['file'] );
			$result      = wp_update_attachment_metadata( $attach_id, $attach_data );

			// Add image to $gallery_data.
			$gallery_data['gallery'][ $attach_id ] = array(
				'status'                   => 'active',
				'src'                      => $upload_file['url'],
				'title'                    => $attachment['post_title'],
				'link'                     => $upload_file['url'],
				'alt'                      => '',
				'caption'                  => '',
				'thumb'                    => '',
				'relational_attachment_id' => $attachment_id,
			);

			// Set post meta to show that this image is attached to one or more Envira galleries.
			$has_gallery[] = $gallery_data['id'];
			update_post_meta( $attach_id, '_eg_has_gallery', $has_gallery );

			// Set post meta to show that this image is attached to a gallery on this page.
			$in_gallery = get_post_meta( $gallery_data['id'], '_eg_in_gallery', true );
			if ( empty( $in_gallery ) ) {
				$in_gallery = array();
			}
			$in_gallery[] = $attach_id;
			update_post_meta( $gallery_data['id'], '_eg_in_gallery', $in_gallery );
		}

		// Finally, clean up files we don't need.
		$wp_filesystem->delete( $tmp_dir );

		return $gallery_data;

	}

	/**
	 * If the $attach_id is a ZIP file, replaces the HTML markup which will appear on the Images tab of the Metabox
	 * with HTML markup of all images in the ZIP file that expand_zip_file() above has stored in the Media Library.
	 *
	 * Also removes the ZIP file from $gallery_data - we do this at this point, as doing it earlier would result
	 * in errors.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html Gallery HTML.
	 * @param array  $gallery_data Gallery Data.
	 * @param int    $attachment_id Attachment ID.
	 * @param int    $post_id Gallery Post ID.
	 * @return string Gallery HTML.
	 */
	public function get_gallery_item_html( $html, $gallery_data, $attachment_id, $post_id ) {

		// Check attachment belongs to this Addon.
		if ( ! $this->attachment_created_by_addon( $attachment_id ) ) {
			return $html;
		}

		// Rebuild HTML by getting all image attachments belonging to this attachment.
		$html     = '';
		$instance = Envira_Gallery_Metaboxes::get_instance();
		foreach ( $gallery_data['gallery'] as $attach_id => $image ) {
			// Check image was created by this addon.
			if ( ! isset( $image['relational_attachment_id'] ) ) {
				continue;
			}
			if ( $image['relational_attachment_id'] !== $attachment_id ) {
				continue;
			}

			// Attachment is an image just uploaded as a result of the ZIP file.
			// Build HTML.
			$html .= $instance->get_gallery_item( $attach_id, $gallery_data['gallery'][ $attach_id ], $post_id );
		}

		// Delete and unset the ZIP file from the media library and gallery arrays,
		// as we don't need it any more.
		wp_delete_attachment( $attachment_id, true );

		// Unset from _eg_in_gallery.
		// Envira Gallery will have added this attachment ZIP to this meta value.
		$in_gallery = get_post_meta( $post_id, '_eg_in_gallery', true );
		if ( ! empty( $in_gallery ) ) {
			foreach ( $in_gallery as $key => $attach_id ) {
				if ( $attach_id === $attachment_id ) {
					unset( $in_gallery[ $key ] );
					update_post_meta( $post_id, '_eg_in_gallery', $in_gallery );
					break;
				}
			}
		}

		// Unset from gallery data.
		unset( $gallery_data['gallery'][ $attachment_id ] );
		update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

		// Return our HTML, which now comprises of all images from the ZIP archive.
		return $html;

	}

	/**
	 * Determines whether the given attachment ID is a supported file type that this Addon
	 * allows.
	 *
	 * @since 1.0.0
	 *
	 * @param int $attachment_id Attachment ID.
	 * @return bool Supported
	 */
	public function attachment_created_by_addon( $attachment_id ) {

		// Get WP upload dirs.
		$destination = wp_upload_dir();

		// Get filename and info.
		$filename      = get_post_meta( $attachment_id, '_wp_attached_file', true );
		$filename_path = $destination['basedir'] . '/' . $filename;
		$fileinfo      = pathinfo( $filename );

		// Check we got some file information.
		if ( ! is_array( $fileinfo ) || ! isset( $fileinfo['extension'] ) ) {
			return false;
		}

		// Check if the attachment is a supported file type.
		$supported_file_types = Envira_ZIP_Importer_Common::get_instance()->get_supported_filetypes();
		if ( ! in_array( $fileinfo['extension'], $supported_file_types, true ) ) {
			// Upload is not a supported format (therefore will be an image).
			return false;
		}

		return true;

	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @return object The Envira_ZIP_Importer_Ajax object.
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_ZIP_Importer_Ajax ) ) {
			self::$instance = new Envira_ZIP_Importer_Ajax();
		}

		return self::$instance;

	}

}

// Load the AJAX class.
$envira_zip_importer_ajax = Envira_ZIP_Importer_Ajax::get_instance();
