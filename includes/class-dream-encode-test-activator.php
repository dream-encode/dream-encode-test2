<?php
/**
 * Fired during plugin activation.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Dream_Encode\Test
 * @subpackage Dream_Encode\Test/includes
 */

namespace Dream_Encode\Test\Core;

use Dream_Encode\Test\Core\Upgrade\Dream_Encode_Test_Upgrader;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dream_Encode\Test
 * @subpackage Dream_Encode\Test/includes
 * @author     David Baumwald <david@dream-encode.com>
 */
class Dream_Encode_Test_Activator {
	/**
	 * Activator.
	 *
	 * Runs on plugin activation.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function activate() {
		Dream_Encode_Test_Upgrader::install();
	}
}
