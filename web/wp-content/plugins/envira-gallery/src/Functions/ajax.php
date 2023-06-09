<?php
/**
 * Handles all admin ajax interactions for the Envira Gallery plugin.
 *
 * @since 1.7.0
 *
 * @package Envira Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Envira\Admin\Metaboxes;
use Envira\Admin\Notices;

add_action( 'wp_ajax_envira_gallery_change_type', 'envira_gallery_ajax_change_type' );
/**
 * Changes the type of gallery to the user selection.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_change_type() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-change-type', 'nonce' );

	// Prepare variables.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;
	$post    = get_post( $post_id );
	$type    = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : null;

	// Retrieve the data for the type selected.
	ob_start();
	$instance = new Envira\Admin\Metaboxes();

	$instance->images_display( $type, $post );

	$html = ob_get_clean();

	// Send back the response.
	echo wp_json_encode(
		[
			'type' => $type,
			'html' => $html,
		]
	);
	die;

}

add_action( 'wp_ajax_envira_change_image_status', 'envira_gallery_change_image_status' );

/**
 * Helper Method to change image status.
 *
 * @access public
 * @return void
 */
function envira_gallery_change_image_status() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-save-meta', 'nonce' );

	// Prepare variables.
	$post_id      = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;
	$attach_id    = isset( $_POST['gallery_id'] ) ? wp_unslash( $_POST['gallery_id'] ) : null; // @codingStandardsIgnoreLine
	$status       = isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : '';
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );

	// Go ahead and ensure to store the attachment ID.
	$gallery_data['gallery'][ $attach_id ]['id'] = $attach_id;

	// Save the different types of default meta fields for images, videos and HTML slides.
	if ( isset( $status ) ) {
		$gallery_data['gallery'][ $attach_id ]['status'] = trim( esc_html( $status ) );

	}

	// Allow filtering of meta before saving.
	$gallery_data = apply_filters( 'envira_ajax_change_status', $gallery_data, $status, $attach_id, $post_id );

	// Update the slider data.
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

	// Flush the slider cache.
	envira_flush_gallery_caches( $post_id );

	wp_send_json_success();
	die;

}

	add_action( 'wp_ajax_envira_sort_publish', 'envira_gallery_sort_gallery' );

/**
 * Helper Method to Sort Gallery.
 *
 * @access public
 * @return void
 */
function envira_gallery_sort_gallery() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-save-meta', 'nonce' );

	// Prepare variables.
	$post_id   = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;
	$order     = isset( $_POST['order'] ) ? sanitize_text_field( wp_unslash( $_POST['order'] ) ) : '';
	$direction = isset( $_POST['direction'] ) ? sanitize_text_field( wp_unslash( $_POST['direction'] ) ) : '';
	if ( ! $direction && isset( $_POST['sort_direction'] ) ) {
		$direction = sanitize_text_field( wp_unslash( $_POST['sort_direction'] ) );
	}

	$slides = '';

	$data = get_post_meta( $post_id, '_eg_gallery_data', true );

	$gallery_data = envira_sort_gallery( $data, $order, $direction );

	// Update the slider data.
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

	// Run hook before finishing.
	do_action( 'envira_ajax_sort_gallery', $post_id, $gallery_data, $order );

	// Flush the slider cache.
	envira_flush_gallery_caches( $post_id );

	// Return a HTML string comprising of all gallery images, so the UI can be updated.
	$html = '';

	$metaboxes = new Envira\Admin\Metaboxes();

	foreach ( (array) $gallery_data['gallery'] as $id => $data ) {

		$html .= $metaboxes->get_gallery_item( $id, $data, ( ! empty( $data['type'] ) ? $data['type'] : 'image' ), $post_id );

	}

	echo wp_send_json_success( $html ); // @codingStandardsIgnoreLine - Unknown

	die;

}

add_action( 'wp_ajax_envira_gallery_change_preview', 'envira_gallery_ajax_change_preview' );
/**
 * Returns the output for the Preview Metabox for the given Gallery Type.
 *
 * @since 1.5.0
 */
function envira_gallery_ajax_change_preview() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-change-preview', 'nonce' );

	// Prepare variables.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;
	$type    = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

	// Get the saved Gallery configuration.
	$data = ( class_exists( 'Envira_Gallery' ) ? Envira_Gallery::get_instance()->get_gallery( $post_id ) : Envira_Gallery_Lite::get_instance()->get_gallery( $post_id ) );

	// Iterate through the POSTed Gallery configuration (which comprises of index based fields),
	// overwriting the above with the supplied values.  This gives us the most up to date,
	// unsaved configuration.
	if ( isset( $_POST['data'] ) ) {

		foreach ( wp_unslash( $_POST['data'] ) as $index => $field ) { // @codingStandardsIgnoreLine

			// Skip if this isnt' a configuration field.
			if ( strpos( $field['name'], '_envira_gallery[' ) === false ) {
				continue;
			}

			// Extract the key from the field name.
			preg_match_all( '/\[([^\]]*)\]/', $field['name'], $matches );
			if ( ! isset( $matches[1] ) || count( $matches[1] ) === 0 ) {
				continue;
			}

			// Add this field key/value pair to the configuration.
			$data['config'][ $matches[1][0] ] = $field['value'];

		}
	}
	// Retrieve the preview for the type selected, using the now up-to-date gallery configuration.
	ob_start();
	do_action( 'envira_gallery_preview_' . $type, $data );
	$html = ob_get_clean();

	// Send back the response.
	echo wp_json_encode( $html );
	die;

}

	add_action( 'wp_ajax_envira_gallery_set_user_setting', 'envira_gallery_ajax_set_user_setting' );
	/**
	 * Stores a user setting for the logged in WordPress User
	 *
	 * @since 1.5.0
	 */
