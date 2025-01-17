<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Dream_Encode_Test
 * @subpackage Dream_Encode_Test/includes
 */

namespace Dream_Encode\Test\Core;

use Dream_Encode\Test\Core\Dream_Encode_Test_Loader;
use Dream_Encode\Test\Core\Dream_Encode_Test_I18n;
use Dream_Encode\Test\Admin\Dream_Encode_Test_Admin;
use Dream_Encode\Test\Frontend\Dream_Encode_Test_Public;
use Dream_Encode\Test\Core\Upgrade\Dream_Encode_Test_Upgrader;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Dream_Encode_Test
 * @subpackage Dream_Encode_Test/includes
 * @author     David Baumwald <david@dream-encode.com>
 */
class Dream_Encode_Test {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     Dream_Encode_Test_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'dream-encode-test';

		$this->load_dependencies();
		$this->define_tables();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_global_hooks();
		$this->define_cli_commands();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Dream_Encode_Test_Loader. Orchestrates the hooks of the plugin.
	 * - Dream_Encode_Test_I18n. Defines internationalization functionality.
	 * - Dream_Encode_Test_Admin. Defines all hooks for the admin area.
	 * - Dream_Encode_Test_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {
		/**
		 * Logger
		 */
		require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/abstracts/abstract-wc-logger.php';
		require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/log/class-dream-encode-test-wc-logger.php';
		/**
		 * Action Scheduler
		 */
		require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'libraries/action-scheduler/action-scheduler.php';
		/**
		 * Upgrader.
		 */
		require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/upgrade/class-dream-encode-test-upgrader.php';

		/**
		 * REST API
		 */
		require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/rest-api/class-dream-encode-test-rest-response.php';
		require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/abstracts/abstract-rest-api.php';
		require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/abstracts/abstract-rest-controller.php';
		require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/rest-api/class-dream-encode-test-core-api.php';
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/class-dream-encode-test-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/class-dream-encode-test-i18n.php';

		/**
		 * Default filters.
		 */
		require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/dream-encode-test-default-filters.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'admin/class-dream-encode-test-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'public/class-dream-encode-test-public.php';

		Dream_Encode_Test_Upgrader::init();

		$this->loader = new Dream_Encode_Test_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dream_Encode_Test_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function set_locale() {
		$plugin_i18n = new Dream_Encode_Test_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Define custom databases tables.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function define_tables() {
		Dream_Encode_Test_Upgrader::define_tables();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Dream_Encode_Test_Admin();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function define_public_hooks() {
		$plugin_public = new Dream_Encode_Test_Public();

		$this->loader->add_action( 'init', $plugin_public, 'rest_api_cors' );
		$this->loader->add_action( 'init', $plugin_public, 'rest_init' );
		$this->loader->add_action( 'example_function', $plugin_public, 'example_function' );
	}

	/**
	 * Register all of the global hooks .
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function define_global_hooks() {
	}

	/**
	 * Register custom WP_Cli commands.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	private function define_cli_commands() {
		if ( defined( 'WP_CLI' ) &amp;&amp; WP_CLI ) {
			WP_CLI::add_command( 'det', 'Dream_Encode\TestCoreCLIDream_Encode_Test_CLI_Commands' );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 * @return string  The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  1.0.0
	 * @return Dream_Encode_Test_Loader  Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0.0
	 * @return string  The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
