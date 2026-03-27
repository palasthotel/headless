<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparation;
use Palasthotel\WordPress\Headless\Model\BlockName;

/**
 * Prepares core/paragraph blocks by removing the redundant innerContent field.
 */
class ParagraphBlockPreparation implements IBlockPreparation {

	/**
	 * Returns the block name this preparation targets.
	 *
	 * @return BlockName The "core/paragraph" block name.
	 */
	function blockName(): ?BlockName {
		return new BlockName("core", "paragraph");
	}

	/**
	 * Removes innerContent from the paragraph block.
	 *
	 * @param array $block The parsed block data.
	 * @return array The block with innerContent removed.
	 */
	function prepare( array $block ): array {

		unset($block["innerContent"]);

		return $block;
	}
}