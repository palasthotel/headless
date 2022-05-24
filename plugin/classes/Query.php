<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

class Query extends Component {
	public function onCreate() {
		parent::onCreate();

		if ( ! $this->plugin->security->hasApiKeyAccess() ) {
			return;
		}

		foreach ( get_post_types( [ 'show_in_rest' => true, "public" => true ] ) as $post_type ) {
			add_filter( 'rest_' . $post_type . '_query', [ $this, 'rest_query' ], 10, 2 );
		}
	}

	public function rest_query( array $args, \WP_REST_Request $request ) {
		$metaExists = $request->get_param("hl_meta_exists");
		if ( !empty($metaExists) ) {
			$args['meta_query'] = array(
				array(
					'key'     => sanitize_text_field($metaExists),
					'compare' => 'EXISTS',
				),
			);
		}

		$metaNotExists = $request->get_param("hl_meta_exists");
		if ( !empty($metaNotExists) ) {
			$args['meta_query'] = array(
				array(
					'key'     => sanitize_text_field($metaNotExists),
					'compare' => 'NOT EXISTS',
				),
			);
		}

		$post_types = $request->get_param("hl_post_type");
		if( ! empty( $post_types ) ){
			if( is_string( $post_types ) ){
				$post_types = [$post_types];
			}
			$args[ 'post_type' ] = array_filter($post_types, function($type){
				return post_type_exists($type) && is_post_type_viewable($type);
			});
		}

		return $args;
	}
}