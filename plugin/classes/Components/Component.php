<?php


namespace Palasthotel\WordPress\Headless\Components;

abstract class Component {

	public function __construct(
		public \Palasthotel\WordPress\Headless\Plugin $plugin
	) {
		$this->onCreate();
	}

	/**
	 * overwrite this method in component implementations
	 */
	public function onCreate(): void {
		// init your hooks and stuff
	}
}
