<?php
/**
 * Grid layout class.
 *
 * @since ??
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

namespace Envira\Frontend\Gallery_Markup\Layouts;

use Envira\Frontend\Gallery_Markup\Base;

/**
 * Grid layout class.
 */
class Grid extends Base {
	/**
	 * Generate wrapper classes
	 *
	 * @since 1.9.0
	 */
	protected function get_wrapper_classes() {
		$classes   = parent::get_wrapper_classes();
		$classes[] = 'envira-layout-grid';
		$classes[] = 'envira-gallery-theme-' . envira_get_config( 'gallery_theme', $this->data );

		return $classes;
	}
}
