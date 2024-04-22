<?php
/**
 * Load the nacessary
 *
 * @package simple_error_pages
 */

namespace SEPages;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Summary of Core
 */
final class Core {
	/**
	 * Summary of getServices
	 *
	 * @return string[]
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
	 * Summary of instantiate
	 *
	 * @param mixed $class_name Name of the class.
	 *
	 * @return object
	 */
	private static function instantiate( $class_name ) {
		$service = new $class_name();
		return $service;
	}
}
