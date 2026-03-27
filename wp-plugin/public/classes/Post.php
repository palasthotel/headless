<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;
use Palasthotel\WordPress\Headless\Extensions\FeaturedMedia;
use WP_Term;

/**
 * Handles post data preparation for the headless REST API response.
 *
 * Extends the default post response with featured media details, taxonomy terms,
 * and other post fields needed by the headless frontend.
 */
class Post extends Component {
	public function onCreate(): void {
		parent::onCreate();
		add_filter( Plugin::FILTER_PREPARE_POST, [ $this, 'prepare_post' ], 10, 2 );
	}

	/**
	 * Determines whether the given post type is a headless post type.
	 *
	 * @param string $postType The post type slug to check.
	 * @return bool True if the post type is considered headless, filterable via Plugin::FILTER_IS_HEADLESS_POST_TYPE.
	 */
	public function isHeadlessPostType(string $postType){
		return apply_filters(Plugin::FILTER_IS_HEADLESS_POST_TYPE, true, $postType);
	}

	/**
	 * Filters the post response array to include headless-specific fields.
	 *
	 * Adds id, type, title, slug, featured media data, excerpt, and REST-visible taxonomy terms.
	 *
	 * @param array            $response   The existing response data array.
	 * @param int|\WP_Post     $id_or_post A post ID or WP_Post object.
	 * @return array The merged response array with headless fields appended.
	 */
	public function prepare_post( array $response, $id_or_post ): array {
		$post            = get_post( $id_or_post );
		$featuredImageId = get_post_thumbnail_id( $post );
		$postJson        = array_merge(
			$response,
			[
				"id"                   => $post->ID,
				"type"                 => $post->post_type,
				"title"                => $post->post_title,
				"slug"                 => $post->post_name,
				"featured_media"       => $featuredImageId,
				"featured_media_url"   => get_the_post_thumbnail_url( $post, "full" ),
				"featured_media_src"   => wp_get_attachment_image_src( $featuredImageId, "full" ),
				"featured_media_sizes" => FeaturedMedia::imageSizes( $featuredImageId ),
				"excerpt"              => $post->post_excerpt,
			]
		);
		$taxonomies      = get_object_taxonomies( $post, 'objects' );
		foreach ( $taxonomies as $taxonomy ) {
			if ( ! $taxonomy->show_in_rest ) {
				continue;
			}
			$terms = get_the_terms( $post, $taxonomy->name );
			$taxonomyKey = is_string($taxonomy->rest_base) ? $taxonomy->rest_base : $taxonomy->name;
			if ( is_array( $terms ) && is_string( $taxonomyKey ) ) {
				$postJson[ $taxonomyKey] = array_map( function ( $term ) {
					/**
					 * @var WP_Term
					 */
					return [
						"id"     => $term->term_id,
						"name"   => $term->name,
						"slug"   => $term->slug,
						"parent" => $term->parent,
						"count"  => $term->count,
					];
				}, $terms );
			}
		}

		return $postJson;
	}
}
