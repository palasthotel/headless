<?php

namespace Palasthotel\WordPress\Headless\Extensions;

use Palasthotel\WordPress\Headless\Model\PostContentAttachmentCollector;
use WP_Post;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Extends the post REST response with attachment IDs collected from post content.
 *
 * Adds a "headless_attachment_ids" field to the content object containing
 * all attachment IDs found in the post's blocks during preparation.
 */
class ContentAttachments extends AbsPostExtensionPost {

	/**
	 * Adds collected attachment IDs to the content field of the post REST response.
	 *
	 * @param WP_REST_Response $response The current REST response.
	 * @param WP_Post          $post     The post object.
	 * @param WP_REST_Request  $request  The current REST request.
	 * @return WP_REST_Response The modified response with "headless_attachment_ids" added.
	 */
	function response( WP_REST_Response $response, WP_Post $post, WP_REST_Request $request ): WP_REST_Response {
		$data = $response->get_data();
		$data["content"]["headless_attachment_ids"] = PostContentAttachmentCollector::get($post->ID);
		$response->set_data( $data );
		return $response;
	}
}