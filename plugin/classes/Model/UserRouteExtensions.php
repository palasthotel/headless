<?php

namespace Palasthotel\WordPress\Headless\Model;

use Palasthotel\WordPress\Headless\Interfaces\IUserRouteExtension;

/**
 * Collection of IUserRouteExtension instances.
 *
 * Used to register and retrieve all active user route extension handlers.
 */
class UserRouteExtensions {

	/**
	 * @var IUserRouteExtension[]
	 */
	private array $items = [];

	/**
	 * Adds a user route extension to the collection.
	 *
	 * @param IUserRouteExtension $extension The extension to add.
	 * @return void
	 */
	public function add( IUserRouteExtension $extension ) {
		$this->items[] = $extension;
	}

	/**
	 * Returns all registered user route extensions.
	 *
	 * @return IUserRouteExtension[] The registered extensions.
	 */
	public function get(){
		return $this->items;
	}


}
