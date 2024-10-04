<?php
/**
 * Default filters for the plugin.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Dream_Encode_Test
 */

namespace Dream_Encode\Test\Core;

add_action( 'dream_encode/test/example_action', 'Dream_Encode\Test\Frontend\example_function', 10, 3 );
