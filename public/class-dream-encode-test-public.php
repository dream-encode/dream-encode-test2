<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Dream_Encode_Test
 * @subpackage Dream_Encode_Test/public
 */

namespace Dream_Encode\Test\Frontend;

use Dream_Encode\Test\Core\Upgrade\Dream_Encode_Test_Upgrader;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dream_Encode_Test
 * @subpackage Dream_Encode_Test/public
 * @author     David Baumwald <david@dream-encode.com>
 */
class Dream_Encode_Test_Public {

	/**
	 * Example function.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $param  First function parameter.
	 * @return string
	 */
	public function example_function( $param ) {
		return $param;
	}
}
