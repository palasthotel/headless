<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

class Query extends Component {

	const META_EXISTS = "hl_meta_exists";
	const META_NOT_EXISTS = "hl_meta_not_exists";
	const POST_TYPE = "hl_post_type";

	public function onCreate() {
		parent::onCreate();

		if ( ! $this->plugin->security->hasApiKeyAccess() ) {
			return;
		}

		foreach ( get_post_types( [ 'show_in_rest' => true, "public" => true ] ) as $post_type ) {
			add_filter( 'rest_' . $post_type . '_query', [ $this, 'rest_query' ], 10, 2 );
		}
	}

	public static function getRequestPostTypes( \WP_REST_Request $request ) {
		$post_types = $request->get_param( static::POST_TYPE );
		if ( empty( $post_types ) ) {
			return [];
		}
		if ( is_string( $post_types ) ) {
			return [ $post_types ];
		}

		return array_filter( $post_types, function ( $type ) {
			return post_type_exists( $type ) && is_post_type_viewable( $type );
		} );
	}

	public function rest_query( array $args, \WP_REST_Request $request ) {
		$metaExists = $request->get_param( static::META_EXISTS );
		if ( ! empty( $metaExists ) ) {
			$args['meta_query'] = array(
				array(
					'key'     => sanitize_text_field( $metaExists ),
					'compare' => 'EXISTS',
				),
			);
		}

		$metaNotExists = $request->get_param( static::META_NOT_EXISTS );
		if ( ! empty( $metaNotExists ) ) {
			$args['meta_query'] = array(
				array(
					'key'     => sanitize_text_field( $metaNotExists ),
					'compare' => 'NOT EXISTS',
				),
			);
		}

		$post_types = static::getRequestPostTypes( $request );
		if ( ! empty( $post_types ) ) {
			$args['post_type'] = $post_types;
		}

		return $args;
	}

}