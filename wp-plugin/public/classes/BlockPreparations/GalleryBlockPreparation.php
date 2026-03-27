<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparation;
use Palasthotel\WordPress\Headless\Model\BlockName;
use Palasthotel\WordPress\Headless\Model\PostContentAttachmentCollector;

/**
 * Prepares core/gallery blocks by extracting image IDs and attachment attributes.
 *
 * Handles both modern gallery blocks (innerBlocks) and legacy gallery markup (innerHTML).
 * Registers attachment IDs with the PostContentAttachmentCollector and removes
 * raw HTML from the block data.
 */
class GalleryBlockPreparation implements IBlockPreparation {

	/**
	 * Returns the block name this preparation targets.
	 *
	 * @return BlockName The "core/gallery" block name.
	 */
	function blockName(): BlockName {
		return new BlockName("core", "gallery");
	}

	/**
	 * Extracts image IDs, builds inner image blocks for legacy markup, and cleans up raw HTML.
	 *
	 * @param array $block The parsed block data.
	 * @return array The prepared block with ids attribute and innerBlocks populated.
	 */
	function prepare( array $block ): array {

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
		} else if(!empty($block["innerHTML"])){
			preg_match_all('/data-id="(\d+)"/m', $block["innerHTML"], $matches, PREG_SET_ORDER, 0);

			foreach ($matches as $match) {
				$id = intval($match[1]);
				$ids[] = $id;
				PostContentAttachmentCollector::add(get_the_ID(), $id);
				$attrs = ["id" => $id];
				$attrs = ImageBlockPreparation::addAttachmentAttributes($id, $attrs, "");
				$block["innerBlocks"][] = [
					"blockName" => "core/image",
					"attrs" => $attrs,
				];
			}
		}

		unset($block["innerHTML"]);
		unset($block["innerContent"]);
		$block["attrs"]["ids"] = $ids;

		return $block;
	}
}