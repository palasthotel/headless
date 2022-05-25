<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

class Revalidate extends Component {



	public function onCreate() {
		parent::onCreate();
		add_action('save_post', [$this, 'save_post']);
	}

	public function save_post($post_id){
		if(wp_is_post_revision($post_id)) return;
		$baseUrl = (empty(HEADLESS_HEAD_BASE_URL)) ? home_url() : HEADLESS_HEAD_BASE_URL;
		$url = untrailingslashit($baseUrl)."/api/revalidate?secret_token=".HEADLESS_SECRET_TOKEN."&post=".$post_id;
		// TODO: enqueue in db for revalidation

	}
}