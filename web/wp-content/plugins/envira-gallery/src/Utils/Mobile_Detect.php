<?php
/**
 * Mobile Detect Library
 *
 * @deprecated 1.8.5.4
 *
 * @package Envira Gallery
 */

namespace Envira\Utils;

/**
 * Mobile Detect Class.
 */
class Mobile_Detect {
	/**
	 * Replacement function for Legecy Class
	 *
	 * @return boolean
	 */
	public function isMobile(){ // @codingStandardsIgnoreLine
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$regex = '/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i';
			return preg_match( $regex, sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) );
		}

		return false;
	}
}
