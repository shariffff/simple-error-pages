<?php
/**
 * This file is responsible for loading the necessary classes.
 *
 * @package simple_error_pages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Callback for the autoload function.
 *
 * @param string $class_name The name of the class to load.
 */
function simple_error_pages_load_files( $class_name ) {
	$prefix   = 'SEPages\\';
	$base_dir = SIMPLE_ERROR_PAGES_PLUGIN_DIR . '/src/';

	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class_name, $len ) !== 0 ) {
		return;
	}

	$relative_class = substr( $class_name, $len );

	
	$file = $base_dir . 'class-' . str_replace( '\\', DIRECTORY_SEPARATOR, strtolower($relative_class) ) . '.php';

	if ( file_exists( $file ) ) {
		require $file;
	}
}

spl_autoload_register( 'simple_error_pages_load_files' );
