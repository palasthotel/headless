<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Extensions\FeaturedMedia;
use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparation;
use Palasthotel\WordPress\Headless\Model\BlockName;
use Palasthotel\WordPress\Headless\Model\PostContentAttachmentCollector;

/**
 * Prepares core/tag-cloud blocks by resolving and embedding term data.
 *
 * Fetches terms using block attributes (taxonomy, numberOfTags) and adds
 * them as a "tags" array within the block's attributes. Removes innerBlocks,
 * innerHTML, and innerContent from the output.
 */
class TagCloudPreparation implements IBlockPreparation {

	/**
	 * Returns the block name this preparation targets.
	 *
	 * @return BlockName The "core/tag-cloud" block name.
	 */
	function blockName(): BlockName {
		return new BlockName("core", "tag-cloud");
	}

	/**
	 * Resolves taxonomy terms and embeds them in the block attributes.
	 *
	 * @param array $block The parsed block data.
	 * @return array The block with a "tags" attribute and raw HTML fields removed.
	 */
	function prepare( array $block ): array {

		if(isset($block["attrs"]) && is_array($block["attrs"])){
			$attrs = $block["attrs"];
			$args = [
				"echo" => false,
				"format" => "array",
				'orderby' => 'count',
				'order'   => 'DESC',
			];
			if(!empty($attrs["taxonomy"])){
				$args["taxonomy"] = $attrs["taxonomy"];
			}
			if(!empty($attrs["numberOfTags"])){
				$args["number"] = $attrs["numberOfTags"];
			}
			$block["attrs"]["tags"] = array_map(function(\WP_Term $term){
				return [
					"term_id" => $term->term_id,
					"name" => $term->name,
					"slug" => $term->slug,
					"description" => $term->description,
					"count" => $term->count,
					"parent" => $term->parent,
				];
			},get_terms($args));
		}

		unset($block["innerBlocks"]);
		unset($block["innerHTML"]);
		unset($block["innerContent"]);

		return $block;
	}

	/**
	 * Adds attachment-related attributes (src, sizes, alt, caption) to a block attrs array.
	 *
	 * @param int    $id        The attachment ID.
	 * @param array  $attrs     The existing block attributes.
	 * @param string $innerHTML The block innerHTML, used for caption extraction.
	 * @return array The enriched attributes array.
	 */
	public static function addAttachmentAttributes($id, $attrs, $innerHTML){
		$attrs["src"] = wp_get_attachment_image_src($id, 'full');

		$attrs["sizes"] = FeaturedMedia::imageSizes($id);

		$attrs["alt"] = get_post_meta($id, '_wp_attachment_image_alt', true);
		$attrs["caption"] = str_replace(
			["\n","\r"],
			'',
			wp_kses($innerHTML, ["br" => [], 'b' => [], 'em' => [], 'i' => [], 'a' => ['href' => [], 'title' => []], 'strong' => []])
		);
		return $attrs;
	}
}