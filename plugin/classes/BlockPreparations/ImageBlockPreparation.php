<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Extensions\FeaturedMedia;
use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparation;
use Palasthotel\WordPress\Headless\Model\BlockName;
use Palasthotel\WordPress\Headless\Model\PostContentAttachmentCollector;
use WP_HTML_Tag_Processor;

class ImageBlockPreparation implements IBlockPreparation {

	function blockName(): BlockName {
		return new BlockName("core", "image");
	}

	function prepare( array $block ): array {

		if(isset($block["attrs"]) && isset($block["attrs"]["id"])){
			$imageId = $block["attrs"]["id"];
			PostContentAttachmentCollector::add(get_the_ID(), $imageId);
			$innerHTML = (isset($block["innerHTML"])) ? $block["innerHTML"] : "";
			$block["attrs"] = static::addAttachmentAttributes($imageId, $block["attrs"], $innerHTML);
		}

		unset($block["innerHTML"]);
		unset($block["innerContent"]);

		return $block;
	}

	public static function addAttachmentAttributes($id, $attrs, $innerHTML){

		$tags = new WP_HTML_Tag_Processor( $innerHTML );
		$tags->next_tag(['tag_name' => 'img']);
		// alt set in the core/image block settings
		$block_alt = $tags->get_attribute('alt');
		$alt = empty($block_alt) ? get_post_meta($id, '_wp_attachment_image_alt', true) : $block_alt;

		$attrs["src"] = wp_get_attachment_image_src($id, 'full');

		$attrs["sizes"] = FeaturedMedia::imageSizes($id);

		$attrs["alt"] = $alt;
		$attrs["caption"] = str_replace(
			["\n","\r"],
			'',
			wp_kses($innerHTML, ["br" => [], 'b' => [], 'em' => [], 'i' => [], 'a' => ['href' => [], 'title' => []], 'strong' => []])
		);
		return $attrs;
	}
}
