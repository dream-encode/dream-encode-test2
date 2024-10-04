<?php
/**
 * Simple wrapper class for custom logs.
 *
 * @uses \WC_Logger();
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Dream_Encode_Test
 * @subpackage Dream_Encode_Test/includes
 */

namespace Dream_Encode\Test\Core\Log;

use Dream_Encode\Test\Core\Abstracts\Dream_Encode_Test_Abstract_WC_Logger;

/**
 * Logger class.
 *
 * Log stuff to files.
 *
 * @since      1.0.0
 * @package    Dream_Encode_Test
 * @subpackage Dream_Encode_Test/includes
 * @author     David Baumwald <david@dream-encode.com>
 */
final class Dream_Encode_Test_Upgrader_Logger extends Dream_Encode_Test_Abstract_WC_Logger {
	/**
	 * Log namespace.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     string  $namespace  Log namespace.
	 */
	public static $namespace = 'dream-encode-test-upgrader';
}
