<?php
/**
 * CPT and Related settings
 *
 * @package simple_error_pages
 */

namespace SEPages;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Summary of Pages
 */
class Pages {

	/**
	 * Summary of register
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', array( $this, 'simple_error_pages_cpt' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_filter( 'display_post_states', array( $this, 'custom_state' ), 10, 2 );
		add_filter( 'manage_simple_error_pages_posts_columns', array( $this, 'preview_column' ) );
		add_action( 'manage_simple_error_pages_posts_custom_column', array( $this, 'preview_link' ), 10, 2 );
		add_filter( 'bulk_actions-edit-simple_error_pages', '__return_false' );
		add_filter( 'page_row_actions', array( $this, 'remove_inline_edit' ), 10, 2 );
	}

	/**
	 * Summary of list
	 *
	 * @return mixed
	 */
	public static function list() {
		return get_option( 'simple_error_pages', array() );
	}

	/**
	 * Summary of get
	 *
	 * @param mixed $page
	 *
	 * @return mixed
	 */
	public static function get( $page ) {
		$all = self::list();
		if ( array_key_exists( $page, $all ) ) {
			return $all[ $page ];
		}
		return null;
	}

	/**
	 * Summary of remove_inline_edit
	 *
	 * @param mixed $actions
	 * @param mixed $post
	 *
	 * @return mixed
	 */
	public function remove_inline_edit( $actions, $post ) {
		if ( 'simple_error_pages' === $post->post_type ) {
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}

	/**
	 * Summary of custom_state
	 *
	 * @param mixed $post_states
	 * @param mixed $post
	 *
	 * @return mixed
	 */
	public function custom_state( $post_states, $post ) {

		if ( 'simple_error_pages' !== get_post_type( $post->ID ) ) {
			return;
		}
		$states = array(
			'db-error'    => 'Database Error',
			'php-error'   => 'PHP Error',
			'maintenance' => 'Maintenance',
		);

		foreach ( $states as $key => $value ) {
			$item = self::get( $key );
			if ( isset( $item['id'] ) && ( $item['id'] ) == $post->ID ) {
				$post_states[ "simple_error_page_for_$key" ] = $value;
			}
		}

		return $post_states;
	}

	/**
	 * Summary of simple_error_pages_cpt
	 *
	 * @return void
	 */
	public function simple_error_pages_cpt() {
		register_post_type(
			'simple_error_pages',
			array(
				'labels'              => array(
					'name'          => __( 'Simple Error Pages', 'simple-error-pages' ),
					'singular_name' => __( 'Error Page', 'simple-error-pages' ),
					'add_new'       => __( 'Add New', 'simple-error-pages' ),
				),
				'public'              => true,
				'has_archive'         => false,
				'show_in_rest'        => true,
				'show_in_menu'        => 'tools.php',
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'hierarchical'        => true,

			)
		);
	}

	/**
	 * Summary of admin_styles
	 *
	 * @return void
	 */
	public function admin_styles() {

		$screen = get_current_screen();
		if ( 'edit-simple_error_pages' !== $screen->id ) {
			return;
		}
		wp_enqueue_style( 'simple-error-pages', plugins_url( 'assets/css/admin.css', SIMPLE_ERROR_PAGES_PLUGIN_FILE ), array(), 1, 'all' );
	}

	/**
	 * Summary of preview_column
	 *
	 * @param mixed $columns
	 *
	 * @return array
	 */
	public function preview_column( $columns ) {
		$columns = array(
			'cb'           => $columns['cb'],
			'title'        => $columns['title'],
			'preview_link' => __( 'Preview', 'simple-error-pages' ),
			'date'         => $columns['date'],
		);
		return $columns;
	}

	/**
	 * Summary of preview_link
	 *
	 * @param mixed $column
	 * @param mixed $post_id
	 *
	 * @return void
	 */
	public function preview_link( $column, $post_id ) {

		if ( 'preview_link' !== $column ) {
			return;
		}

		$all       = self::list();
		$page_name = null;
		$is_dropin = false;

		foreach ( $all as $key => $value ) {
			if ( isset( $value['id'] ) && ( $value['id'] == $post_id ) ) {
				$page_name = $key;
				$is_dropin = true;
				break;
			}
		}

		if ( ! $page_name ) {
			return;
		}
		$post_status = get_post_status( $post_id );
		$path        = trailingslashit( WP_CONTENT_DIR ) . $page_name . '.php';
		$url         = trailingslashit( WP_CONTENT_URL ) . $page_name . '.php';

		if ( $is_dropin ) {
			if ( file_exists( $path ) ) {
				echo '<a target="_blank" href="' . esc_url( $url ) . '">
				<svg style="fill: currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M19.5 4.5h-7V6h4.44l-5.97 5.97 1.06 1.06L18 7.06v4.44h1.5v-7Zm-13 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-3H17v3a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h3V5.5h-3Z"></path></svg></a>';
			} elseif ( 'publish' != $post_status ) {
				echo '<span class="button button-small button-disabled">' . esc_html__( 'Edit the Page and Publish it so see the preview link here.', 'simple-error-pages' ) . '</span>';
			} else {
				echo '<span class="button button-small button-disabled">' . esc_html__( 'Page Edit/Update is required to create the error page. Once the page is created, preview link will appear here.', 'simple-error-pages' ) . '</span>';
			}
		}
	}
}
