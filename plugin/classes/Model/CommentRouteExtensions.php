<?php

namespace Palasthotel\WordPress\Headless\Model;

use Palasthotel\WordPress\Headless\Interfaces\ICommentRouteExtension;

/**
 * Collection of ICommentRouteExtension instances.
 *
 * Used to register and retrieve all active comment route extension handlers.
 */
class CommentRouteExtensions {

	/**
	 * @var ICommentRouteExtension[]
	 */
	private array $items = [];

	/**
	 * Adds a comment route extension to the collection.
	 *
	 * @param ICommentRouteExtension $extension The extension to add.
	 * @return void
	 */
	public function add( ICommentRouteExtension $extension ) {
		$this->items[] = $extension;
	}

	/**
	 * Returns all registered comment route extensions.
	 *
	 * @return ICommentRouteExtension[] The registered extensions.
	 */
	public function get(){
		return $this->items;
	}


}
