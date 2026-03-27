<?php

namespace Palasthotel\WordPress\Headless;


use Palasthotel\WordPress\Headless\Routes\Menus;
use Palasthotel\WordPress\Headless\Routes\Settings;

/**
 * Initializes and exposes the plugin's custom REST API routes.
 *
 * Registers the Menus and Settings REST route handlers on rest_api_init.
 */
class Routes extends Components\Component {

	/**
	 * @var Menus REST route handler for navigation menus.
	 */
	public Menus $menus;

	/**
	 * @var Settings REST route handler for site settings.
	 */
	public Settings $settings;

	public function onCreate(): void {
		parent::onCreate();
		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}

	/**
	 * Instantiates and initializes the Menus and Settings route handlers.
	 *
	 * @return void
	 */
	public function rest_api_init() {

		$this->settings = new Settings($this->plugin);
		$this->settings->init();

		$this->menus = new Menus($this->plugin);
		$this->menus->init();

	}



}
