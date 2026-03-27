<?php

namespace Palasthotel\WordPress\Headless\Model;

use Palasthotel\WordPress\Headless\Interfaces\IPostRouteExtension;

/**
 * Collection of IPostRouteExtension instances.
 *
 * Used to register and retrieve all active post route extension handlers.
 */
class PostRouteExtensions {

	/**
	 * @var IPostRouteExtension[] $postRouteExtensions
	 */
	private array $items = [];

	/**
	 * Adds a post route extension to the collection.
	 *
	 * @param IPostRouteExtension $extension The extension to add.
	 * @return void
	 */
	public function add( IPostRouteExtension $extension ) {
		$this->items[] = $extension;
	}

	/**
	 * Returns all registered post route extensions.
	 *
	 * @return IPostRouteExtension[] The registered extensions.
	 */
	public function get(){
		return $this->items;
	}


}