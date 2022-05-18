<?php

namespace Palasthotel\WordPress\Headless\BlockPreparations;

use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparationExtension;

class ImageBlockPreparation implements IBlockPreparationExtension {

	function blockName(): string {
		return "core/image";
	}

	function prepare( array $block ): array {

		$attributes = $block["attrs"];
		if ( empty( $attributes["id"] ) ) {
			return $block;
		}
		$id                = $attributes["id"];
		$block["caption"]  = wp_get_attachment_caption( $id );
		$block["imageUrl"] = wp_get_attachment_image_url( $id, "full" );

		return $block;
	}
}