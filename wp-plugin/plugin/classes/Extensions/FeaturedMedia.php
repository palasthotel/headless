<?php

namespace Palasthotel\WordPress\Headless\Extensions;

use Palasthotel\WordPress\Headless\Model\PostContentAttachmentCollector;
use WP_Post;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Extends the post REST response with detailed featured media information.
 *
 * Adds featured_media_url, featured_media_src, featured_media_sizes,
 * featured_media_caption, featured_media_description, and featured_media_alt fields.
 */
class FeaturedMedia extends AbsPostExtensionPost {

	/**
	 * Appends featured media data to the post REST response.
	 *
	 * @param WP_REST_Response $response The current REST response.
	 * @param WP_Post          $post     The post object.
	 * @param WP_REST_Request  $request  The current REST request.
	 * @return WP_REST_Response The modified response with featured media fields added.
	 */
	function response( WP_REST_Response $response, WP_Post $post, WP_REST_Request $request ): WP_REST_Response {
		$data = $response->get_data();

		$id = get_post_thumbnail_id( $post );
		if ( $id ) {
			PostContentAttachmentCollector::add( $post->ID, $id );
		}
		$data["featured_media_url"] = get_the_post_thumbnail_url( $post, "full" );

		$src                        = wp_get_attachment_image_src( $id, 'full' );
		$data["featured_media_src"] = $src;

		$data["featured_media_sizes"] = self::imageSizes( $id );

		$attachment                         = get_post( $id );
		$data["featured_media_caption"]     = $attachment instanceof WP_Post ? $attachment->post_excerpt : false;
		$data["featured_media_description"] = $attachment instanceof WP_Post ? $attachment->post_content : false;
		$alt                                = get_post_meta( $id, '_wp_attachment_image_alt', true );
		$data["featured_media_alt"]         = is_string( $alt ) ? $alt : false;


		$response->set_data( $data );

		return $response;
	}

	/**
	 * Returns all available intermediate image size sources for the given attachment.
	 *
	 * @param int $imageId The attachment ID.
	 * @return array[] Array of wp_get_attachment_image_src result arrays for each size.
	 */
	static function imageSizes( $imageId ) {
		return array_values(
			array_filter(
				array_map(
					function ( $size ) use ( $imageId ) {
						return wp_get_attachment_image_src( $imageId, $size );
					},
					get_intermediate_image_sizes()
				),
				function ( $item ) {
					return is_array( $item );
				}
			)
		);
	}
}