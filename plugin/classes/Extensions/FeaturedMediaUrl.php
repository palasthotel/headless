<?php

namespace Palasthotel\WordPress\Headless\Extensions;

use WP_Post;
use WP_REST_Request;
use WP_REST_Response;

class FeaturedMediaUrl extends AbsPostExtensionPost {

	function response( WP_REST_Response $response, WP_Post $post, WP_REST_Request $request ): WP_REST_Response {
		$data = $response->get_data();
		// $data["title"]["rendered"] = html_entity_decode($data["title"]["rendered"]);

		$data["featured_media_url"] = get_the_post_thumbnail_url($post, "full");

		$response->set_data( $data );

		return $response;
	}
}