function envira_gallery_ajax_set_user_setting() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-set-user-setting', 'nonce' );

	// Prepare variables.
	$name  = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$value = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';

	// Set user setting.
	set_user_setting( $name, $value );

	// Send back the response.
	wp_send_json_success();
	die();

}

	add_action( 'wp_ajax_envira_gallery_load_image', 'envira_gallery_ajax_load_image' );
	/**
	 * Loads an image into a gallery.
	 *
	 * @since 1.0.0
	 */
function envira_gallery_ajax_load_image() {

	// Run a security check first.
	check_ajax_referer( 'envira-gallery-load-image', 'nonce' );

	// Prepare variables.
	$id      = isset( $_POST['id'] ) ? absint( wp_unslash( $_POST['id'] ) ) : null;
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	// Set post meta to show that this image is attached to one or more Envira galleries.
	$has_gallery = get_post_meta( $id, '_eg_has_gallery', true );

	if ( empty( $has_gallery ) ) {
		$has_gallery = [];
	}

	$has_gallery[] = $post_id;
	update_post_meta( $id, '_eg_has_gallery', $has_gallery );

	// Set post meta to show that this image is attached to a gallery on this page.
	$in_gallery = get_post_meta( $post_id, '_eg_in_gallery', true );
	if ( empty( $in_gallery ) ) {
		$in_gallery = [];
	}

	$in_gallery[] = $id;
	update_post_meta( $post_id, '_eg_in_gallery', $in_gallery );

	// Set data and order of image in gallery.
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );

	if ( empty( $gallery_data ) ) {
		$gallery_data = [];
	}

	// If no gallery ID has been set, set it now.
	if ( empty( $gallery_data['id'] ) ) {
		$gallery_data['id'] = $post_id;
	}

	// Set data and update the meta information.
	$gallery_data = envira_gallery_ajax_prepare_gallery_data( $gallery_data, $id );

	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

	// Run hook before building out the item.
	do_action( 'envira_gallery_ajax_load_image', $id, $post_id );

	$metaboxes = new Envira\Admin\Metaboxes();

	// Build out the individual HTML output for the gallery image that has just been uploaded.
	$html = $metaboxes->get_gallery_item( $id, $gallery_data['gallery'][ $id ], $post_id );

	// Allow addons to filter the HTML output.
	$html = apply_filters( 'envira_gallery_ajax_get_gallery_item_html', $html, $gallery_data, $id, $post_id );

	$attachment_metadata = wp_get_attachment_metadata( $id );

	do_action( 'envira_gallery_insert_image_complete', $attachment_metadata, $id );

	// Flush the gallery cache.
	envira_flush_gallery_caches( $post_id );

	echo wp_json_encode( $html );
	die;

}

