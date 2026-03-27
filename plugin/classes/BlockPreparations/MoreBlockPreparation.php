<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparation;
use Palasthotel\WordPress\Headless\Model\BlockName;

/**
 * Strips all data from core/more blocks, keeping only the block name.
 *
 * The "read more" block has no meaningful content for the headless frontend,
 * so its presence alone is surfaced as a marker.
 */
class MoreBlockPreparation implements IBlockPreparation {

	/**
	 * Returns the block name this preparation targets.
	 *
	 * @return BlockName The "core/more" block name.
	 */
	function blockName(): BlockName {
		return new BlockName("core", "more");
	}

	/**
	 * Returns a minimal block array containing only the block name.
	 *
	 * @param array $block The parsed block data.
	 * @return array A block array with only the "blockName" key.
	 */
	function prepare( array $block ): array {
		return [
			"blockName" => $block["blockName"],
		];
	}
}