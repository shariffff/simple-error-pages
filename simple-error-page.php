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
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}


require_once dirname( __FILE__ ) . '/vendor/autoload.php';


define( 'SIMPLE_ERROR_PAGES_VERSION', '1.0' );
define( 'SIMPLE_ERROR_PAGES_PLUGIN_FILE', __FILE__ );
define( 'SIMPLE_ERROR_PAGES_PLUGIN_DIR', __DIR__ );
define( 'SIMPLE_ERROR_PAGES_PLUGIN_BASE', plugin_basename( __FILE__ ) );

register_activation_hook( __FILE__, fn() => SEPages\Activate::activate() );
register_deactivation_hook( __FILE__, fn() => SEPages\Deactivate::deactivate() );

add_filter( 'plugin_action_links_' . SIMPLE_ERROR_PAGES_PLUGIN_BASE, function ($links) {
	return array_merge(
		[ '<a href="' . admin_url( 'edit.php?post_type=simple_error_pages' ) . '">Pages</a>' ],
		$links
	);
} );

if ( class_exists( 'SEPages\\Core' ) ) {
	SEPages\Core::init();
}
