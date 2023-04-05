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
 * Item class abstract to be extended per layout if needed.
 */
class Item {
	use Vars;

	/**
	 * Number of image in gallery. 1st 2nd ...
	 *
	 * @var int
	 */
	protected $count;

	/**
	 * Item data.
	 *
	 * @var array
	 */
	protected $item;

	/**
	 * Item attachment id.
	 *
	 * @var numeric
	 */
	protected $item_id;

	/**
	 * Image source.
	 *
	 * @var string
	 */
	protected $src;

	/**
	 * Processed gallery_data['config'].
	 *
	 * @var string
	 */
	protected $config;

	/**
	 * Base construct.
	 *
	 * @param array $config All needed data.
	 */
	public function __construct( $config ) {
		$this->is_mobile  = $config['is_mobile'];
		$this->data       = $config['gallery_data'];
		$this->gallery_id = $config['gallery_id'];
		$this->options_id = $config['options_id'];
		$this->unique_id  = $config['unique_id'];
		$this->count      = $config['count'];
		$this->item       = $config['item'];
		$this->item_id    = $config['item_id'];
		$this->src        = $config['src'];
	}

	/**
	 * Generate single item markup.
	 *
	 * @return string
	 */
	public function markup() {
		// == All needed config ==
		$gutter               = envira_get_config( 'gutter', $this->data );
		$margin               = envira_get_config( 'margin', $this->data );
		$gallery_link_enabled = envira_get_config( 'gallery_link_enabled', $this->data );
		$lightbox_enabled     = envira_get_config( 'lightbox_enabled', $this->data );
		$sanitized_title      = wp_strip_all_tags( htmlspecialchars( $this->item['title'] ) );
		$sanitized_caption    = esc_attr( nl2br( wp_strip_all_tags( $this->item['caption'] ) ) );
		$lightbox_theme       = envira_get_config( 'lightbox_theme', $this->data );

		$lightbox_title = apply_filters(
			'envira_gallery_output_lightbox_title',
			do_shortcode( $sanitized_title ),
			$this->data,
			$this->item_id,
			$this->item,
			$this->count
		);

		$lightbox_caption = do_shortcode( $sanitized_caption );
		if ( in_array( $lightbox_theme, [ 'base_dark', 'base_light' ], true ) ) {
			$caption_limit = apply_filters(
				'envira_gallery_output_lightbox_caption_limit',
				100,
				$this->data,
				$this->item_id,
				$this->item,
				$this->count
			);
			if ( strlen( $lightbox_caption ) > $caption_limit ) {
				$lightbox_caption = substr( $lightbox_caption, 0, strrpos( substr( $lightbox_caption, 0, $caption_limit ), ' ' ) ) . '...';
			}
		}

		$lightbox_caption = apply_filters(
			'envira_gallery_output_lightbox_caption',
			envira_santitize_lightbox_caption( $lightbox_caption, 'shortcode' ),
			$this->data,
			$this->item_id,
			$this->item,
			$this->count
		);

		// == Wrapper Attributes ==
		$wrapper_id      = sanitize_html_class( "envira-gallery-item-$this->item_id" );
		$wrapper_classes = Shortcode_Utils::classnames(
			apply_filters(
				'envira_gallery_output_item_classes',
				$this->get_item_wrapper_classes(),
				$this->item,
				$this->count,
				$this->data
			)
		);

		// Wrapper Styles.
		$padding       = absint( round( $gutter / 2 ) );
		$wrapper_style = "padding-left: {$padding}px; padding-bottom: {$margin}px; padding-right: {$padding}px;";

		$wrapper_extra_attrs = apply_filters( 'envira_gallery_output_item_attr', '', $this->item_id, $this->item, $this->data, $this->count );

		// Schema.org microdata ( Itemscope, etc. ) interferes with Google+ Sharing... so we are adding this via filter rather than hardcoding.
		$wrapper_schema = apply_filters(
			'envira_gallery_output_schema_microdata_imageobject',
			'itemscope itemtype="http://schema.org/ImageObject"',
			$this->data
		);

		// == Boxes ==
		$boxes = '';
		foreach ( [ 'top-left', 'top-right', 'bottom-left', 'bottom-right' ] as $box_position ) {
			$box  = "<div class=\"envira-gallery-position-overlay envira-gallery-$box_position\">";
			$box  = apply_filters(
				'envira_gallery_output_dynamic_position',
				$box,
				$this->item_id,
				$this->item,
				$this->data,
				$this->count,
				$box_position
			);
			$box .= '</div>';

			$boxes .= $box;
		}

		// == Image Link ==
		$create_link = ! empty( $this->item['link'] ) && ( $gallery_link_enabled || $lightbox_enabled );

		if ( $this->is_mobile && ! $gallery_link_enabled && ! $lightbox_enabled ) {
			$create_link = false;
		}

		$create_link = apply_filters(
			'envira_gallery_create_link',
			$create_link,
			$this->data,
			$this->item_id,
			$this->item,
			$this->count,
			$this->is_mobile
		);

		$link_open_tag  = '';
		$link_close_tag = '';

		if ( $create_link ) {
			$link_open_tag  = $this->get_link_open_tag(
				$lightbox_caption,
				$lightbox_title
			);
			$link_close_tag = '</a>';
		}

		$image_tag = $this->get_image_tag(
			$create_link,
			$sanitized_title,
			$sanitized_caption,
			$lightbox_caption,
			$lightbox_title
		);

		$image_tag = apply_filters( 'envira_gallery_output_image', $image_tag, $this->item_id, $this->item, $this->data, $this->count );

		$output_caption = $this->gallery_image_caption_titles();
		$output_caption = apply_filters( 'envira_gallery_output_caption', $output_caption, $this->item_id, $this->item, $this->data, $this->count );

		// ====== Item Markup ======
		$markup  = "<div id=\"$wrapper_id\" class=\"$wrapper_classes\" style=\"$wrapper_style\" $wrapper_extra_attrs $wrapper_schema>";
		$markup .= '<div class="envira-gallery-item-inner">';
		$markup  = apply_filters( 'envira_gallery_output_before_link', $markup, $this->item_id, $this->item, $this->data, $this->count );
		$markup .= $boxes;
		$markup .= $link_open_tag;
		$markup  = apply_filters( 'envira_gallery_output_before_image', $markup, $this->item_id, $this->item, $this->data, $this->count );
		$markup .= $image_tag;
		$markup  = apply_filters( 'envira_gallery_output_after_image', $markup, $this->item_id, $this->item, $this->data, $this->count );
		$markup .= $link_close_tag;
		$markup .= apply_filters( 'envira_gallery_output_after_link', $output_caption, $this->item_id, $this->item, $this->data, $this->count );
		$markup .= '</div></div>';

		return $markup;
	}

