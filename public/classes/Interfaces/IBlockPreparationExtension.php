<?php

namespace Palasthotel\WordPress\Headless\Interfaces;

interface IBlockPreparationExtension {

	function blockName(): string;

	function prepare( array $block ): array;
}