<?php
/**
 * Plugin Name:     Coffee Press
 * Plugin URI:      http://donini.me/
 * Description:     A few tips for the WordPress developers
 * Author:          Rodrigo Donini
 * Author URI:      http://donini.me/
 * Text Domain:     coffeepress
 * Domain Path:     /languages
 * Version:         1.0
 *
 * @package         CoffeePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'COFFEEPRESS_PATH', plugin_dir_path( __FILE__ ) );

require_once COFFEEPRESS_PATH . 'inc/class-cp-message.php';
