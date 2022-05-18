<?php

namespace Palasthotel\WordPress\Headless\Extensions;

use Palasthotel\WordPress\Headless\Model\PostContentAttachmentCollector;
use WP_Post;
use WP_REST_Request;
use WP_REST_Response;

class PostContentAttachments extends AbsPostExtensionPost {

	function response( WP_REST_Response $response, WP_Post $post, WP_REST_Request $request ): WP_REST_Response {
		$data = $response->get_data();
		if(empty($data["meta"]) || !is_array($data["meta"])){
			$data["meta"] = [];
		}

		$data["meta"]["headless_post_content_attachment_ids"] = PostContentAttachmentCollector::get($post->ID);
		$response->set_data( $data );
		return $response;
	}
}