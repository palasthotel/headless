<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

class Security extends Component {

	public function onCreate() {
		parent::onCreate();
		add_filter('wp_is_application_passwords_available', '__return_true');
	}

	public function isHeadlessRequest(): bool {
		return isset( $_GET[ HEADLESS_REST_PARAM ] ) && HEADLESS_REST_VALUE == $_GET[ HEADLESS_REST_PARAM ];
	}

	public function hasApiKeyAccess(): bool {

		if ( ! defined( 'HEADLESS_API_KEY_HEADER_KEY' ) ) {
			// if no header config is present grant access
			return true;
		}
		if ( ! defined( 'HEADLESS_API_KEY_HEADER_VALUE' ) ) {
			// if no header value config is present deny access
			return false;
		}
		$header =  "HTTP_" . strtoupper( str_replace("-", "_",HEADLESS_API_KEY_HEADER_KEY ));
		if ( ! isset( $_SERVER[ $header ] ) ) {
			// on missing header in request deny access
			return false;
		}
		return $_SERVER[$header] === HEADLESS_API_KEY_HEADER_VALUE;
	}

}