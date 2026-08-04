<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

/**
 * Extends REST API queries with custom meta and post type filtering parameters.
 *
 * Adds support for filtering REST posts queries by meta key/value pairs,
 * meta existence checks, and multiple post types via custom request parameters.
 * Only active on requests with valid API key access.
 */
class Query extends Component {

	const META_KEYS = "hl_meta_keys";
	const META_VALUES = "hl_meta_values";
	const META_COMPARES = "hl_meta_compares";
	const META_EXISTS = "hl_meta_exists";
	const META_NOT_EXISTS = "hl_meta_not_exists";
	const META_RELATION = "hl_meta_relation";
	const POST_TYPE = "hl_post_type";

	public function onCreate(): void {
		parent::onCreate();

		if ( ! $this->plugin->security->hasApiKeyAccess() ) {
			return;
		}

		foreach ( get_post_types( [ 'show_in_rest' => true, "public" => true ] ) as $post_type ) {
			add_filter( 'rest_' . $post_type . '_query', [ $this, 'rest_query' ], 10, 2 );
		}
	}

	/**
	 * Extracts and validates the post types requested via the hl_post_type parameter.
	 *
	 * @param \WP_REST_Request $request The current REST request.
	 * @return string[] An array of validated post type slugs, or ["any"] if requested.
	 */
	public static function getRequestPostTypes( \WP_REST_Request $request ) {
		$post_types = $request->get_param( static::POST_TYPE );

		if ( empty( $post_types ) ) {
			return [];
		}

		// "any" used to be passed straight through to WP_Query, which resolves it to
		// every post type that is not excluded from search - including ones that are
		// not viewable and not exposed in the REST API.
		if ( is_array( $post_types ) && in_array( "any", $post_types, true ) ) {
			return array_values( get_post_types( [ "public" => true, "show_in_rest" => true ] ) );
		}

		if ( is_string( $post_types ) ) {
			$post_types = [ $post_types ];
		}

		return array_values( array_filter( $post_types, function ( $type ) {
			return is_string( $type ) && post_type_exists( $type ) && is_post_type_viewable( $type );
		} ) );
	}

	/**
	 * Checks whether a meta key may be used in a query for the current request.
	 *
	 * Protected keys (WordPress treats a leading underscore as protected) are only
	 * queryable for users who may edit posts. Without that, an anonymous request
	 * could use the public posts endpoint as an oracle: a LIKE comparison on a
	 * protected key reveals its value one character at a time, from which posts come
	 * back and which do not.
	 *
	 * @param string $key The meta key requested.
	 * @return bool True if the key may be queried.
	 */
	public static function isMetaKeyQueryable( string $key ): bool {
		$queryable = ! is_protected_meta( $key, 'post' ) || current_user_can( 'edit_posts' );

		return (bool) apply_filters( Plugin::FILTER_META_KEY_IS_QUERYABLE, $queryable, $key );
	}

	/**
	 * Filters the REST query arguments to apply meta and post type parameters.
	 *
	 * Processes hl_meta_keys, hl_meta_values, hl_meta_compares, hl_meta_exists,
	 * hl_meta_not_exists, hl_meta_relation, and hl_post_type request parameters.
	 *
	 * @param array             $args    The current WP_Query arguments.
	 * @param \WP_REST_Request  $request The current REST request.
	 * @return array The modified query arguments.
	 */
	public function rest_query( array $args, \WP_REST_Request $request ) {

		$metas = $request->get_param(static::META_KEYS);
		$values = $request->get_param(static::META_VALUES);
		$compares = $request->get_param(static::META_COMPARES);
		$comparesMap = [
			"eq" => "=",
			"neq" => "!=",
			"like" => "LIKE",
		];
		$validCompares = array_keys($comparesMap);
		$meta_query = [];
		if(!empty($metas) && is_array($metas)){
			foreach ($metas as $index =>  $metaKey) {
				$metaKey = sanitize_text_field($metaKey);
				if(!static::isMetaKeyQueryable($metaKey)) continue;
				$compare = "=";
				if(is_array($compares) && !empty($compares[$index]) && in_array($compares[$index], $validCompares)){
					$compare = $comparesMap[$compares[$index]];
				}
				if(is_array($values) && isset($values[$index])){
					$meta_query[] = [
						"key" => $metaKey,
						"value" => sanitize_text_field($values[$index]),
						"compare" => $compare,
					];
				}

			}
		}

		$metaExists = $request->get_param( static::META_EXISTS );
		$metaExists = is_string( $metaExists ) ? sanitize_text_field( $metaExists ) : "";
		if ( ! empty( $metaExists ) && static::isMetaKeyQueryable( $metaExists ) ) {
			$meta_query[] = array(
				array(
					'key'     => $metaExists,
					'compare' => 'EXISTS',
				),
			);
		}

		$metaNotExists = $request->get_param( static::META_NOT_EXISTS );
		$metaNotExists = is_string( $metaNotExists ) ? sanitize_text_field( $metaNotExists ) : "";
		if ( ! empty( $metaNotExists ) && static::isMetaKeyQueryable( $metaNotExists ) ) {
			$meta_query[] = array(
				array(
					'key'     => $metaNotExists,
					'compare' => 'NOT EXISTS',
				),
			);
		}

		if(count($meta_query) > 0){
			$relation = "AND";
			if($request->get_param(static::META_RELATION) == "OR"){
				$relation = "OR";
			}
			$meta_query['relation'] = $relation;
			$args['meta_query'] = $meta_query;
		}


		$post_types = static::getRequestPostTypes( $request );
		if ( ! empty( $post_types ) ) {
			$args['post_type'] = $post_types;
		}

		return $args;
	}

}
