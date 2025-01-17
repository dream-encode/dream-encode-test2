<?php
/**
 * Class Dream_Encode_Test_Upgrader
 *
 * @since 1.0.0
 */

namespace Dream_Encode\Test\Core\Upgrade;

defined( 'ABSPATH' ) || exit;

use Dream_Encode\Test\Core\Log\Dream_Encode_Test_Upgrader_Logger;

/**
 * Class Dream_Encode_Test_Upgrader
 *
 * @since 1.0.0
 */
class Dream_Encode_Test_Upgrader {

	/**
	 * DB updates and callbacks that need to be run per version.
	 *
	 * Please note that these functions are invoked when the plugin is updated from a previous version,
	 * but NOT when the plugin is newly installed.
	 *
	 * Database schema changes must be incorporated to the SQL returned by get_schema, which is applied
	 * via dbDelta at both install and update time.
	 *
	 * @var array<mixed>
	 */
	private static $db_updates = array();

	/**
	 * Hook in tabs.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
		add_action( 'det_run_update_callback', array( __CLASS__, 'run_update_callback' ) );
		add_action( 'det_update_db_to_current_version', array( __CLASS__, 'update_db_version' ) );
	}

	/**
	 * Check plugin version and run the updater is required.
	 *
	 * This check is done on all requests and runs if the versions do not match.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function check_version() {
		$plugin_version      = get_option( 'det_plugin_version' );
		$plugin_code_version = DREAM_ENCODE_TEST_PLUGIN_VERSION;
		$requires_update     = version_compare( $plugin_version, $plugin_code_version, '<' );

		if ( $requires_update ) {
			self::install();
		}
	}

	/**
	 * Run an update callback when triggered by ActionScheduler.
	 *
	 * @since  1.0.0
	 * @param  string  $update_callback  Callback name.
	 * @return void
	 */
	public static function run_update_callback( $update_callback ) {
		include_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/upgrade/dream-encode-test-upgrader-functions.php';

		if ( is_callable( $update_callback ) ) {
			self::run_update_callback_start( $update_callback );

			$result = (bool) call_user_func( $update_callback );

			self::run_update_callback_end( $update_callback, $result );
		}
	}

	/**
	 * Triggered when a callback will run.
	 *
	 * @since  1.0.0
	 * @param  string  $callback  Callback name.
	 * @return void
	 */
	protected static function run_update_callback_start( $callback ) {
		det_maybe_define_constant( 'dream_encode_test_UPDATING', true );
	}

	/**
	 * Triggered when a callback has ran.
	 *
	 * @since  1.0.0
	 * @param  string  $callback  Callback name.
	 * @param  bool    $result    Return value from callback. Non-false need to run again.
	 * @return void
	 */
	protected static function run_update_callback_end( $callback, $result ) {
		if ( $result && is_callable( $callback ) ) {
			$callback( $result );
		}
	}

	/**
	 * Install plugin.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function install() {
		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( self::is_installing() ) {
			return;
		}

		include_once DREAM_ENCODE_TEST_PLUGIN_PATH . 'includes/log/class-dream-encode-test-upgrader-logger.php';

		Dream_Encode_Test_Upgrader_Logger::log(
			__( '=================== Beginning Install ===================', 'dream-encode-test' )
		);

		// If we made it here nothing is running yet, lets set the transient now.
		set_transient( 'det_installing', 'yes', MINUTE_IN_SECONDS * 10 );

		det_maybe_define_constant( 'dream_encode_test_INSTALLING', true );

		self::create_tables();

		self::create_default_options();

		self::update_plugin_version();

		self::maybe_update_db_version();

		delete_transient( 'det_installing' );

		Dream_Encode_Test_Upgrader_Logger::log(
			__( '=================== End Install ===================', 'dream-encode-test' )
		);
	}

	/**
	 * Create default options.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	protected static function create_default_options() {
	}

	/**
	 * Returns true if we're installing.
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	private static function is_installing() {
		return 'yes' === get_transient( 'det_installing' );
	}

	/**
	 * Is this a brand new plugin install?
	 *
	 * A brand new install has no version yet. Also treat empty installs as 'new'.
	 *
	 * @since  1.0.0
	 * @return boolean
	 */
	public static function is_new_install() {
		return is_null( get_option( 'det_plugin_version', null ) );
	}

	/**
	 * Is a DB update needed?
	 *
	 * @since  1.0.0
	 * @return boolean
	 */
	public static function needs_db_update() {
		Dream_Encode_Test_Upgrader_Logger::log(
			__( 'Checking if updates are needed for this version...', 'dream-encode-test' )
		);

		$updates = self::get_db_update_callbacks();

		if ( count( $updates ) < 1 ) {
			Dream_Encode_Test_Upgrader_Logger::log(
				__( 'No updates found.', 'dream-encode-test' )
			);

			return false;
		}

		$current_db_version = get_option( 'det_database_version', null );

		$update_versions    = array_keys( $updates );

		// @phpstan-ignore-next-line
		usort( $update_versions, 'version_compare' );

		return ! is_null( $current_db_version ) && version_compare( $current_db_version, end( $update_versions ), '<' );
	}

	/**
	 * See if we need to show or run database updates during install.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	private static function maybe_update_db_version() {
		if ( self::needs_db_update() ) {
			Dream_Encode_Test_Upgrader_Logger::log(
				__( 'Version requires updates.', 'dream-encode-test' )
			);

			self::update();
		} else {
			self::update_db_version();
		}
	}

	/**
	 * Update plugin version to current.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	private static function update_plugin_version() {
		update_option( 'det_plugin_version', DREAM_ENCODE_TEST_PLUGIN_VERSION, true );
	}

	/**
	 * Get list of DB update callbacks.
	 *
	 * @since  1.0.0
	 * @return array<mixed>
	 */
	public static function get_db_update_callbacks() {
		return self::$db_updates;
	}