add_action( 'wp_ajax_envira_gallery_insert_images', 'envira_gallery_ajax_insert_images' );
/**
 * Inserts one or more images from the Media Library into a gallery.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_insert_images() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-insert-images', 'nonce' );

	// Prepare variables.
	$images = [];

	if ( isset( $_POST['images'] ) ) {
		$images = json_decode( sanitize_text_field( wp_unslash( $_POST['images'] ) ), true );
	}

	// Get the Envira Gallery ID.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	// Grab and update any gallery data if necessary.
	$in_gallery = get_post_meta( $post_id, '_eg_in_gallery', true );
	if ( empty( $in_gallery ) ) {
		$in_gallery = [];
	}

	// Set data and order of image in gallery.
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
	if ( empty( $gallery_data ) ) {
		$gallery_data = [];
	}

	// If no gallery ID has been set, set it now.
	if ( empty( $gallery_data['id'] ) ) {
		$gallery_data['id'] = $post_id;
	}

	// Loop through the images and add them to the gallery.
	foreach ( (array) $images as $i => $image ) {

		// If the image is already in the gallery, lets skip it since we don't want to override the image metadata settings.
		if ( in_array( $image['id'], $in_gallery, true ) ) {
			continue;
		}

		// Update the attachment image post meta first.
		$has_gallery = get_post_meta( $image['id'], '_eg_has_gallery', true );

		if ( empty( $has_gallery ) ) {
			$has_gallery = [];
		}

		$has_gallery[] = $post_id;
		update_post_meta( $image['id'], '_eg_has_gallery', $has_gallery );

		// Now add the image to the gallery for this particular post.
		$in_gallery[] = $image['id'];
		$gallery_data = envira_gallery_ajax_prepare_gallery_data( $gallery_data, $image['id'], $image );

		$attachment_metadata = wp_get_attachment_metadata( $image['id'] );

		do_action( 'envira_gallery_insert_image_complete', $attachment_metadata, $image['id'] );

	}

	$order     = isset( $_POST['order'] ) ? sanitize_text_field( wp_unslash( $_POST['order'] ) ) : false;
	$direction = isset( $_POST['direction'] ) ? sanitize_text_field( wp_unslash( $_POST['direction'] ) ) : false;

	$gallery_data = envira_sort_gallery( $gallery_data, $order, $direction );

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_in_gallery', $in_gallery );
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

	// Run hooks before finishing.
	do_action( 'envira_gallery_ajax_insert_images', $images, $post_id );
	$gallery_data = apply_filters( 'envira_gallery_ajax_gallery_images', $gallery_data, $images, $post_id );

	// Flush the gallery cache.
	envira_flush_gallery_caches( $post_id );
	$metaboxes = new Envira\Admin\Metaboxes();

	// Return a HTML string comprising of all gallery images, so the UI can be updated.
	$html = '';
	foreach ( (array) $gallery_data['gallery'] as $id => $data ) {
		$html .= $metaboxes->get_gallery_item( $id, $data, $post_id );
	}

	// Output JSON and exit.
	echo wp_json_encode( [ 'success' => $html ] );
	die;

}

	add_action( 'wp_ajax_envira_gallery_sort_images', 'envira_gallery_ajax_sort_images' );
	/**
	 * Sorts images based on user-dragged position in the gallery.
	 *
	 * @since 1.0.0
	 */
function envira_gallery_ajax_sort_images() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-sort', 'nonce' );

	// Prepare variables.
	$order        = isset( $_POST['order'] ) ? explode( ',', wp_unslash( $_POST['order'] ) ) : ''; // @codingStandardsIgnoreLine
	$post_id      = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );

	// Copy the gallery config, removing the images
	// Stops config from getting lost when sorting + not clicking Publish/Update.
	$new_order = $gallery_data;
	unset( $new_order['gallery'] );
	$new_order['gallery'] = [];

	// Loop through the order and generate a new array based on order received.
	foreach ( $order as $id ) {
		$new_order['gallery'][ $id ] = $gallery_data['gallery'][ $id ];
	}

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $new_order );

	// Flush the gallery cache.
	envira_flush_gallery_caches( $post_id );

	echo wp_json_encode( true );
	die;

}

	add_action( 'wp_ajax_envira_gallery_remove_image', 'envira_gallery_ajax_remove_image' );

	/**
	 * Removes an image from a gallery.
	 *
	 * @since 1.0.0
	 */
function envira_gallery_ajax_remove_image() {

	// Run a security check first.
	check_ajax_referer( 'envira-gallery-remove-image', 'nonce' );

	// Prepare variables.
	$post_id      = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;
	$attach_id    = isset( $_POST['attachment_id'] ) ? absint( wp_unslash( $_POST['attachment_id'] ) ) : null;
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
	$in_gallery   = get_post_meta( $post_id, '_eg_in_gallery', true );
	$has_gallery  = get_post_meta( $attach_id, '_eg_has_gallery', true );

	$in_gallery_temp   = $in_gallery; // need to preserve what it was before the image is removed from the array.
	$gallery_data_temp = $gallery_data;

	// Unset the image from the gallery, in_gallery and has_gallery checkers.
	unset( $gallery_data['gallery'][ $attach_id ] );
	$key = array_search( $attach_id, (array) $in_gallery, true );
	if ( false !== $key ) {
		unset( $in_gallery[ $key ] );
	}
	$has_key = array_search( $post_id, (array) $has_gallery, true );
	if ( false !== $has_key ) {
		unset( $has_gallery[ $has_key ] );
	}

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );
	update_post_meta( $post_id, '_eg_in_gallery', $in_gallery );
	update_post_meta( $attach_id, '_eg_has_gallery', $has_gallery );

	// Run hook before finishing the reponse.
	do_action( 'envira_gallery_ajax_remove_image', $attach_id, $post_id, $gallery_data_temp );

	// If the global setting for deleting images on gallery image deletion is enabled, check
	// that the image doesn't belong to another gallery and isn't attached.
	$image_delete = envira_get_setting( 'image_delete' );
	if ( $image_delete ) {
		// Get attachment.
		$attachment = get_post( $attach_id );

		// If post parent is the Gallery ID OR the image was in the gallery (via metadata), and the image isn't in another gallery, we're OK to delete the image.
		if ( ( ( ! empty( $attachment->post_parent ) && $attachment->post_parent === $post_id ) || in_array( $attach_id, $in_gallery_temp, true ) ) && ( count( $has_gallery ) === 0 ) ) {
			wp_delete_attachment( $attach_id );
		}
	}

	// Flush the gallery cache.
	envira_flush_gallery_caches( $post_id );

	echo wp_json_encode( true );
	die;

}

	add_action( 'wp_ajax_envira_gallery_remove_images', 'envira_gallery_ajax_remove_images' );

	/**
	 * Removes multiple images from a gallery.
	 *
	 * @since 1.3.2.4
	 */
