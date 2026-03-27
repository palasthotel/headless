<?php

namespace Palasthotel\WordPress\Headless\Interfaces;

use WP_REST_Request;
use WP_REST_Response;

/**
 * Contract for extensions that modify the REST API response for taxonomy terms.
 */
interface ITermRouteExtension {

	/**
	 * @return string[]
	 */
	function taxonomies(): array;

	/**
	 * Modifies the REST response for a term.
	 *
	 * @param WP_REST_Response $response The current REST response.
	 * @param \WP_Term         $comment  The term object.
	 * @param WP_REST_Request  $request  The current REST request.
	 * @return WP_REST_Response The modified response.
	 */
	function response( WP_REST_Response $response, \WP_Term $comment, WP_REST_Request $request): WP_REST_Response;
}
