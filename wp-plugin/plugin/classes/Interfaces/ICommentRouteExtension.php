<?php

namespace Palasthotel\WordPress\Headless\Interfaces;

use WP_REST_Request;
use WP_REST_Response;

/**
 * Contract for extensions that modify the REST API response for comments.
 */
interface ICommentRouteExtension {

	/**
	 * Modifies the REST response for a comment.
	 *
	 * @param WP_REST_Response $response The current REST response.
	 * @param \WP_Comment      $comment  The comment object.
	 * @param WP_REST_Request  $request  The current REST request.
	 * @return WP_REST_Response The modified response.
	 */
	function response( WP_REST_Response $response, \WP_Comment $comment, WP_REST_Request $request): WP_REST_Response;
}