<?php

namespace Palasthotel\WordPress\Headless;


use Palasthotel\WordPress\Headless\Routes\Menus;
use Palasthotel\WordPress\Headless\Routes\Settings;

class Routes extends Components\Component {

	public Menus $menus;
	public Settings $settings;

	public function onCreate(): void {
		parent::onCreate();
		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}

	public function rest_api_init() {

		$this->settings = new Settings($this->plugin);
		$this->settings->init();

		$this->menus = new Menus($this->plugin);
		$this->menus->init();

	}



}
