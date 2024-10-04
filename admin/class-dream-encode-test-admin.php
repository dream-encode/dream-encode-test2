<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Dream_Encode_Test
 * @subpackage Dream_Encode_Test/admin
 */

namespace Dream_Encode\Test\Admin;

use WP_Screen;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dream_Encode_Test
 * @subpackage Dream_Encode_Test/admin
 * @author     David Baumwald <david@dream-encode.com>
 */
class Dream_Encode_Test_Admin {

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function enqueue_styles() {
		if ( ! dream_encode_test_admin_current_screen_has_enqueued_assets() ) {
			return;
		}

		$current_screen = get_current_screen();

		if ( ! $current_screen instanceof WP_Screen ) {
			return;
		}

		$screens_to_assets = dream_encode_test_get_admin_screens_to_assets();

		foreach ( $screens_to_assets as $screen => $assets ) {
			if ( $current_screen->id !== $screen ) {
				continue;
			}

			foreach ( $assets as $asset ) {
				$asset_base_url = DREAM_ENCODE_TEST_PLUGIN_URL . 'admin/';

				$asset_file = include( DREAM_ENCODE_TEST_PLUGIN_PATH . "admin/assets/dist/js/admin-{$asset['name']}.min.asset.php" );

				wp_enqueue_style(
					"dream-encode-test-admin-{$asset['name']}",
					$asset_base_url . "assets/dist/css/admin-{$asset['name']}.min.css",
					$asset_file['dependencies'],
					$asset_file['version'],
					'all'
				);
			}
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( ! dream_encode_test_admin_current_screen_has_enqueued_assets() ) {
			return;
		}

		$current_screen = get_current_screen();

		if ( ! $current_screen instanceof WP_Screen ) {
			return;
		}

		$screens_to_assets = dream_encode_test_get_admin_screens_to_assets();

		foreach ( $screens_to_assets as $screen => $assets ) {
			if ( $current_screen->id !== $screen ) {
				continue;
			}

			foreach ( $assets as $asset ) {
				$asset_base_url = DREAM_ENCODE_TEST_PLUGIN_URL . 'admin/';

				$asset_file = include( DREAM_ENCODE_TEST_PLUGIN_PATH . "admin/assets/dist/js/admin-{$asset['name']}.min.asset.php" );

				wp_register_script(
					"dream-encode-test-admin-{$asset['name']}",
					$asset_base_url . "assets/dist/js/admin-{$asset['name']}.min.js",
					$asset_file['dependencies'],
					$asset_file['version'],
					array(
						'in_footer' => true,
					)
				);

				if ( ! empty( $asset['localization'] ) ) {
					wp_localize_script( "dream-encode-test-admin-{$asset['name']}", 'det', $asset['localization'] );
				}

				wp_enqueue_script( "dream-encode-test-admin-{$asset['name']}" );

				wp_set_script_translations( "dream-encode-test-admin-{$asset['name']}", 'dream-encode-test' );
			}
		}
	}
}
