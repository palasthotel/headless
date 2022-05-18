<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparationExtension;
use Palasthotel\WordPress\Headless\Utils;

class ParagraphBlockPreparation implements IBlockPreparationExtension {

	function blockName(): string {
		return "core/paragraph";
	}

	function prepare( array $block ): array {
		$block["value"] = Utils::prepareHTML( $block["innerHTML"] );

		return $block;
	}
}