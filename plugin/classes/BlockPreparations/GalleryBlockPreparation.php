<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparation;
use Palasthotel\WordPress\Headless\Model\BlockName;
use Palasthotel\WordPress\Headless\Model\PostContentAttachmentCollector;

class GalleryBlockPreparation implements IBlockPreparation {

	function blockName(): BlockName {
		return new BlockName("core", "gallery");
	}

	function prepare( array $block ): array {

		unset($block["innerContent"]);
		unset($block["innerHTML"]);

		$ids = [];
		if(!empty($block["innerBlocks"]) && is_array($block["innerBlocks"])){
			foreach ($block["innerBlocks"] as $imageBlock){
				if("core/image" != $imageBlock["blockName"]) continue;
				if(empty($imageBlock["attrs"]) || !is_array($imageBlock["attrs"])) continue;
				$attrs = $imageBlock["attrs"];
				if(!isset($attrs["id"])) continue;
				$ids[] = intval($attrs["id"]);
				PostContentAttachmentCollector::add(get_the_ID(), $attrs["id"]);
			}
		}

		$block["attrs"]["ids"] = $ids;

		return $block;
	}
}