function envira_gallery_ajax_remove_images() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-remove-image', 'nonce' );

	// Prepare variables.
	$post_id      = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;
	$attach_ids   = isset( $_POST['attachment_ids'] ) ? (array) wp_unslash( $_POST['attachment_ids'] ) : array(); // @codingStandardsIgnoreLine
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
	$in_gallery   = get_post_meta( $post_id, '_eg_in_gallery', true );

	foreach ( (array) $attach_ids as $attach_id ) {
		$has_gallery = get_post_meta( $attach_id, '_eg_has_gallery', true );

		// Unset the image from the gallery, in_gallery and has_gallery checkers.
		unset( $gallery_data['gallery'][ $attach_id ] );
		$key = array_search( $attach_id, (array) $in_gallery, true );
		if ( false !== $key ) {
			unset( $in_gallery[ $key ] );
		}
		$has_key = array_search( $post_id, (array) $has_gallery, true );
		if ( false !== $has_key ) {
			unset( $has_gallery[ $has_key ] );
		}

		// Update the attachment data.
		update_post_meta( $attach_id, '_eg_has_gallery', $has_gallery );
	}

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );
	update_post_meta( $post_id, '_eg_in_gallery', $in_gallery );

	// Run hook before finishing the reponse.
	do_action( 'envira_gallery_ajax_remove_images', $attach_id, $post_id );

	// Flush the gallery cache.
	envira_flush_gallery_caches( $post_id );

	echo wp_json_encode( true );
	die;

}

	add_action( 'wp_ajax_envira_gallery_save_meta', 'envira_gallery_ajax_save_meta' );

	/**
	 * Saves the metadata for an image in a gallery.
	 *
	 * @since 1.0.0
	 */
function envira_gallery_ajax_save_meta() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-save-meta', 'nonce' );

	// Prepare variables.
	$post_id      = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;
	$attach_id    = isset( $_POST['attach_id'] ) ? absint( wp_unslash( $_POST['attach_id'] ) ) : null;
	$meta         = isset( $_POST['meta'] ) ? wp_unslash( $_POST['meta'] ) : array(); // @codingStandardsIgnoreLine
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );

	// Save the different types of default meta fields for images, videos and HTML slides.
	if ( isset( $meta['status'] ) ) {
		$gallery_data['gallery'][ $attach_id ]['status'] = trim( esc_html( $meta['status'] ) );
	}
	if ( isset( $meta['title'] ) ) {
		$gallery_data['gallery'][ $attach_id ]['title'] = trim( $meta['title'] );
	}

	if ( isset( $meta['alt'] ) ) {
		$gallery_data['gallery'][ $attach_id ]['alt'] = trim( esc_html( $meta['alt'] ) );
	}

	if ( isset( $meta['link'] ) ) {
		$gallery_data['gallery'][ $attach_id ]['link'] = esc_url( $meta['link'] );
	}

	if ( isset( $meta['link_new_window'] ) ) {
		$gallery_data['gallery'][ $attach_id ]['link_new_window'] = trim( $meta['link_new_window'] );
	}

	if ( isset( $meta['caption'] ) ) {
		$gallery_data['gallery'][ $attach_id ]['caption'] = trim( $meta['caption'] );
	}

	// Allow filtering of meta before saving.
	$gallery_data = apply_filters( 'envira_gallery_ajax_save_meta', $gallery_data, $meta, $attach_id, $post_id );

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

	// Flush the gallery cache.
	envira_flush_gallery_caches( $post_id );

	// Done.
	wp_send_json_success();
	die;

}

	add_action( 'wp_ajax_envira_gallery_save_bulk_meta', 'envira_gallery_ajax_save_bulk_meta' );

	/**
	 * Saves the metadata for multiple images in a gallery (bulk edit).
	 *
	 * @since 1.4.2.2
	 */
