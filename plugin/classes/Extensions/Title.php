<?php

namespace Palasthotel\WordPress\Headless\Extensions;

use WP_Post;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Extends the post REST response by HTML-decoding the rendered title.
 *
 * Converts HTML entities in the title's "rendered" field to their plain text equivalents.
 */
class Title extends AbsPostExtensionPost {

	/**
	 * Decodes HTML entities in the post title before returning the response.
	 *
	 * @param WP_REST_Response $response The current REST response.
	 * @param WP_Post          $post     The post object.
	 * @param WP_REST_Request  $request  The current REST request.
	 * @return WP_REST_Response The modified response with decoded title.
	 */
	function response( WP_REST_Response $response, WP_Post $post, WP_REST_Request $request ): WP_REST_Response {
		$data = $response->get_data();
		$data["title"]["rendered"] = html_entity_decode($data["title"]["rendered"]);
		$response->set_data( $data );
		return $response;
	}
}