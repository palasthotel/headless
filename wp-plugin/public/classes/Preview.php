<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;
use WP_Post;

/**
 * Manages the headless preview functionality for WordPress posts.
 *
 * Overrides the standard WordPress preview link to redirect editors to the
 * headless frontend preview URL via an AJAX action. Supports enabling and
 * disabling preview functionality through a filter.
 */
class Preview extends Component {

	const POST_ID_PLACEHOLDER = "{{post_id}}";

	public function onCreate(): void {
		parent::onCreate();

		add_filter( 'preview_post_link', [ $this, 'preview_post_link' ], 10, 2 );
		add_action( 'wp_ajax_headless_preview', [ $this, 'admin_preview' ], 10, 2 );
		add_action( 'wp_ajax_nopriv_headless_preview', [ $this, 'no_permission' ] );
		add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	/**
	 * Builds the WordPress admin-ajax.php redirect URL for headless preview.
	 *
	 * @param int|null $id The post ID, or null to use the placeholder string.
	 * @return string The admin AJAX URL for the headless preview action.
	 */
	public function getRedirectLink( $id ) {
		if($id == null){
			$id = self::POST_ID_PLACEHOLDER;
		}

		return rtrim(get_admin_url(),"/") . "/admin-ajax.php?action=headless_preview&post=$id";
	}

	/**
	 * Returns the path segment for the headless frontend preview endpoint.
	 *
	 * @return string The preview path including the secret token query parameter.
	 */
    public function getHeadlessPreviewPath(){
        return "/api/preview?secret_token=".HEADLESS_SECRET_TOKEN;
    }

    /**
     * @param WP_Post|null $post
     * @return string
     */
	public function getHeadlessPreviewLink( $post = null ) {

        $path = $this->getHeadlessPreviewPath();

		$link = untrailingslashit( HEADLESS_HEAD_BASE_URL ) . $path;

        if($post instanceof WP_Post){
            $link .= "&post=$post->ID&post_type=$post->post_type";
        }

		return apply_filters( Plugin::FILTER_PREVIEW_URL, $link, $post, $link );
	}

	/**
	 * Filters the preview post link to redirect to the headless frontend preview.
	 *
	 * Only applies to headless post types; returns the original link for others.
	 *
	 * @param string  $link The default WordPress preview link.
	 * @param WP_Post $post The post being previewed.
	 * @return string The headless redirect URL or the original link.
	 */
	public function preview_post_link( string $link, WP_Post $post ) {

		if(!$this->plugin->post->isHeadlessPostType($post->post_type)) return $link;

		return apply_filters(
			Plugin::FILTER_PREVIEW_REDIRECT_URL,
			$this->plugin->preview->getRedirectLink( $post->ID ),
			$post,
			$link
		);
	}

	/**
	 * Handles the headless_preview AJAX action for logged-in users.
	 *
	 * Validates the post exists and the user has edit permission, then
	 * redirects to the headless frontend preview URL.
	 *
	 * @return void
	 */
	public function admin_preview() {
		$postId = intval( $_GET["post"] );
		$post   = get_post( $postId );
		if ( ! ( $post instanceof WP_Post ) ) {
			echo "Post not found";
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

	/**
	 * Handles the headless_preview AJAX action for unauthenticated users.
	 *
	 * Returns a 403 Forbidden response.
	 *
	 * @return void
	 */
	public function no_permission() {
		header('HTTP/1.0 403 Forbidden');
		die('Missing permission to access this area!');
	}

	/**
	 * Removes preview hooks when preview functionality is inactive.
	 *
	 * @return void
	 */
	public function plugins_loaded() {
		if($this->isPreviewInactive()){
			remove_filter( 'preview_post_link', [ $this, 'preview_post_link' ] );
			remove_action( 'wp_ajax_headless_preview', [ $this, 'admin_preview' ] );
			remove_action( 'wp_ajax_nopriv_headless_preview', [ $this, 'no_permission' ] );
		}
	}

	/**
	 * Checks whether preview functionality is inactive.
	 *
	 * @return bool True if preview is inactive.
	 */
	function isPreviewInactive() {
		return !$this->isPreviewActive();
	}
	/**
	 * Checks whether preview functionality is active.
	 *
	 * @return bool True if preview is active, filterable via Plugin::FILTER_PREVIEW_IS_ACTIVE.
	 */
	function isPreviewActive() {
		return apply_filters(Plugin::FILTER_PREVIEW_IS_ACTIVE, true);
	}

}
