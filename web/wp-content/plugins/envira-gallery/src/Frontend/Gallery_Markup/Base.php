<?php
/**
 * Shortcode Markup base abstract.
 *
 * @since ??
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

namespace Envira\Frontend\Gallery_Markup;

use Envira\Utils\Shortcode_Utils;

/**
 * Base class for Layouts.
 */
abstract class Base {
	use Vars;

	/**
	 * Gallery type. Obtained from parsed_attrs.
	 *
	 * @var string
	 */
	private $type;

	/**
	 * Gallery limit. Obtained from parsed_attrs.
	 *
	 * @var int
	 */
	private $limit;

	/**
	 * Holds image URLs for indexing.
	 *
	 * @var mixed
	 */
	private $index;

	/**
	 * Base construct.
	 *
	 * @param array $config All needed data.
	 */
	public function __construct( $config ) {
		$this->is_mobile  = $config['is_mobile'];
		$this->data       = $config['data'];
		$this->gallery_id = $config['gallery_id'];
		$this->options_id = $config['options_id'];
		$this->unique_id  = $config['unique_id'];
		$this->type       = $config['type'];
		$this->limit      = $config['limit'];
		$this->index      = $config['index'];
	}

	/**
	 * Gallery Markup.
	 *
	 * @return string
	 */
	public function markup() {
		// ====== Wrapper Attributes ======
		$wrapper_id    = sanitize_html_class( "envira-gallery-wrap-$this->unique_id" );
		$wrapper_class = Shortcode_Utils::classnames(
			apply_filters(
				'envira_gallery_output_classes',
				$this->get_wrapper_classes(),
				$this->data
			)
		);

		// Schema.org microdata ( Itemscope, etc. ) interferes with Google+ Sharing... so we are adding this via filter rather than hard coding.
		$schema_microdata = apply_filters(
			'envira_gallery_output_shortcode_schema_microdata',
			'itemscope itemtype="http://schema.org/ImageGallery"',
			$this->data
		);

		$description_position = envira_get_config( 'description_position', $this->data );

		// ====== Container Attributes ======
		$container_id = sanitize_html_class( "envira-gallery-$this->unique_id" );

		// Container class.
		$extra_class     = explode( ' ', trim( apply_filters( 'envira_gallery_output_extra_css', '', $this->data ) ) );
		$container_class = Shortcode_Utils::classnames( array_merge( $this->get_container_classes(), $extra_class ) );

		// Container data.
		$gallery_images_raw = ! empty( $this->data['gallery_images_raw '] )
			? $this->data['gallery_images_raw']
			: envira_get_gallery_images( $this->gallery_id, true, $this->data, false, false, $this->type, false );

		$gallery_config = envira_get_gallery_config( $this->options_id, false, $this->data, $this->is_mobile );
		$gallery_images = wp_json_encode( array_values( $gallery_images_raw ), JSON_UNESCAPED_UNICODE );
		$lightbox_theme = htmlentities( envira_load_lightbox_config( $this->options_id, false, $this->type ) );

		$container_data = implode(
			' ',
			[
				"data-envira-id=\"$this->gallery_id\"",
				"data-gallery-config='$gallery_config'",
				"data-gallery-images='$gallery_images'",
				"data-lightbox-theme='$lightbox_theme'",
			]
		);

		$type = envira_get_config( 'type', $this->data );

		// == Container start ==
		$container  = apply_filters( 'envira_gallery_temp_output_before_container', '', $this->data );
		$container .= "<div id=\"$container_id\" class=\"$container_class\" $container_data>";

		// Current item number.
		$count = 1;
		// TODO instead of passing gallery_data['gallery'] pass the item from $gallery_images_raw that has all sizes and it is sanitized.
		foreach ( (array) $this->data['gallery'] as $item_id => $item ) {
			// Add the gallery item to the markup.
			$container = $this->generate_gallery_item_markup(
				$container,
				$this->data,
				$item,
				$item_id,
				$count
			);

			// Check the counter - if we are an instagram gallery AND there's a limit, then stop here.
			if ( 'instagram' === $type && $this->limit && $count >= $this->limit ) {
				break;
			}

			// Increment current item number.
			$count ++;
		}

		// == Container end ==
		$container .= '</div>';

		$container = apply_filters( 'envira_gallery_temp_output_after_container', $container, $this->data );

		// NoJS fallback support.
		$no_js  = '<noscript>';
		$no_js .= $this->get_noscript_images( $this->unique_id );
		$no_js .= '</noscript>';

		$no_js = apply_filters( 'envira_gallery_output_noscript', $no_js, $this->data );

		// ====== Gallery Markup ======

		// Apply a filter before starting the gallery HTML.
		$gallery_markup = apply_filters( 'envira_gallery_output_start', '', $this->data );

		// Wrapper start.
		$gallery_markup .= "<div id=\"$wrapper_id\" class=\"$wrapper_class\" $schema_microdata>";

		// Loader.
		$gallery_markup = apply_filters(
			'envira_gallery_output_loader',
			$gallery_markup . '<div class="envira-loader"><div></div><div></div><div></div><div></div></div>',
			$gallery_markup,
			$this->data
		);

		if ( 'above' === $description_position ) {
			$gallery_markup .= $this->description();
		}

		$gallery_markup = apply_filters(
			'envira_gallery_output_before_container',
			$gallery_markup,
			$this->data
		);

		$gallery_markup = apply_filters(
			'envira_gallery_output_after_container',
			$gallery_markup . $container,
			$this->data
		);

		// Wrapper end.
		$gallery_markup .= '</div>';

		if ( 'below' === $description_position ) {
			$gallery_markup = $gallery_markup . $this->description();
		}

		$gallery_markup = apply_filters( 'envira_gallery_output_end', $gallery_markup, $this->data );

		// Remove any contextual filters, so they don't affect other galleries on the page.
		if ( envira_get_config( 'mobile', $this->data ) ) {
			remove_filter( 'envira_gallery_output_image_attr', [ $this, 'mobile_image' ], 999 );
		}

		$gallery_markup .= $no_js;

		return $gallery_markup;
	}

