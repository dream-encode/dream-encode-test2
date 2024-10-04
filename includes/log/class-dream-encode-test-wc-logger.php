<?php
/**
 * Simple logger class that relies on the WC_Logger instance.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Dream_Encode_Test/includes/log
 */

namespace Dream_Encode\Test\Core\Log;

use Dream_Encode\Test\Core\Abstracts\Dream_Encode_Test_Abstract_WC_Logger;

/**
 * Simple logger class to log data to custom files.
 *
 * Relies on the bundled logger class in WooCommerce.
 *
 * @package  Dream_Encode\Test\Core\Log\Dream_Encode_Test_WC_Logger
 * @author   David Baumwald <david@dream-encode.com>
 */
final class Dream_Encode_Test_WC_Logger extends Dream_Encode_Test_Abstract_WC_Logger {

	/**
	 * Log namespace.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     string  $namespace  Log namespace.
	 */
	protected static $namespace = 'dream-encode-test';
}
