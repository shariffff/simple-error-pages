<?php
/**
 * Handle settings
 *
 * @package simple_error_pages
 */

namespace SEPages;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Summary of Settings
 */
class Settings {

	/**
	 * Summary of register
	 *
	 * @return void
	 */
	public function register() {
		add_filter( 'in_admin_header', array( $this, 'load_settings_view' ) );
		add_filter( 'screen_options_show_screen', array( $this, 'disable_for_utility_screen' ) );
		add_action( 'admin_init', array( $this, 'settings_init' ) );
		add_action( 'update_option_simple_error_pages', array( $this, 'create_dropin' ), 10, 2 );
	}

	/**
	 * Summary of create_dropin
	 *
	 * @param mixed $old Old settings value.
	 * @param mixed $new_value Current settings value.
	 *
	 * @return void
	 */
	public function create_dropin( $old, $new_value ) {
		$rebuild = array_filter( array_column( $new_value, 'id' ) );
		if ( ! $rebuild ) {
			return;
		}
		foreach ( $rebuild as $page ) {
			( new Dropins() )->create( $page, get_post( $page ) );
		}
	}

	/**
	 * Summary of is_simple_error_pages_screen
	 *
	 * @return bool
	 */
	public static function is_simple_error_pages_screen() {
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			return 'edit-simple_error_pages' === $screen->id;
		}
		return false;
	}

	/**
	 * Summary of disable_for_utility_screen
	 *
	 * @return bool
	 */
	public function disable_for_utility_screen(): bool {
		return ! self::is_simple_error_pages_screen();
	}
	/**
	 * Summary of settings_init
	 *
	 * @return void
	 */
	public function settings_init() {
		register_setting( 'simple_error_pages_settings', 'simple_error_pages' );
		add_settings_section( 'simple_error_pages_settings_section', '', '__return_null', 'simple_error_pages_settings' );
		add_settings_field( 'simple_error_pages_settings_field', '', array( $this, 'render_field' ), 'simple_error_pages_settings', 'simple_error_pages_settings_section' );
	}
	/**
	 * Summary of render_field
	 *
	 * @return void
	 */
	public function render_field() {
		$setting = Pages::list();
		$states  = array(
			'db-error'    => 'Database Error',
			'php-error'   => 'PHP Error',
			'maintenance' => 'Maintenance',
		);
		foreach ( $states as $key => $value ) {

			$option_name = "simple_error_pages[$key][id]";
			$selected    = array_key_exists( $key, $setting ) ? $setting[ $key ]['id'] : 0;
			?>
			<tr>
				<th scope="row">
					<label for="<?php echo esc_attr( $option_name ); ?>">
						<?php echo esc_html( $value ); ?>
					</label>
				</th>
				<td>

					<?php
					wp_dropdown_pages(
						array(
							'name'              => esc_attr( $option_name ),
							'show_option_none'  => __( '&mdash; Select &mdash;', 'simple-error-pages' ),
							'option_none_value' => '0',
							'selected'          => esc_attr( $selected ),
							'post_type'         => 'simple_error_pages',
						)
					);
					?>

				</td>
			</tr>
			<?php
		}
	}
	/**
	 * Summary of load_settings_view
	 *
	 * @return void
	 */
	public function load_settings_view() {
		if ( self::is_simple_error_pages_screen() ) {
			require_once plugin_dir_path( SIMPLE_ERROR_PAGES_PLUGIN_FILE ) . '/templates/settings.php';
		}
	}
}
