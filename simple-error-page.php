<?php
/**
 * Plugin Name:       Simple Error Pages
 * Plugin URI:        https://github.com/shariffff/simple-error-pages
 * Description:       Craft beautiful error pages directly within the WordPress editor using Simple Error Pages. This plugin leverages WordPress Dropins, enabling you to customize php-error.php for PHP fatal errors, maintenance.php for maintenance screens, and db-error.php for database errors, all from the comfort of your editor. After setting up, you can even remove the plugin if desired.
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

register_activation_hook( __FILE__, fn() => SimpleErrorPages\Activate::activate() );
register_deactivation_hook( __FILE__, fn() => SimpleErrorPages\Deactivate::deactivate() );

add_filter( 'plugin_action_links_' . SIMPLE_ERROR_PAGES_PLUGIN_BASE, function ($links) {
	return array_merge(
		[ '<a href="' . admin_url( 'edit.php?post_type=simple_error_pages' ) . '">Pages</a>' ],
		$links
	);
} );

if ( class_exists( 'SimpleErrorPages\\Core' ) ) {
	SimpleErrorPages\Core::init();
}
