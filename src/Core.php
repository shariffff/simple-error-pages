<?php

namespace SEPages;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Core {
	public static function getServices() {
		return [ 
			Pages::class,
			Settings::class,
			Dropins::class
		];
	}

	/**
	 * Loop through the classes, initialize them,
	 * and call the register() method if it exists
	 * @return
	 */
	public static function init() {
		foreach ( self::getServices() as $class ) {
			$service = self::instantiate( $class );
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	private static function instantiate( $class ) {
		$service = new $class();
		return $service;
	}

}