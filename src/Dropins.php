<?php

namespace SimpleErrorPages;

class Dropins {

	public function register() {
		add_action( 'save_post_simple_error_pages', [ $this, 'create' ], 10, 2 );
	}

	public function create( $post_ID, $post ) {

		$error_pages = Pages::list();

		if ( ! in_array( $post_ID, array_column( $error_pages, 'id' ) ) ) {
			return;
		}

		if ( wp_is_post_revision( $post_ID ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}

		if ( $post->post_status != 'publish' ) {
			return;
		}

		$files = [];

		foreach ( $error_pages as $page => $attr ) {
			if ( isset( $attr['id'] ) && intval( $attr['id'] ) === $post_ID ) {
				$files[] = "$page.php";
			}
		}
		$title = apply_filters( 'the_title', $post->post_title );
		$content = apply_filters( 'the_content', $post->post_content );
		ob_start();
		echo '<!DOCTYPE html>
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta name="robots" content="noindex">
					<title>' . $title . '</title>
				';
		wp_head();
		echo '</head><body>';
		echo $content;
		wp_footer();
		echo '</body></html>';


		$html = ob_get_clean();
		$content = '<?php
		http_response_code(503);
		header( "X-Robots-Tag: noindex" );
		 ?>';
		$content .= $html;
		foreach ( $files as $file ) {
			file_put_contents( trailingslashit( WP_CONTENT_DIR ) . $file, $content );
		}

	}

}
