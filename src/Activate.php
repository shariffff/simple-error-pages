<?php

namespace SimpleErrorPages;

class Activate {

	public static function activate() {
		flush_rewrite_rules();
		$_this = new self();
		$_this->default_error_pages();
	}

	public function default_error_pages() {

		$content = '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"margin":{"top":"0","bottom":"0"}},"dimensions":{"minHeight":"100vh"},"elements":{"link":{"color":{"text":"var:preset|color|base-2"}}},"color":{"gradient":"linear-gradient(180deg,rgb(12,11,11) 0%,rgba(61,15,15,0.62) 99%)"}},"textColor":"base-2","layout":{"type":"flex","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
		<div class="wp-block-group alignfull has-base-2-color has-text-color has-background has-link-color" style="background:linear-gradient(180deg,rgb(12,11,11) 0%,rgba(61,15,15,0.62) 99%);min-height:100vh;margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"constrained"}} -->
		<div class="wp-block-group"><!-- wp:spacer {"height":"var:preset|spacing|10"} -->
		<div style="height:var(--wp--preset--spacing--10)" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->
		<!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"right":"0","left":"0"},"padding":{"right":"var:preset|spacing|60","left":"var:preset|spacing|60","top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}},"elements":{"link":{"color":{"text":"var:preset|color|base-2"}}},"color":{"background":"#76707063"},"typography":{"fontStyle":"normal","fontWeight":"100"}},"textColor":"base-2","fontSize":"x-large"} -->
		<h2 class="wp-block-heading has-text-align-center has-base-2-color has-text-color has-background has-link-color has-x-large-font-size" style="background-color:#76707063;margin-right:0;margin-left:0;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60);font-style:normal;font-weight:100">The site is under maintenance.</h2>
		<!-- /wp:heading -->
		<!-- wp:spacer {"height":"44px"} -->
		<div style="height:44px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer --></div>
		<!-- /wp:group --></div>
		<!-- /wp:group -->';
		$installed = get_option( 'simple_error_pages_installed' );
		if ( $installed ) {
			return;
		}
		$defaults = [ 
			'post_status' => 'publish',
			'post_type' => 'simple_error_pages',
		];
		$initial_items = [ 
			[ 
				'post_title' => 'Opps! there is an error.',
				'post_content' => $content
			],
			[ 
				'post_title' => 'Brief Maintenance',
				'post_content' => $content

			]
		];

		update_option( 'simple_error_pages_installed', time() );
		foreach ( $initial_items as $item ) {
			wp_insert_post( wp_parse_args( $item, $defaults ) );
		}
	}

}