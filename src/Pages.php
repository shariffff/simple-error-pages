<?php

namespace SimpleErrorPages;

class Pages {

	public function register() {
		add_action( 'init', [ $this, 'simple_error_pages_cpt' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ] );
		add_filter( 'display_post_states', [ $this, 'custom_state' ], 10, 2 );
		add_filter( 'manage_simple_error_pages_posts_columns', [ $this, 'preview_column' ] );
		add_action( 'manage_simple_error_pages_posts_custom_column', [ $this, 'preview_link' ], 10, 2 );
		add_filter( 'bulk_actions-edit-simple_error_pages', '__return_false' );
		add_filter( 'page_row_actions', [ $this, 'remove_inline_edit' ], 10, 2 );
	}

	public static function list() {
		return get_option( 'simple_error_pages', [] );
	}

	public static function get( $page ) {
		$all = self::list();
		if ( array_key_exists( $page, $all ) ) {
			return $all[ $page ];
		}
		return null;
	}

	public function remove_inline_edit( $actions, $post ) {
		if ( $post->post_type == 'simple_error_pages' ) {
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}
	public function custom_state( $post_states, $post ) {

		if ( 'simple_error_pages' !== get_post_type( $post->ID ) ) {
			return;
		}
		$states = [ 
			'db-error' => 'Database Error',
			'php-error' => 'PHP Error',
			'maintenance' => 'Maintenance',
		];

		foreach ( $states as $key => $value ) {
			$item = self::get( $key );
			if ( intval( $item['id'] ) === $post->ID ) {
				$post_states[ "simple_error_page_for_$key" ] = __( $value, 'simple-error-pages' );
			}
		}

		return $post_states;
	}

	public function simple_error_pages_cpt() {
		register_post_type( 'simple_error_pages',
			array(
				'labels' => array(
					'name' => __( 'Simple Error Pages', 'simple-error-pages' ),
					'singular_name' => __( 'Error Page', 'simple-error-pages' ),
					'add_new' => __( 'Add New', 'simple-error-pages' ),
				),
				'public' => true,
				'has_archive' => true,
				'show_in_rest' => true,
				'show_in_menu' => 'tools.php',
				'publicly_queryable' => false,
				'exclude_from_search' => true,
				'hierarchical' => true,

			)
		);
	}

	public function admin_styles() {

		$screen = get_current_screen();
		if ( 'edit-simple_error_pages' !== $screen->id ) {
			return;
		}
		wp_enqueue_style( 'simple-error-pages', plugins_url( 'assets/css/admin.css', SIMPLE_ERROR_PAGES_PLUGIN_FILE ), array(), 1, 'all' );

	}

	function preview_column( $columns ) {
		$columns = array(
			'cb' => $columns['cb'],
			'title' => $columns['title'],
			'preview_link' => __( 'Preview', 'simple-error-pages' ),
			'date' => $columns['date']
		);
		return $columns;
	}

	function preview_link( $column, $post_id ) {
		$all = Pages::list();
		$page_name = null;

		foreach ( $all as $key => $value ) {
			if ( isset( $value['id'] ) && intval( $value['id'] ) === $post_id ) {
				$page_name = $key;
				break;
			}
		}


		if ( $page_name ) {
			$dropin = trailingslashit( WP_CONTENT_URL ) . $page_name . '.php';
		}


		switch ( $column ) {
			case 'preview_link':
				if ( $page_name ) {
					echo '<a target="_blank" href="' . $dropin . '">
					<svg style="fill: currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M19.5 4.5h-7V6h4.44l-5.97 5.97 1.06 1.06L18 7.06v4.44h1.5v-7Zm-13 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-3H17v3a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h3V5.5h-3Z"></path></svg></a>';
				}
				break;
		}
	}

}