	/**
	 * Generate wrapper classes
	 *
	 * @since 1.9.0
	 */
	protected function get_wrapper_classes() {
		$rtl          = envira_get_config( 'rtl', $this->data );
		$data_classes = (array) envira_get_config( 'classes', $this->data );
		$classes      = array_merge( $data_classes, [ 'envira-gallery-wrap' ] );

		// If the gallery has RTL support, add a class for it.
		if ( $rtl ) {
			$classes[] = 'envira-gallery-rtl';
		}

		// If the gallery has lazy loading, add a class for it.
		if ( Shortcode_Utils::is_lazy_loading( $this->data ) ) {
			$classes[] = 'envira-lazy-loading-enabled';
		} else {
			$classes[] = 'envira-lazy-loading-disabled';
		}

		return $classes;
	}

	/**
	 * Get container class. Method intended to be extended.
	 *
	 * @return array
	 */
	protected function get_container_classes() {
		$columns = absint( envira_get_config( 'columns', $this->data ) );

		return [
			'envira-gallery-public',
			"envira-gallery-$columns-columns",
			'envira-clear',
		];
	}

	/**
	 * Outputs an individual gallery item in the grid
	 *
	 * @since 1.7.1
	 *
	 * @param string $gallery Gallery HTML.
	 * @param array  $data Gallery Config.
	 * @param array  $item Gallery Item (Image).
	 * @param int    $item_id Gallery Image ID.
	 * @param int    $count Index.
	 *
	 * @return string Gallery HTML
	 */
	public function generate_gallery_item_markup( $gallery, $data, $item, $item_id, $count ) {
		if ( ! $this->data ) {
			$this->data = $data;
		}

		$item     = apply_filters( 'envira_gallery_output_item_data', $item, $item_id, $this->data, $count );
		$imagesrc = envira_get_image_src( $item_id, $item, $this->data ); // Get image and image retina URLs.

		// If we don't get an imagesrc, it's likely because of an error w/ dynamic
		// So to prevent JS errors or not rendering the gallery at all, return the gallery HTML because we can't render without it.
		if ( ! $imagesrc || ! $item ) {
			return $gallery;
		}

		$sanitized_item = $this->sanitize_item( $item, $item_id );

		// Filter output before starting this gallery item.
		$gallery = apply_filters( 'envira_gallery_output_before_item', $gallery, $item_id, $item, $this->data, $count );

		// Append the image to the gallery output.
		$gallery .= $this->get_item_object( $this->get_item_config( $sanitized_item, $item_id, $count, $imagesrc ) )->markup();

		// Filter the output before returning.
		return apply_filters( 'envira_gallery_output_after_item', $gallery, $item_id, $item, $this->data, $count );
	}

