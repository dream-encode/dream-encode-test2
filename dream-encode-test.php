<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://dream-encode.com
 * @since             1.0.0
 * @package           Dream_Encode_Test
 *
 * @wordpress-plugin
 * Plugin Name:       Dream Encode - Test
 * Plugin URI:        https://example.com
 * Description:       Testing.
 * Version:           1.0.0
 * Author:            David Baumwald
 * Author URI:        https://dream-encode.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dream-encode-test
 * Domain Path:       /languages
 * GitHub Plugin URI: dream-encode/dream-encode-test2
 * Primary Branch:    main
 * Release Asset:     true
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Constants
 */
require_once 'includes/dream-encode-test-constants.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dream-encode-test-activator.php
 *
 * @return void
 */
function dream_encode_test_activate() {
	require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/class-dream-encode-test-activator.php';
	Dream_Encode\Test\Core\Dream_Encode_Test_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dream-encode-test-deactivator.php
 *
 * @return void
 */
function dream_encode_test_deactivate() {
	require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/class-dream-encode-test-deactivator.php';
	Dream_Encode\Test\Core\Dream_Encode_Test_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'dream_encode_test_activate' );
register_deactivation_hook( __FILE__, 'dream_encode_test_deactivate' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since  1.0.0
 * @return void
 */
function dream_encode_test_init() {
	/**
	 * Import some common functions.
	 */
	require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/dream-encode-test-core-functions.php';

	/**
	 * Main plugin loader class.
	 */
	require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/class-dream-encode-test.php';

	$plugin = new Dream_Encode\Test\Core\Dream_Encode_Test();
	$plugin->run();
}

dream_encode_test_init();
