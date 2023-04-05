<?php
/**
 * Shortcode_Link class.
 *
 * @since ??
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

namespace Envira\Frontend;

use Envira\Utils\Shortcode_Utils;

/**
 * Envira Gallery Link Shortcode Class.
 *
 * @since ??
 */
final class Shortcode_Link {
	/**
	 * Init
	 *
	 * @since ??
	 */
	public function init() {
		add_shortcode( 'envira-link', [ $this, 'shortcode' ] );
	}

	/**
	 * Shortcode link function.
	 *
	 * @since ??
	 *
	 * @param mixed  $attrs Attributes.
	 * @param string $content The content.
	 *
	 * @return string
	 */
	public function shortcode( $attrs, $content = null ) {
		if ( is_admin() && ! wp_doing_ajax() ) {
			// if it's the admin BUT it's not admin ajax from the frontend, then return.
			return '';
		}

		$data_and_id = Shortcode_Utils::get_data_and_id( $attrs );

		if ( ! $data_and_id ) {
			return '';
		}

		$gallery_id = $data_and_id['id'];
		$data       = $data_and_id['data'];

		// Run a hook before the gallery output begins but after scripts and inits have been set.
		do_action( 'envira_gallery_link_before_output', $data );

		// Load custom lightbox themes.
		envira_load_lightbox_theme( envira_get_config( 'lightbox_theme', $data ) );

		// Load scripts and styles.
		wp_enqueue_style( ENVIRA_SLUG . '-style' );

		Shortcode_Utils::enqueue_main_script( $data );

		$gallery_config   = envira_get_gallery_config( $gallery_id );
		$lb_theme_options = envira_load_lightbox_config( $gallery_id );

		$gallery_images_data = envira_get_gallery_images( $gallery_id, null, $data, true );
		$gallery_images      = $gallery_images_data['gallery_images'];
		$sorted_ids          = $gallery_images_data['sorted_ids'];

		// Run a hook before the gallery output begins but after scripts and inits have been set.
		do_action( 'envira_link_before_output', $data );

		$link_id    = "envira-links-$gallery_id";
		$link_class = 'envira-gallery-links';

		$link_data = implode(
			' ',
			[
				"data-gallery-config='$gallery_config'",
				"data-lightbox-theme='$lb_theme_options'",
				"data-gallery-images='$gallery_images'",
				"data-gallery-sort-ids='$sorted_ids'",
			]
		);

		$output = "<a id=\"$link_id\" class=\"$link_class\" href=\"#\" $link_data >$content</a>";

		return apply_filters( 'envira_link_shortcode_output', $output );
	}
}
