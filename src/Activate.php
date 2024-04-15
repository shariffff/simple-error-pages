<?php

namespace SEPages;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Activate {

	public static function activate() {
		flush_rewrite_rules();
		$_this = new self();
		$_this->default_error_pages();
	}

	public function default_error_pages() {
		global $wp_filesystem;

		$installed = get_option( 'simple_error_pages_installed' );
		if ( $installed ) {
			return;
		}

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();


		$template = SIMPLE_ERROR_PAGES_PLUGIN_DIR . '/templates/pattern.txt';
		$content = '';

		if ( ! is_wp_error( $wp_filesystem ) && $wp_filesystem->exists( $template ) ) {
			$content = $wp_filesystem->get_contents( $template );
		}

		$post_content = [ 
			'post_title' => 'Brief Maintenance',
			'post_status' => 'draft',
			'post_type' => 'simple_error_pages',
			'post_content' => $content
		];

		$created = wp_insert_post( $post_content );

		update_option( 'simple_error_pages', [ 'php-error' => [ 'id' => $created ] ] );
		update_option( 'simple_error_pages_installed', time() );
	}

}