function envira_gallery_ajax_save_bulk_meta() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-save-meta', 'nonce' );

	// Prepare variables.
	$post_id   = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;
	$image_ids = isset( $_POST['image_ids'] ) ? wp_unslash( $_POST['image_ids'] ) : array(); // @codingStandardsIgnoreLine
	$meta      = isset( $_POST['meta'] ) ? wp_unslash( $_POST['meta'] ) : array(); // @codingStandardsIgnoreLine

	// Check the required variables exist.
	if ( empty( $post_id ) ) {
		wp_send_json_error();
	}
	if ( empty( $image_ids ) || ! is_array( $image_ids ) ) {
		wp_send_json_error();
	}
	if ( empty( $meta ) || ! is_array( $meta ) ) {
		wp_send_json_error();
	}

	// Get gallery.
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
	if ( empty( $gallery_data ) || ! is_array( $gallery_data ) ) {
		wp_send_json_error();
	}

	$image_tags = [];

	// Iterate through gallery images, updating the metadata.
	foreach ( $image_ids as $image_id ) {

		// If the image isn't in the gallery, something went wrong - so skip this image.
		if ( ! isset( $gallery_data['gallery'][ $image_id ] ) ) {
			continue;
		}

		// Save the different types of default meta fields for images, videos and HTML slides.
		if ( isset( $meta['status'] ) ) {
			$gallery_data['gallery'][ $image_id ]['status'] = trim( esc_html( $meta['status'] ) );

		}

		// Update image metadata.
		if ( isset( $meta['title'] ) && '' !== $meta['title'] ) {
			$gallery_data['gallery'][ $image_id ]['title'] = trim( $meta['title'] );
		}

		if ( isset( $meta['alt'] ) && '' !== $meta['alt'] ) {
			$gallery_data['gallery'][ $image_id ]['alt'] = trim( esc_html( $meta['alt'] ) );
		}

		if ( isset( $meta['link'] ) && '' !== $meta['link'] ) {
			$gallery_data['gallery'][ $image_id ]['link'] = esc_url( $meta['link'] );
		}

		if ( isset( $meta['link_new_window'] ) && '' !== $meta['link_new_window'] ) {
			$gallery_data['gallery'][ $image_id ]['link_new_window'] = trim( $meta['link_new_window'] );
		}

		if ( isset( $meta['caption'] ) && '' !== $meta['caption'] ) {
			$gallery_data['gallery'][ $image_id ]['caption'] = trim( $meta['caption'] );
		}

		// Allow filtering of meta before saving.
		$gallery_data = apply_filters( 'envira_gallery_ajax_save_bulk_meta', $gallery_data, $meta, $image_id, $post_id );

		// Add all image tags to array so we can send them back via ajax.
		$image_tags[ $image_id ] = wp_get_object_terms( $image_id, 'envira-tag', [ 'fields' => 'names' ] );

	}

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

	// Flush the gallery cache.
	envira_flush_gallery_caches( $post_id );

	// Done.
	wp_send_json_success( $image_tags );
	die;

}

	add_action( 'wp_ajax_envira_gallery_refresh', 'envira_gallery_ajax_refresh' );

	/**
	 * Refreshes the DOM view for a gallery.
	 *
	 * @since 1.0.0
	 */
function envira_gallery_ajax_refresh() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-refresh', 'nonce' );

	// Prepare variables.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;
	$gallery = '';

	// Grab all gallery data.
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );

	// If there are no gallery items, don't do anything.
	if ( empty( $gallery_data ) || empty( $gallery_data['gallery'] ) ) {
		echo wp_json_encode( [ 'error' => true ] );
		die;
	}
	$metaboxes = new Envira\Admin\Metaboxes();
	// Loop through the data and build out the gallery view.
	foreach ( (array) $gallery_data['gallery'] as $id => $data ) {
		$gallery .= $metaboxes->get_gallery_item( $id, $data, $post_id );
	}

	echo wp_json_encode( [ 'success' => $gallery ] );
	die;

}

add_action( 'wp_ajax_envira_gallery_load_gallery_data', 'envira_gallery_ajax_load_gallery_data' );
/**
 * Retrieves and return gallery data for the specified ID.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_load_gallery_data() {

	// Prepare variables and grab the gallery data.
	$gallery_id   = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null; // @codingStandardsIgnoreLine
	$gallery_data = get_post_meta( $gallery_id, '_eg_gallery_data', true );

	// Send back the gallery data.
	echo wp_json_encode( $gallery_data );
	die;

}

add_action( 'wp_ajax_envira_gallery_install_addon', 'envira_gallery_ajax_install_addon' );
/**
 * Installs an Envira addon.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_install_addon() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-install', 'nonce' );

	// Install the addon.
	if ( isset( $_POST['plugin'] ) ) {
		$download_url = esc_url_raw( wp_unslash( $_POST['plugin'] ) );
		global $hook_suffix;

		// Set the current screen to avoid undefined notices.
		set_current_screen();

		// Prepare variables.
		$method = '';
		$url    = add_query_arg(
			[
				'page' => 'envira-gallery-settings',
			],
			admin_url( 'admin.php' )
		);
		$url    = esc_url( $url );

		// Start output bufferring to catch the filesystem form if credentials are needed.
		ob_start();
		$creds = request_filesystem_credentials( $url, $method, false, false, null );
		if ( false === $creds ) {
			$form = ob_get_clean();
			echo wp_json_encode( [ 'form' => $form ] );
			die;
		}

		// If we are not authenticated, make it happen now.
		if ( ! WP_Filesystem( $creds ) ) {
			ob_start();
			request_filesystem_credentials( $url, $method, true, false, null );
			$form = ob_get_clean();
			echo wp_json_encode( [ 'form' => $form ] );
			die;
		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once plugin_dir_path( ENVIRA_FILE ) . 'src/Utils/Skin.php';

		// Create the plugin upgrader with our custom skin.
		$skin      = new Envira_Gallery_Skin();
		$installer = new Plugin_Upgrader( $skin );
		$installer->install( $download_url );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		if ( $installer->plugin_info() ) {
			$plugin_basename = $installer->plugin_info();
			echo wp_json_encode( [ 'plugin' => $plugin_basename ] );
			die;
		}
	}

	// Send back a response.
	echo wp_json_encode( true );
	die;

}

	add_action( 'wp_ajax_envira_gallery_activate_addon', 'envira_gallery_ajax_activate_addon' );

	/**
	 * Activates an Envira addon.
	 *
	 * @since 1.0.0
	 */
