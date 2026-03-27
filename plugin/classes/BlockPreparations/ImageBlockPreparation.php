<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Extensions\FeaturedMedia;
use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparation;
use Palasthotel\WordPress\Headless\Model\BlockName;
use Palasthotel\WordPress\Headless\Model\PostContentAttachmentCollector;
use WP_HTML_Tag_Processor;

/**
 * Prepares core/image blocks with attachment data including src, sizes, alt, and caption.
 *
 * Registers the attachment with the PostContentAttachmentCollector and strips
 * innerHTML and innerContent from the block.
 */
class ImageBlockPreparation implements IBlockPreparation {

	/**
	 * Returns the block name this preparation targets.
	 *
	 * @return BlockName The "core/image" block name.
	 */
	function blockName(): BlockName {
		return new BlockName("core", "image");
	}

	/**
	 * Enriches the image block with attachment metadata and removes raw HTML fields.
	 *
	 * @param array $block The parsed block data.
	 * @return array The block with src, sizes, alt, and caption attrs added.
	 */
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

	/**
	 * Adds attachment-related attributes (src, sizes, alt, caption) to a block attrs array.
	 *
	 * @param int    $id        The attachment ID.
	 * @param array  $attrs     The existing block attributes.
	 * @param string $innerHTML The block innerHTML, used to extract alt and caption.
	 * @return array The enriched attributes array.
	 */
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