	/**
	 * Helper method for adding custom gallery classes.
	 *
	 * @since 1.8.8
	 *
	 * @return array Item wrapper classes
	 */
	protected function get_item_wrapper_classes() {
		$classes = [
			'envira-gallery-item',
			"envira-gallery-item-$this->count",
		];

		if ( isset( $this->item['video_in_gallery'] ) && 1 === $this->item['video_in_gallery'] ) {
			$classes[] = 'envira-video-in-gallery';
		}

		// If lazy load exists, add that.
		if ( Shortcode_Utils::is_lazy_loading( $this->data ) ) {
			$classes[] = 'envira-lazy-load';
		}

		return $classes;
	}

	/**
	 * Generate link open tag.
	 *
	 * @param string $lightbox_caption Lightbox caption.
	 * @param string $lightbox_title Lightbox title.
	 *
	 * @return string
	 */
	private function get_link_open_tag( $lightbox_caption, $lightbox_title ) {
		// == All needed config ==
		$crop_position           = envira_get_config( 'crop_position', $this->data );
		$instagram_link          = envira_get_config( 'instagram_link', $this->data );
		$mobile_thumbnails_width = envira_get_config( 'mobile_thumbnails_width', $this->data );
		$lightbox_enabled        = envira_get_config( 'lightbox_enabled', $this->data );
		$type                    = envira_get_config( 'type', $this->data );
		$thumb_dimensions        = envira_get_thumbnail_dimensions( $this->data );

		// == Link Attributes ==
		$gallery_link_class = $lightbox_enabled ? 'envira-gallery-link' : 'envira-gallery-link-disabled';
		if ( 'instagram' === $type ) {
			$gallery_link_class = $lightbox_enabled && empty( $instagram_link ) ? 'envira-gallery-link' : 'envira-gallery-link-disabled';
		}

		$link_class = esc_attr( "envira-gallery-$this->unique_id $gallery_link_class" );

		$link_target = ! empty( $this->item['target'] ) ? $this->item['target'] : '_self';
		$link_target = isset( $this->item['link_new_window'] ) && $this->item['link_new_window'] ? '_blank' : $link_target;

		$link_href = esc_url(
			apply_filters(
				'envira_gallery_link_href',
				$this->item['link'],
				$this->data,
				$this->item_id,
				$this->item,
				$this->count,
				$this->is_mobile
			)
		);

		$link_title = wp_strip_all_tags( htmlspecialchars( str_replace( '<', '&lt;', $this->item['title'] ) ) );

		$link_schema = apply_filters(
			'envira_gallery_output_schema_microdata_itemprop_contenturl',
			'itemprop="contentUrl"',
			$this->data,
			$this->item_id,
			$this->item,
			$this->count
		);

		$link_extra_attrs = apply_filters( 'envira_gallery_output_link_attr', '', $this->item_id, $this->item, $this->data, $this->count );

		// Data Attributes.
		$image_src_retina_lb = (string) apply_filters(
			'envira_gallery_output_item_src_retina_lightbox',
			envira_get_image_src( $this->item_id, $this->item, $this->data, false, true, 'ligthbox_image_size' ),
			$this->item,
			$this->item_id,
			$this->data,
			$this->count,
			$this->is_mobile
		);

		$thumb = envira_resize_image(
			$this->item['src'],
			$thumb_dimensions['width'],
			$thumb_dimensions['height'],
			true,
			$crop_position,
			100,
			false,
			$this->data
		);

		if ( is_wp_error( $thumb ) ) {
			// If there's a WP_Error (maybe a 'No image URL specified for cropping.'), fall back to $this->item['thumb'].
			$thumb = ( isset( $this->item['thumb'] ) ) ? $this->item['thumb'] : $this->item['src'];
		}

		$mobile_thumb = null;
		if ( $mobile_thumbnails_width ) {
			$mobile_thumb = envira_resize_image(
				$this->item['src'],
				$thumb_dimensions['width'],
				$thumb_dimensions['height'],
				true,
				$crop_position,
				100,
				false,
				$this->data
			);
		}

		$thumb = esc_attr( $this->is_mobile && $mobile_thumb && ! is_wp_error( $mobile_thumb ) ? $mobile_thumb : $thumb );

		$link_data_array = [
			"data-caption=\"$lightbox_caption\"",
			"data-envira-item-id=\"$this->item_id\"",
			"data-envira-retina=\"$image_src_retina_lb\"",
			"data-thumb=\"$thumb\"",
			"data-title=\"$lightbox_title\"",
		];

		// Conditional link data.
		if ( 'external' !== $this->item['link_type'] ) {
			$link_data_array[] = "data-envirabox='$this->unique_id'";
		}

		if ( isset( $this->item['src'] ) && 'instagram' === $type ) {
			$link_data_array[] = "data-src='{$this->item['src']}'";
		}

		if ( isset( $this->item['src'] ) && isset( $this->item['video']['type'] ) ) {
			$link_data_array[] = "data-video-placeholder='{$this->item['src']}'";
		}

		if ( '_self' !== $link_target ) {
			$link_data_array[] = "target=\"$link_target\"";
		}

		$link_data = implode( ' ', $link_data_array );

		// Merge data, extra and schema attrs for readability.
		$link_attrs = "$link_data $link_schema" . ( empty( $link_extra_attrs ) ? '' : "  $link_extra_attrs" );

		return "<a class=\"$link_class\" href=\"$link_href\" title=\"$link_title\" $link_attrs>";
	}

