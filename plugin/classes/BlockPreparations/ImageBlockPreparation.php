<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparation;
use Palasthotel\WordPress\Headless\Model\BlockName;
use Palasthotel\WordPress\Headless\Model\PostContentAttachmentCollector;

class ImageBlockPreparation implements IBlockPreparation {

	function blockName(): BlockName {
		return new BlockName("core", "image");
	}

	function prepare( array $block ): array {

		unset($block["innerHTML"]);
		unset($block["innerContent"]);

		if(isset($block["attrs"]) && isset($block["attrs"]["id"])){
			PostContentAttachmentCollector::add(get_the_ID(), $block["attrs"]["id"]);
		}


		return $block;
	}
}