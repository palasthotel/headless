<?php

namespace Palasthotel\WordPress\Headless\Extensions;

use Palasthotel\WordPress\Headless\Model\BlockPreparations;
use Palasthotel\WordPress\Headless\Plugin;
use WP_Post;
use WP_REST_Request;
use WP_REST_Response;

class ContentBlocks extends AbsPostExtensionPost {

	private BlockPreparations $preparations;

	public function __construct(BlockPreparations $preparations) {
		parent::__construct();
		$this->preparations = $preparations;
		add_filter( Plugin::FILTER_BLOCKS_PREPARE_FILTER, function ( $blockName ) {
			return $blockName != null;
		} );
	}

	function response( WP_REST_Response $response, WP_Post $post, WP_REST_Request $request ): WP_REST_Response {
		$data = $response->get_data();

		if ( has_blocks( $post ) ) {
			$data["content"]["rendered"] = false;
			$data["content"]["headless_blocks"] = $this->parse( $post->post_content );
		} else {
			$data["content"]["headless_blocks"] = false;
		}

		$response->set_data( $data );

		return $response;
	}

	private function filterBlocks( $blocks ) {
		return array_values( array_filter( $blocks, function ( $block ) {
			return apply_filters( Plugin::FILTER_BLOCKS_PREPARE_FILTER, $block["blockName"], $block );
		} ) );
	}

	private function parse($post_content, $level = 1): array {
		$blocks = parse_blocks($post_content);
		return $this->prepare($blocks, $level);
	}

	private function prepare( $blocks, $level ) {
		$noNulls = $this->filterBlocks( $blocks );

		return array_map( function ( $block ) use ( $level ) {

			if (
				isset( $block["innerBlocks"] ) &&
				is_array( $block["innerBlocks"] ) &&
				count( $block["innerBlocks"] )
			) {
				$block["innerBlocks"] = $this->prepare( $block["innerBlocks"], $level + 1 );
			}

			foreach ($this->preparations->get() as $extension){
				if($extension->blockName() == $block["blockName"]){
					$block = $extension->prepare($block);
				}
			}

			return apply_filters( Plugin::FILTER_BLOCKS_PREPARE_BLOCK, $block, $level, $block );
		}, $noNulls );
	}

}