<?php
/**
 * Variables shared in Shortcode and Base classes.
 *
 * @since ??
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

namespace Envira\Frontend\Gallery_Markup;

// To be used in Base and Shortcode classes.
trait Vars {
	/**
	 * Is mobile
	 *
	 * @var bool
	 */
	protected $is_mobile;

	/**
	 * Current gallery data
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Current gallery id: post id, gallery post id, tags-names, etc.
	 *
	 * Also present in $gallery_data['gallery_id']
	 *
	 * @var string
	 */
	protected $gallery_id;

	/**
	 * Current gallery id from where to get options.
	 *
	 * Value of $gallery_data['dynamic_id'] if present or else $gallery_data['gallery_id'].
	 *
	 * @var int
	 */
	protected $options_id;

	/**
	 * Current gallery unique id used on main div.
	 *
	 * Also present in $gallery_data['id']
	 *
	 * @var string
	 */
	protected $unique_id;
}