	/**
	 * Generate image tag.
	 *
	 * @param bool   $create_link Should create link option.
	 * @param string $sanitized_title Sanitized title.
	 * @param string $sanitized_caption Sanitized caption.
	 * @param string $lightbox_caption Lightbox caption.
	 * @param string $lightbox_title Lightbox title.
	 *
	 * @return string
	 */
	private function get_image_tag( $create_link, $sanitized_title, $sanitized_caption, $lightbox_caption, $lightbox_title ) {
		// == All needed config ==
		$placeholder                    = wp_get_attachment_image_src( $this->item_id, 'medium' );
		$columns                        = envira_get_config( 'columns', $this->data );
		$justified_gallery_theme        = envira_get_config( 'justified_gallery_theme', $this->data );
		$justified_gallery_theme_detail = envira_get_config( 'justified_gallery_theme_detail', $this->data );
		$lazy_loading                   = Shortcode_Utils::is_lazy_loading( $this->data );
		$create_mobile                  = envira_get_config( 'mobile', $this->data ) && $this->is_mobile;
		$crop                           = envira_get_config( 'crop', $this->data );
		$crop_width                     = envira_get_config( 'crop_width', $this->data );
		$crop_height                    = envira_get_config( 'crop_height', $this->data );
		$image_size                     = envira_get_config( 'image_size', $this->data );
		$mobile_width                   = envira_get_config( 'mobile_width', $this->data );
		$mobile_height                  = envira_get_config( 'mobile_height ', $this->data );
		$type                           = envira_get_config( 'type', $this->data );

		$is_instagram = 'instagram' === $type;

		$gallery_theme        = 0 === $columns ? " envira-$justified_gallery_theme" : '';
		$gallery_theme_suffix = 'hover' === $justified_gallery_theme_detail ? '-hover' : '';

		// Schema.org microdata ( itemprop, etc. ) interferes with Google+ Sharing... so we are adding this via filter rather than hardcoding.
		$img_schema = apply_filters(
			'envira_gallery_output_schema_microdata_itemprop_thumbnailurl',
			'itemprop="thumbnailUrl"',
			$this->data,
			$this->item_id,
			$this->item,
			$this->count
		);

		// Check if user has lazy loading on - if so, we add the css class.
		$envira_lazy_load = $lazy_loading ? ' envira-lazy' : '';

		$image_src_retina = esc_url(
			apply_filters(
				'envira_gallery_output_item_src_retina',
				envira_get_image_src( $this->item_id, $this->item, $this->data, false, true ),
				$this->item,
				$this->item_id,
				$this->data,
				$this->count,
				$this->is_mobile
			)
		);

		$output_src = false;
		if ( $create_mobile && ! empty( $this->item['src'] ) ) {
			$output_src = $this->item['src'];
		} elseif ( $crop || $image_size ) { // the user has selected the image to be cropped.
			$output_src = $this->src;
		}

		$output_src = esc_attr( $output_src );

		$output_width = false;
		if ( $crop && $crop_height && $crop_width && 'full' !== $image_size ) {
			$output_width = $crop_width;
		} elseif ( $create_mobile && $mobile_width ) {
			$output_width = $mobile_width;
		} elseif ( $is_instagram && strpos( $this->src, 'cdninstagram' ) !== false ) {
			// Try to extract the size from the url itself.
			$output_width = empty( $this->item['width'] ) ? '150' : $this->item['width'];

			if ( strpos( $this->src, '640x640' ) ) {
				$output_width = '640';
			}
		} elseif ( ! empty( $placeholder[1] ) ) {
			$output_width = $placeholder[1];
		} elseif ( ! empty( $this->item['width'] ) ) {
			$output_width = $this->item['width'];
		}

		$output_height = false;
		if ( $crop && $crop_height && $crop_width && 'full' !== $image_size ) {
			$output_height = $crop_height;
		} elseif ( $create_mobile && $mobile_height ) {
			$output_height = $mobile_height;
		} elseif ( $is_instagram && strpos( $this->src, 'cdninstagram' ) !== false ) {
			// Try to extract the size from the url itself.
			$output_height = empty( $this->item['height'] ) ? '150' : $this->item['height'];

			if ( strpos( $this->src, '640x640' ) ) {
				$output_height = '640';
			}
		} elseif ( ! empty( $placeholder[2] ) ) {
			$output_height = $placeholder[2];
		} elseif ( ! empty( $this->item['height'] ) ) {
			$output_height = $this->item['height'];
		}

		/* add filters for width and height, primarily so dynamic can add width and height */
		$output_width  = apply_filters(
			'envira_gallery_output_width',
			$output_width,
			$this->item_id,
			$this->item,
			$this->data,
			$this->count,
			$output_src
		);
		$output_height = apply_filters(
			'envira_gallery_output_height',
			$output_height,
			$this->item_id,
			$this->item,
			$this->data,
			$this->count,
			$output_src
		);

		$img_sizes = $this->get_img_tag_dimensions( $output_width, $output_height, $image_size, $crop_width, $crop_height );

		$img_width  = $img_sizes['width'];
		$img_height = $img_sizes['height'];

		// set tab index to -1 if there's a link, which was already set to 0.
		$tabindex = $create_link ? - 1 : 0;

		$srcset = $image_src_retina && envira_is_image( $image_src_retina )
			? "$image_src_retina, $image_src_retina 2x"
			: $output_src;

		if ( $envira_lazy_load ) {
			$srcset = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
		}

		$srcset = apply_filters(
			'envira_gallery_output_item_srcset',
			$srcset,
			$image_src_retina,
			$this->item,
			$this->item_id,
			$this->data,
			$this->count,
			$this->is_mobile
		);

		$get_img_wrapper = $this->get_img_wrapper( $img_height, $img_width );

		$img_id    = sanitize_html_class( "envira-gallery-image-$this->item_id" );
		$img_class = "envira-gallery-image envira-gallery-image-$this->count $gallery_theme $gallery_theme_suffix $envira_lazy_load";
		$img_alt   = esc_attr( $this->item['alt'] );
		$img_title = wp_strip_all_tags( esc_attr( $this->item['title'] ) );

		$envira_index = $this->count - 1;

		$img_attrs_array = [
			"class=\"$img_class\"",
			"height=\"$img_height\"",
			"id=\"$img_id\"",
			"srcset=\"$srcset\"",
			"tabindex=\"$tabindex\"",
			"title=\"$img_title\"",
			"width=\"$img_width\"",
			"data-caption=\"$lightbox_caption\"",
			"data-envira-gallery-id=\"$this->unique_id\"",
			"data-envira-index=\"$envira_index\"",
			"data-envira-item-id=\"$this->item_id\"",
			"data-envira-src=\"$output_src\"",
			"data-envira-srcset=\"$output_src 400w, $output_src 2x\"",
			"data-title=\"$lightbox_title\"",
			$img_schema,
			apply_filters( 'envira_gallery_output_image_attr', '', $this->item_id, $this->item, $this->data, $this->count ),
		];

		if ( 'external' !== $this->item['link_type'] ) {
			$img_attrs_array[] = "data-envirabox=\"$this->unique_id\"";
		}

		$img_attrs_array = array_merge(
			$img_attrs_array,
			$this->img_extra_attrs( $output_width, $output_height, $sanitized_title, $sanitized_caption )
		);

		$img_attrs = implode( ' ', $img_attrs_array );

		$output_item  = $get_img_wrapper['start'];
		$output_item .= "<img src=\"$output_src\" alt=\"$img_alt\" $img_attrs />";
		$output_item .= $get_img_wrapper['end'];

		return $output_item;
	}

