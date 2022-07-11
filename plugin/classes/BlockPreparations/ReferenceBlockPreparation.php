<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparation;
use Palasthotel\WordPress\Headless\Model\BlockName;

class ReferenceBlockPreparation implements IBlockPreparation {

	function blockName(): ?BlockName {
		return new BlockName("core", "block");
	}

	function prepare( array $block ): array {

		if(!empty($block["attrs"]) && !empty($block["attrs"]["ref"])){
			$post = get_post($block["attrs"]["ref"]);
			if($post instanceof \WP_Post && $post->post_type == 'wp_block'){
				$blocks = parse_blocks($post->post_content);
				if(count($blocks) == 1){
					$theBlock = $blocks[0];
					$theBlock["block_reference_id"] = $post->ID;
					return $theBlock;
				}
			}

		}

		return $block;
	}
}