	/**
	 * Push all needed DB updates to the queue for processing.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	private static function update() {
		Dream_Encode_Test_Upgrader_Logger::log(
			__( 'Checking updates...', 'dream-encode-test' )
		);

		$current_db_version = get_option( 'det_database_version' );
		$loop               = 0;

		Dream_Encode_Test_Upgrader_Logger::log(
			sprintf(
				/* translators: %s current database version. */
				__( 'Current database version: %s', 'dream-encode-test' ),
				$current_db_version
			)
		);

		foreach ( self::get_db_update_callbacks() as $version => $update_callbacks ) {
			if ( version_compare( $current_db_version, $version, '<' ) ) {
				Dream_Encode_Test_Upgrader_Logger::log(
					sprintf(
						/* translators: %s current database version. */
						__( 'Parsing needed updates for version %s', 'dream-encode-test' ),
						$version
					)
				);

				foreach ( $update_callbacks as $update_callback ) {
					if ( as_has_scheduled_action( 'det_run_update_callback', array( $update_callback ), 'dream-encode-test' ) ) {
						continue;
					}

					as_schedule_single_action(
						time() + $loop,
						'det_run_update_callback',
						array(
							$update_callback,
						),
						'dream-encode-test'
					);

					Dream_Encode_Test_Upgrader_Logger::log(
						sprintf(
							/* translators: %s update hook. */
							__( 'Scheduled async update for `%s`', 'dream-encode-test' ),
							$update_callback
						)
					);

					$loop++;
				}
			}
		}

		// After the callbacks finish, update the db version to the current plugin version.
		$current_db_define_version = DREAM_ENCODE_TEST_DATABASE_VERSION;

		if ( version_compare( $current_db_version, $current_db_define_version, '<' ) && ! as_has_scheduled_action( 'det_update_db_to_current_version', array(), 'dream-encode-test' ) ) {
			as_schedule_single_action(
				time() + $loop,
				'det_update_db_to_current_version',
				array(
					$current_db_define_version,
				),
				'dream-encode-test'
			);

			Dream_Encode_Test_Upgrader_Logger::log(
				__( 'Scheduled async database version update.', 'dream-encode-test' )
			);
		}
	}

	/**
	 * Update DB version to current.
	 *
	 * @since  1.0.0
	 * @param  string|null  $version  New plugin DB version or null.
	 * @return void
	 */
	public static function update_db_version( $version = null ) {
		update_option( 'det_database_version', is_null( $version ) ? DREAM_ENCODE_TEST_DATABASE_VERSION : $version, true );

		Dream_Encode_Test_Upgrader_Logger::log(
			sprintf(
				/* translators: %s current database version. */
				__( 'Updated database version to %s.', 'dream-encode-test' ),
				$version
			)
		);
	}

	/**
	 * Set up the database tables which the plugin needs to function.
	 * WARNING: If you are modifying this method, make sure that its safe to call regardless of the state of database.
	 *
	 * This is called from `install` method and is executed in-sync when the plugin is installed or updated.
	 *
	 * @since 1.0.0
	 * @global WPDB  $wpdb  WordPress database instance global.
	 * @return void
	 */
	public static function create_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$schema = self::get_schema();

		if ( ! empty( $schema ) ) {
			dbDelta( $schema );
		}
	}

	/**
	 * Get Table schema.
	 *
	 * Changing indexes may cause duplicate index notices in logs due to https://core.trac.wordpress.org/ticket/34870 but dropping
	 * indexes first causes too much load on some servers/larger DB.
	 *
	 * When adding or removing a table, make sure to update the list of tables in Dream_Encode_Test_Upgrader::get_tables().
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb  WordPress database instance global.
	 * @return string
	 */
	private static function get_schema() {
		global $wpdb;

		$charset_collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$charset_collate = $wpdb->get_charset_collate();
		}

		/*
		 * Indexes have a maximum size of 767 bytes. Historically, we haven't need to be concerned about that.
		 * As of 4.2, however, we moved to utf8mb4, which uses 4 bytes per character. This means that an index which
		 * used to have room for floor(767/3) = 255 characters, now only has room for floor(767/4) = 191 characters.
		 */
		$max_index_length = 191;

		$tables = "";

		return $tables;
	}

	/**
	 * Return a list of plugin tables. Used to make sure all tables are dropped when uninstalling the plugin
	 * in a single site or multi site environment.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb  WordPress database instance global.
	 * @return array<string, string>  Database tables.
	 */
	public static function get_tables() {
		global $wpdb;

		$table_names = array();

		$tables = array();

		foreach ( $table_names as $table_name ) {
			$tables[ $table_name ] = $wpdb->prefix . $table_name;
		}

		return $tables;
	}

	/**
	 * Drop plugin tables.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb  WordPress database instance global.
	 * @return void
	 */
	public static function drop_tables() {
		global $wpdb;

		$tables = static::get_tables();

		foreach ( $tables as $name => $table ) {
			$wpdb->query(
				$wpdb->prepare(
					'DROP TABLE IF EXISTS %i',
					$table
				)
			);
		}
	}

	/**
	 * Define plugin tables in the `$wpdb` global.
	 *
	 * @since  1.0.0
	 * @global WPDB  $wpdb  WordPress database instance global.
	 * @return void
	 */
	public static function define_tables() {
		global $wpdb;

		$tables = static::get_tables();

		foreach ( $tables as $name => $table ) {
			$wpdb->{$name} = $table;

			$wpdb->tables[] = $name;
		}
	}
}

Dream_Encode_Test_Upgrader::init();
