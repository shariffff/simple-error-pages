<?php
/**
 * Loads the necessary classes and creates single instance of each.
 *
 * @package simple_error_pages
 */

namespace SEPages;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core class responsible for initializing the plugin.
 */
final class Core {
	/**
	 * Retrieves the list of services.
	 *
	 * @return array The list of services.
	 */
	public static function get_services() {
		return array(
			Pages::class,
			Settings::class,
			Dropins::class,
		);
	}

	/**
	 * Loop through the classes, initialize them,
	 * and call the register() method if it exists
	 *
	 * @return void
	 */
	public static function init() {
		foreach ( self::get_services() as $class ) {
			$service = self::instantiate( $class );
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	/**
	 * Initialize the class.
	 *
	 * @param string $class_name The class name.
	 * @return mixed
	 */
	private static function instantiate( $class_name ) {
		$service = new $class_name();
		return $service;
	}
}
