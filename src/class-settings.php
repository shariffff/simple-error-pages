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
 * Responsible for plugin settings.
 */
class Settings {

	/**
	 * Registers the settings for the plugin.
	 *
	 * This method adds filters and actions to register the settings and handle related functionality.
	 *
	 * @return void
	 */
	public function register() {
		add_filter( 'in_admin_header', array( $this, 'load_settings_view' ) );
		add_filter( 'screen_options_show_screen', array( $this, 'disable_for_utility_screen' ) );
		add_action( 'admin_init', array( $this, 'settings_init' ) );
		add_action( 'update_option_simple_error_pages', array( $this, 'create_dropin' ), 10, 2 );
		add_filter( 'plugin_action_links_' . SIMPLE_ERROR_PAGES_PLUGIN_BASE, array( $this, 'action_links' ) );
	}

	/**
	 * Creates drop-ins for error pages.
	 *
	 * This method is responsible for creating drop-ins for error pages based on the provided new values.
	 * It takes an array of new values and filters out the ones that have an 'id' property.
	 * If there are no values with an 'id' property, the method returns early.
	 * Otherwise, it iterates over the filtered values and creates a drop-in for each page using the Dropins class.
	 *
	 * @param mixed $old The old value.
	 * @param array $new_value The new value containing an array of error pages.
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
	 * Checks if the current screen is the Simple Error Pages screen.
	 *
	 * @return bool True if the current screen is the Simple Error Pages screen, false otherwise.
	 */
	public static function is_simple_error_pages_screen() {
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			return 'edit-simple_error_pages' === $screen->id;
		}
		return false;
	}

	/**
	 * Determines whether the utility screen should be disabled for the Simple Error Pages plugin.
	 *
	 * @return bool Returns true if the utility screen should be disabled, false otherwise.
	 */
	public function disable_for_utility_screen(): bool {
		return ! self::is_simple_error_pages_screen();
	}
	/**
	 * Initializes the settings for the Simple Error Pages plugin.
	 *
	 * @return void
	 */
	public function settings_init() {
		register_setting( 'simple_error_pages_settings', 'simple_error_pages' );
		add_settings_section( 'simple_error_pages_settings_section', '', '__return_null', 'simple_error_pages_settings' );
		add_settings_field( 'simple_error_pages_settings_field', '', array( $this, 'render_field' ), 'simple_error_pages_settings', 'simple_error_pages_settings_section' );
	}
	/**
	 * Renders the plugin options markup.
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
							'show_option_none'  => esc_attr( '&mdash; Select &mdash;' ),
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
	 * Loads the settings view if the current screen is the Simple Error Pages screen.
	 *
	 * @return void
	 */
	public function load_settings_view() {
		if ( self::is_simple_error_pages_screen() ) {
			require_once plugin_dir_path( SIMPLE_ERROR_PAGES_PLUGIN_FILE ) . '/templates/settings.php';
		}
	}

	/**
	 * Sets plugin action link
	 *
	 * @param array $links An array of existing action links for the plugin.
	 * @return array Modified array of action links.
	 */
	public static function action_links( $links ) {
		$url = 'edit.php?post_type=simple_error_pages';
		return array_merge(
			array( '<a href="' . admin_url( $url ) . '">Pages</a>' ),
			$links
		);
	}
}
