<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

class Preview extends Component {
	public function onCreate() {
		parent::onCreate();

		add_filter( 'preview_post_link', [ $this, 'preview_post_link' ], 10, 2 );
		add_filter( 'rest_prepare_post', [ $this, 'hack_fix_preview_link' ], 10, 2 );
		add_filter( 'rest_prepare_autosave', [ $this, 'hack_fix_preview_link' ], 10, 2 );
		add_filter( 'rest_prepare_revision', [ $this, 'hack_fix_preview_link' ], 10, 2 );
		add_action( 'wp_ajax_headless_preview', [ $this, 'admin_preview' ] );
		add_action( 'wp_ajax_nopriv_headless_preview', [ $this, 'redirect' ] );
	}

	public function getRedirectLink( $id ) {
		$postType = get_post_type( $id );

		return home_url() . "/wp-admin/admin-ajax.php?action=headless_preview&p=$id&post_type=$postType";
	}

	public function getHeadlessPreviewLink( \WP_Post $post ) {
		$link = untrailingslashit( HEADLESS_HEAD_BASE_URL ) . "/api/preview?post={$post->ID}&post_type=$post->post_type&secret_token=" . HEADLESS_SECRET_TOKEN;

		return apply_filters( Plugin::FILTER_PREVIEW_URL, $link, $post, $link );
	}

	public function preview_post_link( string $link, \WP_Post $post ) {
		return apply_filters(
			Plugin::FILTER_PREVIEW_URL,
			$this->plugin->preview->getRedirectLink( $post->ID ),
			$post,
			$link
		);
	}

	/**
	 * Hack Function that changes the preview link for draft articles,
	 * this must be removed when wordpress do the properly fix https://github.com/WordPress/gutenberg/issues/13998
	 *
	 * @param $response
	 * @param $post
	 *
	 * @return mixed
	 */
	function hack_fix_preview_link( $response, $post ) {
		if ( 'publish' !== $post->post_status ) {
			$response->data['link'] = get_preview_post_link( $post );
		}

		return $response;
	}

	public function admin_preview() {
		$postType = $_GET["post_type"];
		if ( ! post_type_exists( $postType ) ) {
			echo "Post type not exists";
			exit;
		}
		$postId = intval( $_GET["p"] );
		$post   = get_post( $postId );
		if ( ! ( $post instanceof \WP_Post ) ) {
			echo "Post not found";
			exit;
		}
		if ( $post->post_type != $postType ) {
			echo "That's weired";
			exit;
		}
		if ( ! current_user_can( "edit_post", $postId ) ) {
			echo "Sorry, not allowed";
			exit;
		}

		$redirect = $this->getHeadlessPreviewLink( $post );
		wp_redirect( $redirect );
		exit;
	}

	public function redirect() {

		$postId    = intval( $_GET["p"] );
		$permalink = get_permalink( $postId );
		if ( ! is_string( $permalink ) ) {
			wp_redirect( get_home_url() );
			exit;
		}
		wp_redirect( $permalink );
		exit;
	}


}
