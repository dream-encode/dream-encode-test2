<?php
/**
 * Class Dream_Encode_Test_REST_Example_Controller
 */

namespace Dream_Encode\Test\Core\RestApi\V1\Frontend;

use Exception;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use Dream_Encode\Test\Core\RestApi\Dream_Encode_Test_REST_Response;
use Dream_Encode\Test\Core\Abstracts\Dream_Encode_Test_Abstract_REST_Controller;


/**
 * Class Dream_Encode_Test_REST_Example_Controller
 */
class Dream_Encode_Test_REST_Example_Controller extends Dream_Encode_Test_Abstract_REST_Controller {
	/**
	 * Dream_Encode_Test_REST_Example_Controller constructor.
	 */
	public function __construct() {
		$this->namespace = 'dream_encode/test/v1';
		$this->rest_base = 'example';
	}

	/**
	 * Register routes API
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function register_routes() {
		$this->routes = array(
			'example' => array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'example_method' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			),
		);

		parent::register_routes();
	}

	/**
	 * Validate user permissions.
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	public function permission_callback() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Example method.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request  $request  Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function example_method( $request ) {
		$response = new Dream_Encode_Test_REST_Response();

		$success = false;

		try {
			$success = true;

			$response->status = '100';
			$response->data   = array();
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		$response->success = $success;
		$response->status  = $success ? '100' : '401';

		return rest_ensure_response( $response );
	}
}
