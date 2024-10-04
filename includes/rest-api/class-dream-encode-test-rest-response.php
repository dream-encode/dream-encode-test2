<?php
/**
 * Class Dream_Encode_Test_REST_Response.
 *
 * @since 1.0.0
 */

namespace Dream_Encode\Test\Core\RestApi;

use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 * Class Dream_Encode_Test_REST_Response
 *
 * @since 1.0.0
 */
class Dream_Encode_Test_REST_Response {
	/**
	 * Status.
	 *
	 * @var string.
	 */
	public $status = 'error';

	/**
	 * Message.
	 *
	 * @var string .
	 */
	public $message = '';

	/**
	 * Extra data
	 *
	 * @var mixed
	 */
	public $data;

	/**
	 * Success
	 *
	 * @var bool
	 */
	public $success;

	/**
	 * MMEWOA_REST_Response constructor.
	 */
	public function __construct() {
		$this->data = new stdClass();
	}
}
