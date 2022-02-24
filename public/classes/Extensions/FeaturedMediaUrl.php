<?php

namespace Palasthotel\WordPress\Headless\Extensions;

use Palasthotel\WordPress\Headless\Components\Component;
use WP_Post;
use WP_REST_Request;
use WP_REST_Response;

class FeaturedMediaUrl extends Component {

	public function init(){
		$post_types = get_post_types( [ "public" => true ] );
		foreach ( $post_types as $type ) {
			add_filter( 'rest_prepare_' . $type, [ $this, 'extend' ], 99, 3 );
		}
	}


	public function extend( WP_REST_Response $response, WP_Post $post, WP_REST_Request $request ) {

		$data = $response->get_data();
		// $data["title"]["rendered"] = html_entity_decode($data["title"]["rendered"]);
		$data["featured_media_url"] = get_the_post_thumbnail_url($post, "full");

		$response->set_data( $data );

		return $response;
	}
}