function envira_gallery_ajax_activate_addon() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-activate', 'nonce' );

	// Activate the addon.
	if ( isset( $_POST['plugin'] ) ) {
		$activate = activate_plugin( wp_unslash( $_POST['plugin'] ) );  // @codingStandardsIgnoreLine

		if ( is_wp_error( $activate ) ) {
			echo wp_json_encode( [ 'error' => $activate->get_error_message() ] );
			die;
		}
	}

	echo wp_json_encode( true );
	die;

}

	add_action( 'wp_ajax_envira_gallery_deactivate_addon', 'envira_gallery_ajax_deactivate_addon' );
	/**
	 * Deactivates an Envira addon.
	 *
	 * @since 1.0.0
	 */
function envira_gallery_ajax_deactivate_addon() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-deactivate', 'nonce' );

	// Deactivate the addon.
	if ( isset( $_POST['plugin'] ) ) {
		$deactivate = deactivate_plugins( wp_unslash( $_POST['plugin'] ) );  // @codingStandardsIgnoreLine
	}

	echo wp_json_encode( true );
	die;

}

	/**
	 * Helper function to prepare the metadata for an image in a gallery.
	 *
	 * @since 1.0.0
	 *
	 * @param array $gallery_data   Array of data for the gallery.
	 * @param int   $id             The attachment ID to prepare data for.
	 * @param array $image          Attachment image. Populated if inserting from the Media Library.
	 * @return array $gallery_data Amended gallery data with updated image metadata.
	 */
function envira_gallery_ajax_prepare_gallery_data( $gallery_data, $id, $image = false ) {

	// Get attachment.
	$attachment = get_post( $id );

	// Depending on whether we're inserting from the Media Library or not, prepare the image array.
	if ( ! $image ) {
		$url       = wp_get_attachment_image_src( $id, 'full' );
		$alt_text  = get_post_meta( $id, '_wp_attachment_image_alt', true );
		$new_image = [
			'status'  => 'active',
			'src'     => isset( $url[0] ) ? esc_url( $url[0] ) : '',
			'title'   => get_the_title( $id ),
			'link'    => ( isset( $url[0] ) ? esc_url( $url[0] ) : '' ),
			'alt'     => ! empty( $alt_text ) ? $alt_text : '',
			'caption' => ! empty( $attachment->post_excerpt ) ? $attachment->post_excerpt : '',
			'thumb'   => '',
		];
	} else {
		$new_image = [
			'status'  => 'active',
			'src'     => ( isset( $image['src'] ) ? $image['src'] : $image['url'] ),
			'title'   => $image['title'],
			'link'    => $image['link'],
			'alt'     => $image['alt'],
			'caption' => $image['caption'],
			'thumb'   => '',
		];
	}

	// Allow Addons to possibly add metadata now.
	$image = apply_filters( 'envira_gallery_ajax_prepare_gallery_data_item', $new_image, $image, $id, $gallery_data );

	if ( ! is_array( $gallery_data ) ) {
		$gallery_data = [];
	}

	// If gallery data is not an array (i.e. we have no images), just add the image to the array.
	if ( ! isset( $gallery_data['gallery'] ) || ! is_array( $gallery_data['gallery'] ) ) {

		$gallery_data['gallery']        = [];
		$gallery_data['gallery'][ $id ] = $image;

	} else {

		// Add this image to the start or end of the gallery, depending on the setting.
		$media_position = envira_get_setting( 'media_position' );

		switch ( $media_position ) {

			case 'before':
				// Add image to start of images array
				// Store copy of images, reset gallery array and rebuild.
				$images                         = $gallery_data['gallery'];
				$gallery_data['gallery']        = [];
				$gallery_data['gallery'][ $id ] = $image;

				foreach ( $images as $old_image_id => $old_image ) {
					$gallery_data['gallery'][ $old_image_id ] = $old_image;
				}

				break;
			case 'after':
			default:
				// Add image, this will default to the end of the array.
				$gallery_data['gallery'][ $id ] = $image;
				break;
		}
	}

	// Filter and return.
	$gallery_data = apply_filters( 'envira_gallery_ajax_item_data', $gallery_data, $attachment, $id, $image );

	return $gallery_data;

}

	/**
	 * Called whenever a notice is dismissed in Envira Gallery or its Addons.
	 *
	 * Updates a key's value in the options table to mark the notice as dismissed,
	 * preventing it from displaying again
	 *
	 * @since 1.3.5
	 */
