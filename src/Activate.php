<?php

namespace SimpleErrorPages;

class Activate {

	public static function activate() {
		flush_rewrite_rules();
		$_this = new self();
		$_this->default_error_pages();
	}

	public function default_error_pages() {
		$installed = get_option( 'simple_error_pages_installed' );
		if ( $installed ) {
			return;
		}
		$template = file_get_contents( SIMPLE_ERROR_PAGES_PLUGIN_DIR . '/templates/pattern.html' );

		if ( file_exists( $template ) ) {
			$content = file_get_contents( $template );
		}

		$initial_items = [ 
			'Ops! there is an error.',
			'Brief Maintenance',
			'Ops! Technical Error!',
		];

		$created = [];

		foreach ( $initial_items as $item ) {
			$post_content = [ 
				'post_title' => $item,
				'post_status' => 'draft',
				'post_type' => 'simple_error_pages',
				'post_content' => $content
			];

			$created[] = wp_insert_post( $post_content );
		}

		update_option( 'simple_error_pages', [ [ 'db-error' => $created[0] ] ] );
		update_option( 'simple_error_pages_installed', time() );

	}

}