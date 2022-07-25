<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

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


}