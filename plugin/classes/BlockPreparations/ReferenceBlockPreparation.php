<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparation;
use Palasthotel\WordPress\Headless\Model\BlockName;

/**
 * Resolves core/block (reusable block) references by inlining the referenced block content.
 *
 * When a block has a "ref" attribute pointing to a wp_block post, this preparation
 * fetches and parses its content and replaces the reference block's innerBlocks.
 * Must run before all other preparations so the inlined blocks can be further processed.
 */
class ReferenceBlockPreparation implements IBlockPreparation {

	/**
	 * Returns the block name this preparation targets.
	 *
	 * @return BlockName The "core/block" block name.
	 */
	function blockName(): ?BlockName {
		return new BlockName("core", "block");
	}

	/**
	 * Inlines the referenced reusable block content into innerBlocks.
	 *
	 * @param array $block The parsed block data.
	 * @return array The block with innerBlocks replaced by the referenced block's content.
	 */
	function prepare( array $block ): array {

		if(!empty($block["attrs"]) && !empty($block["attrs"]["ref"])){
			$post = get_post($block["attrs"]["ref"]);
			if($post instanceof \WP_Post && $post->post_type == 'wp_block'){
				$blocks = parse_blocks($post->post_content);
				unset($block["attrs"]);
				unset($block["innerHTML"]);
				unset($block["innerContent"]);
				$block["innerBlocks"] = $blocks;
			}

		}

		return $block;
	}
}
