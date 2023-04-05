<?php
/**
 * Mason item layout class.
 *
 * @since ??
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

namespace Envira\Frontend\Gallery_Markup\Layouts;

use Envira\Frontend\Gallery_Markup\Item;

/**
 * Mason item class.
 */
class Mason_Item extends Item {
	/**
	 * Helper method for adding custom gallery classes.
	 *
	 * @since 1.8.8
	 *
	 * @return array Item wrapper classes
	 */
	protected function get_item_wrapper_classes() {
		$classes   = parent::get_item_wrapper_classes();
		$classes[] = 'enviratope-item';

		return $classes;
	}
}
