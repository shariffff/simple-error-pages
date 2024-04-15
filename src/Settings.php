<?php

namespace SEPages;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings {

	public function register() {
		add_filter( 'in_admin_header', [ $this, 'load_settings_view' ] );
		add_filter( 'screen_options_show_screen', [ $this, 'disable_for_utility_screen' ] );
		add_action( 'admin_init', [ $this, 'settings_init' ] );
		add_action( 'update_option_simple_error_pages', [ $this, 'create_dropin' ], 10, 2 );
	}

	public function create_dropin( $old, $new ) {
		$rebuild = array_filter( array_column( $new, 'id' ) );
		if ( ! $rebuild ) {
			return;
		}
		foreach ( $rebuild as $page ) {
			( new Dropins )->create( $page, get_post( $page ) );
		}

	}

	public static function is_simple_error_pages_screen() {
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			return 'edit-simple_error_pages' === $screen->id;
		}
		return false;
	}

	public function disable_for_utility_screen(): bool {
		return ! self::is_simple_error_pages_screen();
	}
	public function settings_init() {
		register_setting( 'simple_error_pages_settings', 'simple_error_pages' );
		add_settings_section( 'simple_error_pages_settings_section', '', '__return_null', 'simple_error_pages_settings' );
		add_settings_field( 'simple_error_pages_settings_field', '', [ $this, 'render_field' ], 'simple_error_pages_settings', 'simple_error_pages_settings_section' );

	}
	public function render_field() {
		$setting = Pages::list();
		$states = [ 
			'db-error' => 'Database Error',
			'php-error' => 'PHP Error',
			'maintenance' => 'Maintenance',
		];
		foreach ( $states as $key => $value ) {

			$option_name = "simple_error_pages[$key][id]";
			$selected = array_key_exists( $key, $setting ) ? $setting[ $key ]['id'] : 0;
			?>
			<tr>
				<th scope="row">
					<label for="<?php echo esc_attr( $option_name ) ?>">
						<?php echo esc_html( $value ); ?>
					</label>
				</th>
				<td>

					<?php
					wp_dropdown_pages( [ 
						'name' => esc_attr( $option_name ),
						'show_option_none' => __( '&mdash; Select &mdash;', 'simple-error-pages' ),
						'option_none_value' => '0',
						'selected' => esc_attr( $selected ),
						'post_type' => 'simple_error_pages',
					] );
					?>

				</td>
			</tr>
		<?php }
	}
	public function load_settings_view() {
		if ( self::is_simple_error_pages_screen() ) {
			require_once plugin_dir_path( SIMPLE_ERROR_PAGES_PLUGIN_FILE ) . '/templates/settings.php';
		}

	}

}
