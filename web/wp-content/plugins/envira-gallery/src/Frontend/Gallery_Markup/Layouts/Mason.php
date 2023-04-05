<?php
/**
 * Mason layout class.
 *
 * @since ??
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

namespace Envira\Frontend\Gallery_Markup\Layouts;

use Envira\Frontend\Gallery_Markup\Base;

/**
 * Mason layout class.
 */
class Mason extends Base {
	/**
	 * Generate wrapper classes
	 *
	 * @since 1.9.0
	 */
	protected function get_wrapper_classes() {
		$classes   = parent::get_wrapper_classes();
		$classes[] = 'envira-layout-mason';
		$classes[] = 'envira-gallery-theme-' . envira_get_config( 'gallery_theme', $this->data );

		return $classes;
	}

	/**
	 * Get container class. Method intended to be extended.
	 *
	 * @return array
	 */
	protected function get_container_classes() {
		$classes   = parent::get_container_classes();
		$classes[] = 'enviratope';

		return $classes;
	}

	/**
	 * Get item config.
	 *
	 * @param array $config Item config.
	 *
	 * @return Mason_Item
	 */
	protected function get_item_object( $config ) {
		return new Mason_Item( $config );
	}
}
