<?php

namespace Palasthotel\WordPress\Headless;


use Palasthotel\WordPress\Headless\Routes\Menus;

/**
 * @property Menus $menus
 */
class Routes extends Components\Component {

	public function onCreate() {
		parent::onCreate();
		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}

	public function rest_api_init() {

		$this->menus = new Menus($this->plugin);
		$this->menus->init();


	}


}