	/**
	 * Get image sizes.
	 *
	 * @param string $output_width Output width.
	 * @param string $output_height Output height.
	 * @param string $image_size Image size.
	 * @param string $crop_width Crop width.
	 * @param string $crop_height Crop height.
	 *
	 * @return array{width: numeric, height: numeric}
	 */
	protected function get_img_tag_dimensions( $output_width, $output_height, $image_size, $crop_width, $crop_height ) {
		$img_width  = $output_width;
		$img_height = $output_height;

		if ( 'default' === $image_size ) {
			$img_width  = $crop_width;
			$img_height = $crop_height;
		} elseif ( 'full' === $image_size ) {
			$src = apply_filters(
				'envira_gallery_retina_image_src',
				wp_get_attachment_image_src( $this->item_id, 'full' ),
				$this->item_id,
				$this->item,
				$this->data
			);

			$img_width  = $src[1];
			$img_height = $src[2];
		}

		return [
			'width'  => $img_width,
			'height' => $img_height,
		];
	}

	/**
	 * Add extra wrappers around img tag.
	 *
	 * @param numeric $img_height Image tag height attr.
	 * @param numeric $img_width Image tag width attr.
	 *
	 * @return array{start: string, end: string}
	 */
	protected function get_img_wrapper( $img_height, $img_width ) {
		$start = '';
		$end   = '';
		if ( Shortcode_Utils::is_lazy_loading( $this->data ) ) {
			$padding_bottom = 100;
			if ( $img_height > 0 && $img_width > 0 ) {
				$padding_bottom = ( $img_height / $img_width ) * 100;
			}
			$lazy_wrapper_data = "data-envira-changed=\"false\" data-width=\"$img_width\" data-height=\"$img_height\"";
			$start             = "<div class=\"envira-lazy\" style=\"padding-bottom: $padding_bottom%;\" $lazy_wrapper_data>";
			$end               = '</div>';
		}

		return [
			'start' => $start,
			'end'   => $end,
		];
	}

