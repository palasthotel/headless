<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparation;
use Palasthotel\WordPress\Headless\Model\BlockName;

/**
 * Processes free-form (classic) blocks by running shortcodes and stripping innerContent.
 *
 * Returns null from blockName() to apply to all blocks without a specific name.
 */
class FreeFormBlockPreparation implements IBlockPreparation {

	/**
	 * Returns null to indicate this preparation applies to all blocks.
	 *
	 * @return null
	 */
	function blockName(): ?BlockName {
		return null;
	}

	/**
	 * Runs shortcodes on innerHTML and removes innerContent from the block.
	 *
	 * @param array $block The parsed block data.
	 * @return array The block with shortcodes processed and innerContent removed.
	 */
	function prepare( array $block ): array {

		if ( ! empty( $block["innerHTML"] ) && is_string( $block["innerHTML"] ) ) {
			$block["innerHTML"] = do_shortcode( $block["innerHTML"] );
		}

		unset($block["innerContent"]);

		return $block;
	}
}