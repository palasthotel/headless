<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparation;
use Palasthotel\WordPress\Headless\Model\BlockName;

/**
 * Normalizes the legacy "core-embed/wordpress" block name to "core/embed".
 *
 * Handles blocks saved with the old "core-embed" namespace so they are
 * treated as standard core/embed blocks by subsequent preparations.
 */
class CoreEmbedBlockPreparation implements IBlockPreparation {

	/**
	 * Returns the legacy block name this preparation targets.
	 *
	 * @return BlockName The "core-embed/wordpress" block name.
	 */
	function blockName(): ?BlockName {
		return new BlockName("core-embed", "wordpress");
	}

	/**
	 * Rewrites the block name from "core-embed/wordpress" to "core/embed".
	 *
	 * @param array $block The parsed block data.
	 * @return array The block with the corrected name.
	 */
	function prepare( array $block ): array {

		$block["blockName"] = "core/embed";

		return $block;
	}
}