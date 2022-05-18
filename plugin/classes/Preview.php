<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

class Preview extends Component {
	public function onCreate() {
		parent::onCreate();

		// TODO: find a solid preview in headless way
		// add_action('init', [$this, 'init']);
	}

	public function init(){
		$isPreview = is_admin() || !isset( $_GET['preview'] ) || true != $_GET['preview'];
		$previewId = isset($_GET["preview_id"]) ? intval($_GET["preview_id"]) : false;


		$doRedirect = apply_filters(Plugin::FILTER_PREVIEW_DO_REDIRECT, $isPreview);
		if (!$doRedirect) return;


	}
}