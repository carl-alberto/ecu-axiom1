<?php
/**
 * Envira Image Size
 *
 * @package Envira Gallery
 */

namespace Envira\Compress;

/**
 * Envira Image Size
 */
class Image_Size {

	/**
	 * Filename
	 *
	 * @var string
	 */
	public $filename = null;

	/**
	 * MetaData
	 *
	 * @var array
	 */
	public $metadata = [];

	/**
	 * Class Construct
	 *
	 * @since 1.9.2
	 *
	 * @param string $filename Filename.
	 */
	public function __construct( $filename = null ) {
		$this->filename = $filename;
	}

}
