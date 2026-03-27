<?php

namespace Palasthotel\WordPress\Headless\Model;

use Palasthotel\WordPress\Headless\Interfaces\ITermRouteExtension;

/**
 * Collection of ITermRouteExtension instances.
 *
 * Used to register and retrieve all active term route extension handlers.
 */
class TermRouteExtensions {

	/**
	 * @var ITermRouteExtension[]
	 */
	private array $items = [];

	/**
	 * Adds a term route extension to the collection.
	 *
	 * @param ITermRouteExtension $extension The extension to add.
	 * @return void
	 */
	public function add( ITermRouteExtension $extension ) {
		$this->items[] = $extension;
	}

	/**
	 * Returns all registered term route extensions.
	 *
	 * @return ITermRouteExtension[] The registered extensions.
	 */
	public function get(){
		return $this->items;
	}


}
