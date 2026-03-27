<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparation;
use Palasthotel\WordPress\Headless\Model\BlockName;

/**
 * Resolves oEmbed data for core/embed blocks.
 *
 * Fetches the oEmbed HTML for the block's URL and adds "isResolved" and
 * "resolvedHTML" attributes. Removes innerContent from the block data.
 */
class EmbedBlockPreparation implements IBlockPreparation {

	/**
	 * Returns the block name this preparation targets.
	 *
	 * @return BlockName The "core/embed" block name.
	 */
	function blockName(): ?BlockName {
		return new BlockName("core", "embed");
	}

	/**
	 * Resolves the oEmbed HTML for the block URL and strips innerContent.
	 *
	 * @param array $block The parsed block data.
	 * @return array The block with "isResolved" and "resolvedHTML" attrs added.
	 */
	function prepare( array $block ): array {

		if ( ! empty( $block["attrs"] ) && !empty($block["attrs"]["url"]) ) {
			$url = $block["attrs"]["url"];
			$oembed = wp_oembed_get($url);
			$block["attrs"]["isResolved"] = $oembed !== false;
			$block["attrs"]["resolvedHTML"] = $oembed;
		}

		unset($block["innerContent"]);

		return $block;
	}
}