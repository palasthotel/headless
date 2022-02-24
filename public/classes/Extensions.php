<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;
use Palasthotel\WordPress\Headless\Extensions\FeaturedMediaUrl;

/**
 * @property FeaturedMediaUrl $featuredMediaUrl
 */
class Extensions extends Component {

	public function onCreate() {
		parent::onCreate();
		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}

	public function rest_api_init() {

		$this->featuredMediaUrl = new FeaturedMediaUrl($this->plugin);
		$this->featuredMediaUrl->init();

		// TODO: gutenberg blocks

		// TODO: single post full taxonomy term objects
	}




}