	/**
	 * Extra attrs to add on extension class.
	 *
	 * @param string $output_width Output width.
	 * @param string $output_height Output height.
	 * @param string $sanitized_title Sanitized title.
	 * @param string $sanitized_caption Sanitized caption.
	 *
	 * @return array
	 */
	protected function img_extra_attrs( $output_width, $output_height, $sanitized_title, $sanitized_caption ) {
		return [];
	}

	/**
	 * Allow users to add a title or caption under an image for legacy galleries
	 *
	 * @since 1.7.0
	 *
	 * @return string Output HTML todo rename as caption markup
	 */
	protected function gallery_image_caption_titles() {
		// get the gallery theme.
		$gallery_theme     = envira_get_config( 'gallery_theme', $this->data );
		$title_caption     = '';
		$allowed_html_tags = apply_filters(
			'envira_gallery_image_caption_allowed_html_tags',
			[
				'a'      => [
					'href'   => [],
					'title'  => [],
					'class'  => [],
					'target' => [],
				],
				'br'     => [],
				'em'     => [],
				'strong' => [],
				'p'      => [],
				'strike' => [],
				'object' => [],
			],
			$this->item_id,
			$this->item,
			$this->data,
			$this->count
		);

		$title_caption_array = $this->get_title_caption();
		foreach ( array_keys( $title_caption_array ) as $type ) {
			if ( ! empty( $title_caption_array[ $type ] ) ) {
				$classes        = "envira-$type envira-gallery-captioned-text $type-$this->item_id";
				$title_caption .= "<span class='$classes'>" . wp_kses( $title_caption_array[ $type ], $allowed_html_tags ) . '</span>';
			}
		}

		$classes = "envira-gallery-captioned-data envira-gallery-captioned-data-$gallery_theme";
		$output  = "<div class=\"$classes\">$title_caption</div>";

		return apply_filters( 'envira_gallery_image_caption_titles', nl2br( $output ), $this->item_id, $this->item, $this->data, $this->count );
	}

	/**
	 * Wrapper for obtaining title and caption from options:
	 * additional_copy_title
	 * additional_copy_caption
	 * gallery_column_title_caption
	 *
	 * @return array{title: string, caption: string}
	 */
	protected function get_title_caption() {
		// Set defaults as empty.
		$title_caption = [
			'title'   => '',
			'caption' => '',
		];

		foreach ( array_keys( $title_caption ) as $type ) {
			if ( ! empty( $this->item[ $type ] ) ) {
				$additional_copy = envira_get_config( "additional_copy_$type", $this->data );
				$column_title    = envira_get_config( 'gallery_column_title_caption', $this->data );
				if ( 1 === $additional_copy || in_array( $column_title, [ $type, 'title_caption' ], true ) ) {
					$title_caption[ $type ] = $this->item[ $type ];
				}
			}
		}

		return $title_caption;
	}
}
