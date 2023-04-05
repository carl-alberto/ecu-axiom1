<?php
/**
 * Automatic layout class.
 *
 * @since ??
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

namespace Envira\Frontend\Gallery_Markup\Layouts;

use Envira\Frontend\Gallery_Markup\Base;

/**
 * Automatic layout class.
 */
class Automatic extends Base {
	/**
	 * Generate wrapper classes
	 *
	 * @since 1.9.0
	 */
	protected function get_wrapper_classes() {
		$classes   = parent::get_wrapper_classes();
		$classes[] = 'envira-layout-automatic';

		return $classes;
	}

	/**
	 * Get container class. Method intended to be extended.
	 *
	 * @return array
	 */
	protected function get_container_classes() {
		$classes   = parent::get_container_classes();
		$classes[] = 'envira-gallery-justified-public';

		return $classes;
	}

	/**
	 * Get item config.
	 *
	 * @param array $config Item config.
	 *
	 * @return Automatic_Item
	 */
	protected function get_item_object( $config ) {
		return new Automatic_Item( $config );
	}
}
