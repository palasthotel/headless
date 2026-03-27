<?php

namespace Palasthotel\WordPress\Headless\Interfaces;

use WP_Post;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Contract for extensions that modify the REST API response for posts.
 */
interface IPostRouteExtension {

	/**
	 * Modifies the REST response for a post.
	 *
	 * @param WP_REST_Response $response The current REST response.
	 * @param WP_Post          $post     The post object.
	 * @param WP_REST_Request  $request  The current REST request.
	 * @return WP_REST_Response The modified response.
	 */
	function response( WP_REST_Response $response, WP_Post $post, WP_REST_Request $request): WP_REST_Response;
}