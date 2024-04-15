<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
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

		if ( current_user_can( 'administrator' ) ) {
			echo '<a href="' . esc_url( admin_url( 'post-new.php?post_type=simple_error_pages' ) ) . '" class="page-title-action button-secondary">' . esc_html__( 'Add New', 'simple-error-pages' ) . '</a>';
		}
		?>

	</div>

	<div class="settings-area">
		<button onclick="toggle_utility_options()"><span class="dashicons dashicons-admin-generic"></span>
			Settings</button>
	</div>
</div>
<div id="simple-error-pages-options-wrapper" class="hidden">
	<?php
	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error( 'simple_error_pages_settings', 'simple_error_pages_settings_message', __( 'Settings Saved. Should the Error Page appear unstyled, please make edits and update it with some changes. This action will trigger a rebuild of the error page.', 'simple-error-pages' ), 'updated' );
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