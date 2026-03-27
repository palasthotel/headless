<?php


namespace Palasthotel\WordPress\Headless\Components;

/**
 * Base class for all plugin components.
 *
 * Receives the Plugin instance via constructor injection and calls onCreate()
 * to allow subclasses to register hooks and initialize state.
 */
abstract class Component {

	public function __construct(
		public \Palasthotel\WordPress\Headless\Plugin $plugin
	) {
		$this->onCreate();
	}

	/**
	 * Called after construction. Override in subclasses to register hooks and initialize state.
	 *
	 * @return void
	 */
	public function onCreate(): void {
		// init your hooks and stuff
	}
}
