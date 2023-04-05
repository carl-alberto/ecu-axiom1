<?php
/**
 * Envira Exception Class.
 *
 * @since 1.9.2
 *
 * @package Envira Gallery
 */

namespace Envira\Utils;

/**
 * Envira Exception Class
 */
class Exception extends \Exception {

	/**
	 * Exception Type.
	 *
	 * @since 1.9.2
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * Exception Status
	 *
	 * @since 1.9.2
	 *
	 * @var string
	 */
	protected $status;

	/**
	 * Class Constructor
	 *
	 * @param string $message error message.
	 * @param string $type error type.
	 * @param string $status errror status.
	 *
	 * @throws InvalidArgumentException First two arguments must be strings.
	 */
	public function __construct( $message, $type = null, $status = null ) {
		if ( ! is_string( $message ) || ( $type && ! is_string( $type ) ) ) {
			throw new InvalidArgumentException(
				'First two arguments must be strings'
			);
		}

		$this->type   = $type;
		$this->status = $status;

		parent::__construct( $message );

	}

	/**
	 * Helper Method to get exception type.
	 *
	 * @since 1.9.2
	 *
	 * @return string exception type.
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Helper Method to get exception status.
	 *
	 * @since 1.9.2
	 *
	 * @return string exception status.
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Helper Method to get exception message.
	 *
	 * @since 1.9.2
	 *
	 * @return string exception message.
	 */
	public function get_message() {
		return $this->getMessage();
	}

}
