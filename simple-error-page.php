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

if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'SIMPLE_ERROR_PAGES_VERSION', '1.0' );
define( 'SIMPLE_ERROR_PAGES_PLUGIN_FILE', __FILE__ );
define( 'SIMPLE_ERROR_PAGES_PLUGIN_DIR', __DIR__ );
define( 'SIMPLE_ERROR_PAGES_PLUGIN_BASE', plugin_basename( __FILE__ ) );


require_once SIMPLE_ERROR_PAGES_PLUGIN_DIR . '/src/class-activate.php';
require_once SIMPLE_ERROR_PAGES_PLUGIN_DIR . '/src/class-deactivate.php';
require_once SIMPLE_ERROR_PAGES_PLUGIN_DIR . '/src/class-dropins.php';
require_once SIMPLE_ERROR_PAGES_PLUGIN_DIR . '/src/class-pages.php';
require_once SIMPLE_ERROR_PAGES_PLUGIN_DIR . '/src/class-settings.php';
require_once SIMPLE_ERROR_PAGES_PLUGIN_DIR . '/src/class-core.php';

/**
 * Summary of simple_error_pages_activate
 *
 * @return void
 */
function simple_error_pages_activate() {
	SEPages\Activate::activate();
}

/**
 * Summary of simple_error_pages_deactivate
 *
 * @return void
 */
function simple_error_pages_deactivate() {
	SEPages\Deactivate::deactivate();
}

register_activation_hook( __FILE__, 'simple_error_pages_activate' );
register_deactivation_hook( __FILE__, 'simple_error_pages_deactivate' );


add_filter(
	'plugin_action_links_' . SIMPLE_ERROR_PAGES_PLUGIN_BASE,
	'simple_error_pages_plugin_action_link'
);

/**
 * Sets plugin action link
 *
 * @param array $links An array of existing action links for the plugin.
 * @return array Modified array of action links, with the custom link added.
 */
function simple_error_pages_plugin_action_link( $links ) {
	$url = 'edit.php?post_type=simple_error_pages';
	return array_merge(
		array( '<a href="' . admin_url( $url ) . '">Pages</a>' ),
		$links
	);
}

if ( class_exists( 'SEPages\\Core' ) ) {
	SEPages\Core::init();
}