function envira_gallery_ajax_dismiss_notice() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-dismiss-notice', 'nonce' );

	// Deactivate the notice.
	if ( isset( $_POST['notice'] ) ) {
		// Init the notice class and mark notice as deactivated.
		$notices = new Notices();
		$notices->dismiss( wp_unslash( $_POST['notice'] ), intval( wp_unslash( $_POST['seconds'] ) ) );  // @codingStandardsIgnoreLine

		echo wp_json_encode( true );
		die;
	}

	// If here, an error occured.
	echo wp_json_encode( false );
	die;

}

	add_action( 'wp_ajax_envira_gallery_ajax_dismiss_notice', 'envira_gallery_ajax_dismiss_notice' );

	/**
	 * Called whenever a notice is dismissed in Envira Gallery or its Addons.
	 *
	 * Updates a key's value in the options table to mark the notice as dismissed,
	 * preventing it from displaying temporarily for a period of time
	 *
	 * @since 1.3.5
	 */
function envira_gallery_ajax_dismiss_notice_temp() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-dismiss-notice', 'nonce' );

	// Deactivate the notice.
	if ( isset( $_POST['notice'] ) ) {
		// Init the notice class and mark notice as deactivated.
		$notice = Envira_Gallery_Notice_Admin::get_instance();
		$notice->dismiss( wp_unslash( $_POST['notice'] ) ); // @codingStandardsIgnoreLine

		echo wp_json_encode( true );
		die;
	}

	// If here, an error occured.
	echo wp_json_encode( false );
	die;

}

add_action( 'wp_ajax_envira_gallery_ajax_dismiss_notice_temp', 'envira_gallery_ajax_dismiss_notice_temp' );

/**
 * Returns the media link (direct image URL) for the given attachment ID.
 *
 * @since 1.4.1.4
 */
add_action( 'wp_ajax_envira_gallery_get_attachment_links', 'envira_gallery_get_attachment_links' );

/**
 * Helper Method to get Attachment Links
 *
 * @access public
 * @return void
 */
function envira_gallery_get_attachment_links() {

	// Check nonce.
	check_ajax_referer( 'envira-gallery-save-meta', 'nonce' );

	// Get required inputs.
	$attachment_id = isset( $_POST['attachment_id'] ) ? absint( wp_unslash( $_POST['attachment_id'] ) ) : null;

	// Return the attachment's links.
	wp_send_json_success(
		[
			'media_link'      => wp_get_attachment_url( $attachment_id ),
			'attachment_page' => get_attachment_link( $attachment_id ),
		]
	);

}

/**
 * Returns Galleries, with an optional search term
 *
 * @since 1.5.0
 */
add_action( 'wp_ajax_envira_gallery_editor_get_galleries', 'envira_gallery_editor_get_galleries' );

/**
 * Helper Method to get Editor Galleries.
 *
 * @access public
 * @return void
 */
function envira_gallery_editor_get_galleries() {

	// Check nonce.
	check_admin_referer( 'envira-gallery-editor-get-galleries', 'nonce' );

	// Get POSTed fields.
	$search       = isset( $_POST['search'] ) ? (bool) wp_unslash( $_POST['search'] ) : false; // @codingStandardsIgnoreLine
	$search_terms = isset( $_POST['search_terms'] ) ? sanitize_text_field( wp_unslash( $_POST['search_terms'] ) ) : ''; // @codingStandardsIgnoreLine
	$prepend_ids  = isset( $_POST['prepend_ids'] ) ? stripslashes_deep( wp_unslash( $_POST['prepend_ids'] ) ) : array(); // @codingStandardsIgnoreLine
	$results      = [];

	// Get galleries.
	$instance  = Envira_Gallery::get_instance();
	$galleries = $instance->get_galleries( false, true, ( $search ? $search_terms : '' ) );

	// Build array of just the data we need.
	foreach ( (array) $galleries as $gallery ) {
		// Get the thumbnail of the first image.
		if ( isset( $gallery['gallery'] ) && ! empty( $gallery['gallery'] ) ) {
			// Get the first image.
			reset( $gallery['gallery'] );
			$key       = key( $gallery['gallery'] );
			$thumbnail = wp_get_attachment_image_src( $key, 'thumbnail' );
		}

		// Instead of pulling the title from config, attempt to pull it from the gallery post first.
		if ( isset( $gallery['id'] ) ) {
			$gallery_post = get_post( $gallery['id'] );
		} else {
			$gallery_post = false;
		}

		$temp_title = false;
		if ( isset( $gallery_post->post_title ) ) {
			$temp_title = trim( $gallery_post->post_title );
		}

		if ( ! empty( $temp_title ) ) {
			$gallery_title = $gallery_post->post_title;
		} elseif ( isset( $gallery['config']['title'] ) ) {
			$gallery_title = $gallery['config']['title'];
		} else {
			$gallery_title = false;
		}

		// Check to make sure variables are there.
		$gallery_id          = false;
		$gallery_config_slug = false;

		if ( isset( $gallery['id'] ) ) {
			$gallery_id = $gallery['id'];
		}
		if ( isset( $gallery['config']['slug'] ) ) {
			$gallery_config_slug = $gallery['config']['slug'];
		}

		if ( false !== $gallery_id ) {

			// Add gallery to results.
			$results[] = [
				'id'        => $gallery_id,
				'slug'      => $gallery_config_slug,
				'title'     => $gallery_title,
				'thumbnail' => ( ( isset( $thumbnail ) && is_array( $thumbnail ) ) ? $thumbnail[0] : '' ),
				'action'    => 'gallery', // Tells the editor modal whether this is a Gallery or Album for the shortcode output.
			];

		}
	}

	// If any prepended Gallery IDs were specified, get them now
	// These will typically be a Defaults Gallery, which wouldn't be included in the above get_galleries() call.
	if ( is_array( $prepend_ids ) && count( $prepend_ids ) > 0 ) {
		$prepend_results = [];

		// Get each Gallery.
		foreach ( $prepend_ids as $gallery_id ) {
			// Get gallery.
			$gallery = get_post_meta( $gallery_id, '_eg_gallery_data', true );

			// Get gallery first image.
			if ( isset( $gallery['gallery'] ) && ! empty( $gallery['gallery'] ) ) {
				// Get the first image.
				reset( $gallery['gallery'] );
				$key       = key( $gallery['gallery'] );
				$thumbnail = wp_get_attachment_image_src( $key, 'thumbnail' );
			}

			// Add gallery to results.
			$prepend_results[] = [
				'id'        => $gallery['id'],
				'slug'      => $gallery['config']['slug'],
				'title'     => $gallery['config']['title'],
				'thumbnail' => ( ( isset( $thumbnail ) && is_array( $thumbnail ) ) ? $thumbnail[0] : '' ),
				'action'    => 'gallery', // Tells the editor modal whether this is a Gallery or Album for the shortcode output.
			];
		}

		// Add to results.
		if ( is_array( $prepend_results ) && count( $prepend_results ) > 0 ) {
			$results = array_merge( $prepend_results, $results );
		}
	}

	// Return galleries.
	wp_send_json_success( $results );

}

