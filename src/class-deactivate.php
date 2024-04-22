<?php
/**
 * Handle Deactivation
 *
 * @package simple_error_pages
 */

namespace SEPages;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Summary of Deactivate
 */
class Deactivate {

	/**
	 * Summary of deactivate
	 *
	 * @return void
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
