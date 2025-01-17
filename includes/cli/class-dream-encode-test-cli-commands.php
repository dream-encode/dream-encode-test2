<?php
/**
 * Class Dream_Encode_Test_CLI_Commands
 *
 * Base class for custom WP_CLI commands.
 *
 * @since 1.0.0
 */

namespace Dream_Encode\Test\Core\CLI;

use WP_CLI;

/**
 * Class Dream_Encode_Test_CLI_Commands
 *
 * Base class for custom WP_CLI commands for manual migrations and fixes.
 *
 * @since 1.0.0
 */
final class Dream_Encode_Test_CLI_Commands {
	/**
	 * Example command.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $args        Indexed array of arguments.
	 * @param  array  $assoc_args  Assoc array of arguments.
	 * @return void
	 */
	public function example_command( $args, $assoc_args ) {
		$dry_run = WP_CLI\Utils\get_flag_value( $assoc_args, 'dry-run' );

		if ( $dry_run ) {
			WP_CLI::line(
				__( 'Dry run only.', 'dream-encode-test' )
			);
		}
	}
}
