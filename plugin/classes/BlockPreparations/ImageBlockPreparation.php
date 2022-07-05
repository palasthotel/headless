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

		if(isset($block["attrs"]) && isset($block["attrs"]["id"])){
			$imageId = $block["attrs"]["id"];
			PostContentAttachmentCollector::add(get_the_ID(), $imageId);
			$block["attrs"] = static::addAttachmentAttributes($imageId, $block["attrs"], $block["innerHTML"]);
		}

		unset($block["innerHTML"]);
		unset($block["innerContent"]);

		return $block;
	}

	public static function addAttachmentAttributes($id, $attrs, $innerHTML){
		$attrs["src"] = wp_get_attachment_image_src($id, 'full');
		$attrs["alt"] = get_post_meta($id, '_wp_attachment_image_alt', true);
		$attrs["caption"] = str_replace(
			["\n","\r"],
			'',
			wp_kses($innerHTML, ["br" => [], 'b' => [], 'em' => [], 'i' => [], 'a' => ['href' => [], 'title' => []], 'strong' => []])
		);
		return $attrs;
	}
}