/**
 * Moves media (images) from one Gallery to another.
 *
 * @since 1.5.0.3
 */
add_action( 'wp_ajax_envira_gallery_move_media', 'envira_gallery_move_media' );

/**
 * Helper Method to move gallery media.
 *
 * @access public
 * @return void
 */
function envira_gallery_move_media() {

	// Check nonce.
	check_admin_referer( 'envira-gallery-move-media', 'nonce' );

	// Get POSTed fields.
	$from_gallery_id = isset( $_POST['from_gallery_id'] ) ? absint( $_POST['from_gallery_id'] ) : null;
	$to_gallery_id   = isset( $_POST['to_gallery_id'] ) ? absint( $_POST['to_gallery_id'] ) : null;
	$image_ids       = isset( $_POST['image_ids'] ) ? wp_unslash( $_POST['image_ids'] ) : array(); // @codingStandardsIgnoreLine

	if ( ! $from_gallery_id ) {
		wp_send_json_error( __( 'The From Gallery ID has not been specified.', 'envira-gallery' ) );
	}
	if ( ! $to_gallery_id ) {
		wp_send_json_error( __( 'The From Gallery ID has not been specified.', 'envira-gallery' ) );
	}
	if ( count( $image_ids ) === 0 ) {
		wp_send_json_error( __( 'No images were selected to be moved between Galleries.', 'envira-gallery' ) );
	}

	// Get from and to Galleries.
	$from_gallery = Envira_Gallery::get_instance()->_get_gallery( $from_gallery_id );
	$to_gallery   = Envira_Gallery::get_instance()->_get_gallery( $to_gallery_id );

	// Iterate through each image ID, adding the image to $to_gallery, then removing from $from_gallery.
	foreach ( $image_ids as $image_id ) {
		// Check the image exists in $from_gallery
		// If not, skip this image.
		if ( ! isset( $from_gallery['gallery'][ $image_id ] ) ) {
			continue;
		}

		// Copy the image to $to_gallery
		// Add this image to the start or end of the gallery, depending on the setting.
		$media_position = envira_get_setting( 'media_position' );

		switch ( $media_position ) {
			case 'before':
				// Add image to start of images array
				// Store copy of images, reset gallery array and rebuild.
				$images                             = $to_gallery['gallery'];
				$to_gallery['gallery']              = [];
				$to_gallery['gallery'][ $image_id ] = $from_gallery['gallery'][ $image_id ];
				foreach ( $images as $old_image_id => $old_image ) {
					$to_gallery['gallery'][ $old_image_id ] = $old_image;
				}
				break;
			case 'after':
			default:
				// Add image, this will default to the end of the array.
				$to_gallery['gallery'][ $image_id ] = $from_gallery['gallery'][ $image_id ];
				break;
		}

		// Remove the image from $from_gallery.
		unset( $from_gallery['gallery'][ $image_id ] );
	}

	// Save both Galleries.
	update_post_meta( $from_gallery_id, '_eg_gallery_data', $from_gallery );
	update_post_meta( $to_gallery_id, '_eg_gallery_data', $to_gallery );

	// Return success.
	wp_send_json_success();

}
