<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Dream_Encode_Test
 * @subpackage Dream_Encode_Test/includes
 */

namespace Dream_Encode\Test\Core;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Dream_Encode_Test
 * @subpackage Dream_Encode_Test/includes
 * @author     David Baumwald <david@dream-encode.com>
 */
class Dream_Encode_Test_I18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'dream-encode-test',
			false,
			DREAM_ENCODE_TEST_PLUGIN_PATH . 'languages/'
		);
	}
}