	/**
	 * Changes the link attribute of an image, if the Lightbox config
	 * requires a different sized image to be displayed.
	 *
	 * @since 1.3.6
	 *
	 * @param array  $item Item array.
	 * @param string $item_id Item id.
	 *
	 * @return array Image array
	 */
	private function sanitize_item( $item, $item_id ) {
		$item['title']     = empty( $item['title'] ) ? null : $item['title'];
		$item['caption']   = empty( $item['caption'] ) ? null : $item['caption'];
		$item['link_type'] = empty( $item['link_type'] ) ? null : $item['link_type'];

		// check if the url is a valid image if not return it.
		if ( ! isset( $item['link'] ) || ! envira_is_image( $item['link'] ) ) {
			return $item;
		}

		// Determine if the image url resides on a third-party site.
		$url = preg_replace( '/(?:https?:\/\/)?(?:www\.)?(.*)\/?$/i', '$1', network_site_url() );
		if ( strpos( $item['link'], $url ) === false ) {
			return $item;
		}

		// Check gallery config.
		$image_size = envira_get_config( 'lightbox_image_size', $this->data );
		// Get media library attachment at requested size.
		$image = wp_get_attachment_image_src( $item_id, $image_size );

		if ( ! is_array( $image ) ) {
			return $item;
		}

		// Determine if the image is entered by the user as an override in the image modal
		// We are doing this by comparing the first few characters to see if the filename is a possible match
		// This covers the scenario of a filename being in the same upload folder as the original image, something probably rare.
		// We can't compare the entire string because the end of the string might contain misc characters, dropping, etc.
		$filename_image = isset( $image[0] ) ? basename( $image[0] ) : false;
		$filename_link  = basename( $item['link'] );
		if ( ( false !== $filename_image ) && $filename_link ) {
			$pos = ( strtolower( $filename_image ) === strtolower( $filename_link ) ) ? 0 : strspn( $filename_image ^ $filename_link, "\0" );
		} else {
			$pos = false;
		}

		// First few characters don't match, likely this is a different image in the same upload directory
		// The number can be changed to literally anything.
		if ( $pos <= apply_filters( 'envira_gallery_check_image_file_name', 10, $filename_image, $filename_link, $this->data ) ) {
			// check for REAL short file names.
			$file_with_sizes_removed               = preg_replace( '/(\d+)x(\d+)/', '', $filename_image );
			$filename_image_with_extension_removed = preg_replace( '/-\\.[^.\\s]{3,4}$/', '', $file_with_sizes_removed );
			$filename_link_with_extension_removed  = preg_replace( '/\\.[^.\\s]{3,4}$/', '', $filename_link );
			if ( strtolower( $filename_image_with_extension_removed ) !== strtolower( $filename_link_with_extension_removed ) ) {
				return $item;
			}
		}

		// Inject new image size into $item.
		$item['link'] = $image[0];

		return $item;
	}

	/**
	 * Get item config.
	 *
	 * @param array $config Item config.
	 *
	 * @return Item
	 */
	protected function get_item_object( $config ) {
		return new Item( $config );
	}

	/**
	 * Get item config.
	 *
	 * @param array   $item Item data.
	 * @param numeric $item_id Item attachment id.
	 * @param int     $count Current item that is processing.
	 * @param string  $imagesrc Image src.
	 *
	 * @return array
	 */
	private function get_item_config( $item, $item_id, $count, $imagesrc ) {
		return [
			'is_mobile'    => $this->is_mobile,
			'gallery_data' => $this->data,
			'gallery_id'   => $this->gallery_id,
			'options_id'   => $this->options_id,
			'unique_id'    => $this->unique_id,
			'count'        => $count,
			'item'         => $item,
			'item_id'      => $item_id,
			'src'          => $imagesrc,
		];
	}

	/**
	 * Returns a set of indexable image links to allow SEO indexing for preloaded images.
	 *
	 * @since 1.7.0
	 *
	 * @param mixed $id The slider ID to target.
	 *
	 * @return string $images String of indexable image HTML.
	 */
	private function get_noscript_images( $id ) {
		$images = '';

		if ( empty( $this->index[ $id ] ) || ! is_array( $this->index[ $id ] ) ) {
			// If there are no images, don't do anything.
			return $images;
		}

		// Potentially assign a CSS class because some lazy loaders (like Jetpack) will try to process these images
		// and a CSS class might be used as a blacklist.
		$css_classes = apply_filters( 'envira_gallery_indexable_image_css', '', $id );
		$classes     = "envira_noscript_images $css_classes";

		foreach ( $this->index[ $id ] as $item ) {
			$src     = esc_url( $item['src'] );
			$alt     = esc_attr( $item['alt'] );
			$images .= "<img src='$src' alt='$alt' class='$classes' />";
		}

		return apply_filters( 'envira_gallery_indexable_images', $images, $this->index, $id );
	}

	/**
	 * Builds HTML for the Gallery Description
	 *
	 * @since 1.3.0.2
	 *
	 * @return string
	 */
	private function description() {
		$margin        = envira_get_config( 'margin', $this->data );
		$wrapper_style = "padding-bottom: {$margin}px;";

		$wrapper = "<div class=\"envira-gallery-description envira-gallery-description-above\" style=\"$wrapper_style\">";
		$wrapper = apply_filters( 'envira_gallery_output_before_description', $wrapper, $this->data );

		$wrapper .= Shortcode_Utils::get_description( $this->data );

		$wrapper  = apply_filters( 'envira_gallery_output_after_description', $wrapper, $this->data );
		$wrapper .= '</div>';

		return $wrapper;
	}
}
