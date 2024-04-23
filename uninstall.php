<?php
/**
 * If uninstall not called from WordPress, then exit.
 *
 * @package           simple-error-pages
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
/**
 * Delete the CPT posts.
 * delete the dropins.
 * flush rewrite rules.
 */
