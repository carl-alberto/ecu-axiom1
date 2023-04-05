<?php
/**
 * Automatic layout item class.
 *
 * @since ??
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

namespace Envira\Frontend\Gallery_Markup\Layouts;

use Envira\Frontend\Gallery_Markup\Item;

/**
 * Automatic layout item.
 */
class Automatic_Item extends Item {
	/**
	 * Automatic layout displays the caption and title on hover.
	 *
	 * @return string
	 */
	protected function gallery_image_caption_titles() {
		return '';
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
		return [
			'width'  => $crop_width,
			'height' => $crop_height,
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
		$caption_title_array = $this->get_title_caption( $this->item );
		$caption_array       = [];

		if ( $caption_title_array['title'] ) {
			$caption_array[] = $sanitized_title;
		}

		if ( $caption_title_array['caption'] ) {
			$caption_array[] = $sanitized_caption;
		}

		$automatic_caption = implode( ' - ', $caption_array );
		$img_attrs_array   = [];
		$img_attrs_array[] = "data-automatic-caption=\"$automatic_caption\"";
		$img_attrs_array[] = "data-envira-height=\"$output_height\"";
		$img_attrs_array[] = "data-envira-width=\"$output_width\"";

		return $img_attrs_array;
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
				$additional_copy = envira_get_config( "additional_copy_automatic_$type", $this->data );
				$column_title    = envira_get_config( 'gallery_automatic_title_caption', $this->data );
				if ( 1 === $additional_copy || in_array( $column_title, [ $type, 'title_caption' ], true ) ) {
					$title_caption[ $type ] = $this->item[ $type ];
				}
			}
		}

		return $title_caption;
	}

	/**
	 * Add extra wrappers around img tag.
	 *
	 * Automatic does not need any wrapper.
	 *
	 * @param numeric $img_height Image tag height attr.
	 * @param numeric $img_width Image tag width attr.
	 *
	 * @return array{'start': string, 'end': string}
	 */
	protected function get_img_wrapper( $img_height, $img_width ) {
		return [
			'start' => '',
			'end'   => '',
		];
	}
}
