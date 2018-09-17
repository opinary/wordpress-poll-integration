<?php

namespace Opinary\PollIntegration;

require_once OPINARY_PLUGIN_PATH . 'includes/class-invalid-tag-attribute-exception.php';

/**
 *
 */
class Tag_Attributes {
	/** @var string */
	protected $customer = '';

	/** @var string */
	protected $poll_id = '';

	/** @var bool */
	protected $is_automated;

	/**
	 * @param string $customer
	 * @param string $poll_id
	 * @param bool $is_automated
	 *
	 * @throws Invalid_Tag_Attribute_Exception
	 */
	public function __construct( $customer, $poll_id, $is_automated = false ) {
		if ( ! is_string( $customer ) || empty( $customer ) ) {
			throw new Invalid_Tag_Attribute_Exception(
				__( 'Invalid or missing attribute "customer"', 'opinary_poll_integration' )
			);
		}

		if ( false === $is_automated && ( ! is_string( $poll_id ) || empty( $poll_id ) ) ) {
			throw new Invalid_Tag_Attribute_Exception(
				__( 'Invalid or missing attribute "poll"', 'opinary_poll_integration' )
			);
		}

		$this->customer     = $customer;
		$this->poll_id      = $poll_id;
		$this->is_automated = $is_automated;
	}

	/**
	 * @return string
	 */
	public function get_customer() {
		return $this->customer;
	}

	/**
	 * @return string
	 */
	public function get_poll_id() {
		return $this->poll_id;
	}

	/**
	 * @return bool
	 */
	public function is_automated() {
		return $this->is_automated;
	}
}
