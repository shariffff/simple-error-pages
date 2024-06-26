<?php
/**
 * Plugin Name:       Simple Error Pages
 * Plugin URI:        https://github.com/shariffff/simple-error-pages
 * Description:       This plugin uses WordPress Dropins to let you easily customize error pages for PHP, maintenance, and database issues directly from your editor.
 * Version:           1.0.0
 * Author:            Sharif Mohammad Eunus
 * Author URI:        https://github.com/shariffff
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-error-pages
 * Domain Path:       /languages
 *
 * @package           simple_error_pages
 */

// Exit if directly accessed.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Return if not admin area.
if ( ! is_admin() ) {
	return;
}

define( 'SIMPLE_ERROR_PAGES_VERSION', '1.0' );
define( 'SIMPLE_ERROR_PAGES_PLUGIN_FILE', __FILE__ );
define( 'SIMPLE_ERROR_PAGES_PLUGIN_DIR', __DIR__ );
define( 'SIMPLE_ERROR_PAGES_PLUGIN_BASE', plugin_basename( __FILE__ ) );

require_once SIMPLE_ERROR_PAGES_PLUGIN_DIR . '/autoloader.php';


/**
 * Runs on plugin activation.
 *
 * @return void
 */
function simple_error_pages_activate() {
	SEPages\Activate::activate();
}

/**
 * Runs on plugin deactivation.
 *
 * @return void
 */
function simple_error_pages_deactivate() {
	SEPages\Deactivate::deactivate();
}

register_activation_hook( __FILE__, 'simple_error_pages_activate' );
register_deactivation_hook( __FILE__, 'simple_error_pages_deactivate' );


// Initialize the class.
if ( class_exists( 'SEPages\\Core' ) ) {
	SEPages\Core::init();
}
