<?php

namespace Palasthotel\WordPress\Headless;

/**
 * Sets cache-control headers on REST API responses for headless requests.
 *
 * Applies a stale-while-revalidate caching strategy to REST responses
 * when the request is identified as a headless request.
 */
class Headers extends Components\Component {
	public function onCreate(): void {
		parent::onCreate();
		add_filter('rest_post_dispatch', [$this, 'rest_post_dispatch'], 10, 3);
	}

	/**
	 * Adds Cache-Control headers to the REST response for headless requests.
	 *
	 * Only for anonymous read requests. "public" on a response that was rendered
	 * for a logged-in user lets a shared cache in front of WordPress hand that
	 * user's data to the next visitor, and appending the headless query parameter
	 * to an authenticated request is something anyone can do.
	 *
	 * @param \WP_REST_Response $response The REST response object.
	 * @param \WP_REST_Server   $server   The REST server instance.
	 * @param \WP_REST_Request  $request  The request that produced the response.
	 * @return \WP_REST_Response The modified response with cache headers applied.
	 */
	public function rest_post_dispatch(\WP_REST_Response $response, $server = null, $request = null){
		$isReadRequest = ! ( $request instanceof \WP_REST_Request )
			|| in_array( $request->get_method(), [ 'GET', 'HEAD' ], true );

		if($this->plugin->security->isHeadlessRequest() && ! is_user_logged_in() && $isReadRequest){
			$headers = $response->get_headers();
			// use cache while we are revalidating in the background
			$headers["Cache-Control"] = "max-age=300, public, stale-while-revalidate=604800";
			$headers = apply_filters(Plugin::FILTER_REST_RESPONSE_HEADERS, $headers, $headers);
			$response->set_headers($headers);
		}
		return $response;
	}
}
