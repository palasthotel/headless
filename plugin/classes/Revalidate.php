<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;
use Palasthotel\WordPress\Headless\Model\Frontend;

class Revalidate extends Component {

	public function onCreate() {
		parent::onCreate();
		add_action('save_post', [$this, 'save_post']);
		add_action('edit_comment', [$this, 'edit_comment']);
	}

	public function save_post($post_id){
		if(wp_is_post_revision($post_id)) return;
		$this->plugin->dbRevalidation->addPost($post_id);
	}

	public function edit_comment($comment_id){
		$comment = get_comment($comment_id);
		$this->plugin->dbRevalidation->addPost($comment->comment_post_ID);
	}

	/**
	 * @param $post_id
	 *
	 * @return (\WP_Error|true)[]
	 */
	function revalidatePost($post_id) {
		$frontends = $this->plugin->headquarter->getFrontends();
		$results = [];
		foreach ($frontends as $frontend){
			$results[] = $this->revalidateByPostId($frontend, $post_id);
		}
		return $results;
	}

	function revalidateByPostId(Frontend $frontend, $post_id) {
		$url = untrailingslashit($frontend->getBaseUrl())."/api/revalidate?secret_token=".HEADLESS_SECRET_TOKEN."&post=".$post_id;
		return $this->executeRavalidation(
			apply_filters(Plugin::FILTER_REVALIDATE_BY_POST_ID_URL, $url, $post_id, $frontend)
		);
	}

	function revalidateByPath(Frontend $frontend, $path){
		$url = untrailingslashit($frontend->getBaseUrl())."/api/revalidate?secret_token=".HEADLESS_SECRET_TOKEN."&path=".urlencode($path);
		return $this->executeRavalidation(
			apply_filters(Plugin::FILTER_REVALIDATE_BY_PATH_URL, $url, $path, $frontend)
		);
	}

	private function executeRavalidation($finalUrl){
		$url = add_query_arg('invalidate___cache', time(),$finalUrl);
		$result = wp_remote_get($url);

		if($result instanceof \WP_Error) return $result;

		return true;
	}


}
