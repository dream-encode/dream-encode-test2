<?php
/**
 * Class Dream_Encode_Test_Core_API
 *
 * @since 1.0.0
 */

namespace Dream_Encode\Test\Core\RestApi;

use Dream_Encode\Test\Core\Abstracts\Dream_Encode_Test_Abstract_API;

defined( 'ABSPATH' ) || exit;

/**
 * Class Dream_Encode_Test_Core_API
 *
 * @since 1.0.0
 */
class Dream_Encode_Test_Core_API extends Dream_Encode_Test_Abstract_API {
	/**
	 * Includes files
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function rest_api_includes() {
		parent::rest_api_includes();

		$path_version = 'includes/rest-api' . DIRECTORY_SEPARATOR . $this->version . DIRECTORY_SEPARATOR . 'frontend';

		include_once DREAM_ENCODE_TEST_PLUGIN_PATH . $path_version . '/class-dream-encode-test-rest-user-controller.php';
	}

	/**
	 * Register all routes.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function rest_api_register_routes() {
		$controllers = array(
			'Dream_Encode_Test_REST_User_Controller',
		);

		$this->controllers = $controllers;

		parent::rest_api_register_routes();
	}
}
