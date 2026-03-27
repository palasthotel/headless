<?php

namespace Palasthotel\WordPress\Headless\Interfaces;

use Palasthotel\WordPress\Headless\Model\BlockName;

/**
 * Contract for block preparation handlers.
 *
 * Implementations transform a parsed Gutenberg block array before it is
 * included in the headless REST API response.
 */
interface IBlockPreparation {

	/**
	 * Returns the block name this preparation applies to, or null to apply to all blocks.
	 *
	 * @return BlockName|null The target block name, or null for a catch-all preparation.
	 */
	function blockName(): ?BlockName;

	/**
	 * Transforms the block data array.
	 *
	 * @param array $block The parsed block data.
	 * @return array The modified block data.
	 */
	function prepare( array $block ): array;
}