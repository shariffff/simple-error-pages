<?php
/**
 * Creates the dropins
 *
 * @package simple_error_pages
 */

namespace SEPages;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Summary of Dropins
 */
class Dropins {

	/**
	 * Summary of register
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'save_post_simple_error_pages', array( $this, 'create' ), 10, 2 );
	}

	/**
	 * Summary of create
	 *
	 * @param mixed $post_ID ID of the WordPress Post.
	 * @param mixed $post Post Content.
	 *
	 * @return void
	 */
	public function create( $post_ID, $post ) {

		$error_pages = Pages::list();

		if ( ! in_array( $post_ID, array_column( $error_pages, 'id' ), true ) ) {
			return;
		}

		if ( wp_is_post_revision( $post_ID ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}

		if ( 'publish' !== $post->post_status ) {
			return;
		}

		$files = array();

		foreach ( $error_pages as $page => $attr ) {
			if ( isset( $attr['id'] ) && absint( $attr['id'] ) === $post_ID ) {
				$files[] = "$page.php";
			}
		}

		$content = do_blocks( $post->post_content );
		$title   = apply_filters( 'the_title', $post->post_title );

		ob_start();
		echo '<!DOCTYPE html>
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta name="robots" content="noindex">
					<title>' . esc_html( $title ) . '</title>
				';
		remove_filter( 'wp_robots', 'wp_robots_max_image_preview_large' );
		remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
		wp_enqueue_style( 'wp-block-library' );
		wp_dequeue_style( 'dashicons' );
		wp_deregister_style( 'dashicons' );
		wp_dequeue_script( 'common' );
		wp_dequeue_script( 'admin-bar' );
		remove_action( 'wp_head', 'wp_enqueue_admin_bar_bump_styles' );
		wp_head();
		echo '</head><body>';
		echo wp_kses_post( $content );
		wp_footer();
		echo '</body></html>';

		$html     = ob_get_clean();
		$content  = '<?php
		http_response_code(503);
		header( "X-Robots-Tag: noindex" );
		 ?>';
		$content .= $html;

		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();

		if ( ! is_wp_error( $wp_filesystem ) ) {
			foreach ( $files as $file ) {
				$wp_filesystem->put_contents( trailingslashit( WP_CONTENT_DIR ) . $file, $content, FS_CHMOD_FILE );
			}
		}
	}
}
