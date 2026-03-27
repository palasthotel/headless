<?php

namespace Palasthotel\WordPress\Headless\Extensions;

use Palasthotel\WordPress\Headless\Interfaces\IPostRouteExtension;
use Palasthotel\WordPress\Headless\Plugin;

/**
 * Abstract base class for post route extensions that self-register via a filter.
 *
 * Subclasses automatically hook into Plugin::ACTION_REGISTER_POST_ROUTE_EXTENSIONS
 * on construction and add themselves to the extension array.
 */
abstract class AbsPostExtensionPost implements IPostRouteExtension {

	public function __construct() {
		add_filter(Plugin::ACTION_REGISTER_POST_ROUTE_EXTENSIONS, [$this, 'register']);
	}

	/**
	 * @param IPostRouteExtension[] $extensions
	 *
	 * @return IPostRouteExtension[]
	 */
	/**
	 * Appends this extension to the provided extensions array.
	 *
	 * @param IPostRouteExtension[] $extensions The existing list of post route extensions.
	 * @return IPostRouteExtension[] The extensions array with this instance appended.
	 */
	public function register(array $extensions){
		$extensions[] = $this;
		return $extensions;
	}
}