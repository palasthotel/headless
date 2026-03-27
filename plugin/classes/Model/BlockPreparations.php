<?php

namespace Palasthotel\WordPress\Headless\Model;

use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparation;

/**
 * Collection of IBlockPreparation instances.
 *
 * Used to register and retrieve all active block preparation handlers.
 */
class BlockPreparations {

	/**
	 * @var IBlockPreparation[]
	 */
	private array $items = [];

	/**
	 * Adds a block preparation handler to the collection.
	 *
	 * @param IBlockPreparation $extension The block preparation to add.
	 * @return void
	 */
	public function add( IBlockPreparation $extension ) {
		$this->items[] = $extension;
	}

	/**
	 * Returns all registered block preparation handlers.
	 *
	 * @return IBlockPreparation[] The registered handlers.
	 */
	public function get(){
		return $this->items;
	}

}