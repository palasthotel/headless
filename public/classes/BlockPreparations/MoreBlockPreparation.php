<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparationExtension;

class MoreBlockPreparation implements IBlockPreparationExtension {

	function blockName(): string {
		return "core/more";
	}

	function prepare( array $block ): array {
		return [
			"blockName" => $block["blockName"],
		];
	}
}