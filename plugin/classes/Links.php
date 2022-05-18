<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

class Links extends Component {

	public function onCreate() {
		parent::onCreate();

		// TODO: find a solid way to change links
//		add_filter( 'post_link', [ $this, 'post_link' ], 10, 3 );
//		add_filter( 'page_link', [ $this, 'page_link' ], 10, 3 );
//		add_filter( 'post_type_link', [ $this, 'post_type_link' ], 10, 4 );
		//add_filter( 'preview_post_link', [ $this, 'preview_post_link' ], 10, 2 );
	}

	public function post_link( string $permalink, \WP_Post $post, bool $leavename ) {
		return $this->modifyPostLinkUrl($permalink, $post);
	}

	public function page_link( string $link, int $pageId, bool $isSample ) {
		if($isSample) return $link;
		return $this->modifyPostLinkUrl($link, get_post($pageId));
	}

	public function post_type_link( string $link, \WP_Post $post, bool $leavename, bool $isSample ) {
		if($isSample) return $link;
		return $this->modifyPostLinkUrl($link, $post);
	}

	public function modifyPostLinkUrl($link, \WP_Post $post): string{
		return apply_filters(
			Plugin::FILTER_POST_LINK,
			$this->removeHomeUrl($link),
			$link,
			$post
		);
	}

	private function removeHomeUrl(string $link): string {
		return str_replace( trailingslashit(home_url()), "/", $link );
	}

	public function preview_post_link( string $link, \WP_Post $post ) {
		return apply_filters(
			Plugin::FILTER_PREVIEW_URL,
			"/api/preview?post={$post->ID}&post_type=$post->post_type&security_token=".HEADLESS_PREVIEW_TOKEN,
			$post,
			$link
		);
	}

}