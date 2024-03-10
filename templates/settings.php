<script>
	function toggle_utility_options() {
		document.getElementById('simple-error-pages-options-wrapper').classList.toggle('hidden')
	}
</script>

<div id="simple-error-pages-settings-wrapper">
	<div class="simple-error-pages-settings">
		<h1 class="wp-heading-inline">
			<?php echo esc_html( get_admin_page_title() ); ?>
		</h1>

		<?php
		global $post_new_file, $post_type_object;
		if ( isset( $post_new_file ) && current_user_can( 'administrator' ) ) {
			echo '<a href="' . esc_url( admin_url( $post_new_file ) ) . '" class="page-title-action button-secondary">' . esc_html( $post_type_object->labels->add_new ) . '</a>';
		}
		?>

	</div>

	<div class="settings-area">
		<button onclick="toggle_utility_options()"><span class="dashicons dashicons-admin-generic"></span>
			Settings</button>
	</div>
</div>
<div id="simple-error-pages-options-wrapper" class="hidden">
	<div class="notice notice-large inline">Make sure to <b>update</b> the corresponding page once making a change here.
	</div>
	<?php
	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error( 'simple_error_pages_settings', 'simple_error_pages_settings_message', __( 'Settings Saved', 'simple-error-pages' ), 'updated' );
	}

	settings_errors( 'simple_error_pages_settings' );
	?>
	<form method="post" action="options.php" novalidate="novalidate">
		<?php
		settings_fields( 'simple_error_pages_settings' );

		do_settings_sections( 'simple_error_pages_settings' );

		submit_button( 'Save Changes' );
		?>
	</form>
</div>