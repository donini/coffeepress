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

require_once COFFEEPRESS_PATH . 'inc/class-cp-helper.php';
require_once COFFEEPRESS_PATH . 'inc/class-cp-message.php';


/**
 * Test if the ACF plugin is active.
 */
function activation_check() {
	if (! is_plugin_active( 'advanced-custom-fields-pro/acf.php' )) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( 'The plugin ACF needs to be installed and active in order to activate this plugin.' );
	}
}

register_activation_hook( __FILE__, 'activation_check' );