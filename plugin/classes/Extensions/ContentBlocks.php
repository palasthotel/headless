<?php

namespace Palasthotel\WordPress\Headless\Extensions;

use Palasthotel\WordPress\Headless\Model\BlockPreparations;
use Palasthotel\WordPress\Headless\Plugin;
use WP_Post;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Extends the post REST response with parsed and prepared Gutenberg block data.
 *
 * Adds a "headless_blocks" field to the content object when the post has blocks.
 * Skips blocks and other heavy fields when the request uses the teasers variant.
 * Applies all registered block preparations during parsing.
 */
class ContentBlocks extends AbsPostExtensionPost {

	/**
	 * @var BlockPreparations The registered block preparation handlers.
	 */
	private BlockPreparations $preparations;

	/**
	 * @param BlockPreparations $preparations The block preparations collection to use.
	 */
	public function __construct(BlockPreparations $preparations) {
		parent::__construct();
		$this->preparations = $preparations;
		add_filter( Plugin::FILTER_BLOCKS_PREPARE_FILTER, function ( $blockName, $block ) {
			return $blockName != null || !empty(trim($block["innerHTML"]));
		}, 10, 2);
	}

	/**
	 * Adds parsed block data to the post REST response.
	 *
	 * @param WP_REST_Response $response The current REST response.
	 * @param WP_Post          $post     The post object.
	 * @param WP_REST_Request  $request  The current REST request.
	 * @return WP_REST_Response The modified response with "headless_blocks" added to content.
	 */
	function response( WP_REST_Response $response, WP_Post $post, WP_REST_Request $request ): WP_REST_Response {
		$data = $response->get_data();

		if(Plugin::instance()->security->isHeadlessRequestVariant(HEADLESS_REST_VARIANT_TEASERS_VALUE)){
			unset($data["content"]["rendered"]);
			unset($data["content"]["headless_blocks"]);
			unset($data["yoast_head"]);
			unset($data["yoast_head_json"]);
		} else if ( has_blocks( $post ) ) {
			$data["content"]["headless_blocks"] = $this->parse( $post->post_content );
		} else {
			$data["content"]["headless_blocks"] = false;
		}

        $data = apply_filters(Plugin::FILTER_REST_RESPONSE_DATA, $data );
        $response->set_data( $data );

		return $response;
	}

	/**
	 * Filters out blocks that should not be included in the response.
	 *
	 * Uses the Plugin::FILTER_BLOCKS_PREPARE_FILTER filter to determine inclusion.
	 *
	 * @param array $blocks The raw array of parsed blocks.
	 * @return array The filtered blocks with sequential keys.
	 */
	private function filterBlocks( $blocks ) {
		return array_values( array_filter( $blocks, function ( $block ) {
			return apply_filters( Plugin::FILTER_BLOCKS_PREPARE_FILTER, $block["blockName"], $block );
		} ) );
	}

	/**
	 * Parses raw post content into a prepared array of blocks.
	 *
	 * @param string $post_content The raw block content string.
	 * @param int    $level        The current nesting depth (1 for top-level).
	 * @return array The prepared block array.
	 */
	private function parse($post_content, $level = 1): array {
		$blocks = parse_blocks($post_content);
		return $this->prepare($blocks, $level);
	}

	/**
	 * Filters and prepares a set of blocks by applying registered preparations recursively.
	 *
	 * @param array $blocks The blocks to prepare.
	 * @param int   $level  The current nesting depth.
	 * @return array The prepared blocks.
	 */
	private function prepare( $blocks, $level ) {
		$blocks = $this->filterBlocks( $blocks );

		return array_map( function ( $block ) use ( $level ) {

			foreach ($this->preparations->get() as $extension){
				if($extension->blockName() == $block["blockName"]){
					$block = $extension->prepare($block);
				}
			}

			if (
				isset( $block["innerBlocks"] ) &&
				is_array( $block["innerBlocks"] ) &&
				count( $block["innerBlocks"] )
			) {
				$block["innerBlocks"] = $this->prepare( $block["innerBlocks"], $level + 1 );
			}

			return apply_filters( Plugin::FILTER_BLOCKS_PREPARE_BLOCK, $block, $level, $block );
		}, $blocks );
	}

}