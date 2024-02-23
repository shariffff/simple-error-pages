<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://shariffff.com
 * @since             1.0.0
 * @package           Error_Bag
 *
 * @wordpress-plugin
 * Plugin Name:       Error Bag
 * Plugin URI:        https://sharifff.com
 * Description:       Manage Error Pages, Logs and more.
 * Version:           1.0.0
 * Author:            Sharif Mohammad Eunus
 * Author URI:        https://sharifff.com/
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


register_activation_hook( __FILE__, fn() => SimpleErrorPages\Activate::activate() );
register_deactivation_hook( __FILE__, fn() => SimpleErrorPages\Deactivate::deactivate() );


if ( class_exists( 'SimpleErrorPages\\Core' ) ) {
	SimpleErrorPages\Core::init();
}