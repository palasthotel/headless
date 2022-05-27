<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

class Links extends Component {

	public function onCreate() {
		parent::onCreate();

		// TODO: find a solid way to change links
		add_filter( 'post_link', [ $this, 'post_link' ], 10, 3 );
		add_filter( 'post_type_link', [ $this, 'post_type_link' ], 10, 4 );
		add_filter( 'preview_post_link', [ $this, 'preview_post_link' ], 10, 2 );
	}

	public function post_link( string $permalink, \WP_Post $post, bool $leavename ) {
		if($post->post_status == "publish"){
			return $this->modifyPostLinkUrl($permalink, $post);
		}
		return $this->preview_post_link($permalink, $post);
	}

	public function post_type_link( string $link, \WP_Post $post, bool $leavename, bool $isSample ) {
		if($isSample) return $link;
		if($post->post_status == "publish"){
			return $this->modifyPostLinkUrl($link, $post);
		}
		return $this->preview_post_link($link, $post);
	}

	public function modifyPostLinkUrl($link, \WP_Post $post): string{
		return apply_filters(
			Plugin::FILTER_POST_LINK,
			$this->removeHomeUrl($link),
			$post,
			$link
		);
	}

	private function removeHomeUrl(string $link): string {
		return str_replace( trailingslashit(home_url()), "/", $link );
	}

	public function preview_post_link( string $link, \WP_Post $post ) {
		return apply_filters(
			Plugin::FILTER_PREVIEW_URL,
			untrailingslashit(HEADLESS_HEAD_BASE_URL)."/api/preview?post={$post->ID}&post_type=$post->post_type&secret_token=".HEADLESS_SECRET_TOKEN,
			$post,
			$link
		);
	}

}