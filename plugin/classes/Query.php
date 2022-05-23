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

		if ( isset( $_GET["hl_meta_exists"] ) ) {
			$metaExists         = sanitize_text_field( $_GET["hl_meta_exists"] );
			$args['meta_query'] = array(
				array(
					'key'     => $metaExists,
					'compare' => 'EXISTS',
				),
			);
		}
		if ( isset( $_GET["hl_meta_not_exists"] ) ) {
			$metaNotExists      = sanitize_text_field( $_GET["hl_meta_not_exists"] );
			$args['meta_query'] = array(
				array(
					'key'     => $metaNotExists,
					'compare' => 'NOT EXISTS',
				),
			);
		}

		return $args;
	}
}