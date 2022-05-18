<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

class Security extends Component {

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