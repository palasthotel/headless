<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

class Preview extends Component {
	public function onCreate() {
		parent::onCreate();

		// add_action( 'init', [ $this, 'init' ] );
	}

	public function init() {
		$isPreview = ! ( is_admin() || ! isset( $_GET['preview'] ) || true != $_GET['preview'] );
		$previewId = intval( $_GET["preview_id"] );


		$doRedirect = apply_filters( Plugin::FILTER_PREVIEW_DO_REDIRECT, $isPreview );
		if ( ! $doRedirect ) {
			return;
		}


	}
}