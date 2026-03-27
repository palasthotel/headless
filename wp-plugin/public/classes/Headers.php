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
		add_filter('rest_post_dispatch', [$this, 'rest_post_dispatch']);
	}

	/**
	 * Adds Cache-Control headers to the REST response for headless requests.
	 *
	 * @param \WP_REST_Response $response The REST response object.
	 * @return \WP_REST_Response The modified response with cache headers applied.
	 */
	public function rest_post_dispatch(\WP_REST_Response $response){
		if($this->plugin->security->isHeadlessRequest()){
			$headers = $response->get_headers();
			// use cache while we are revalidating in the background
			$headers["Cache-Control"] = "max-age=300, public, stale-while-revalidate=604800";
			$headers = apply_filters(Plugin::FILTER_REST_RESPONSE_HEADERS, $headers, $headers);
			$response->set_headers($headers);
		}
		return $response;
	}
}
