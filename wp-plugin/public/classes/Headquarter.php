<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;
use Palasthotel\WordPress\Headless\Model\Frontend;

/**
 * Provides the list of configured headless frontend instances.
 *
 * Returns Frontend objects based on the HEADLESS_HEAD_BASE_URL constant,
 * with support for filtering via the Plugin::FILTER_FRONTENDS hook.
 */
class Headquarter extends Component {

	/**
	 * @return Frontend[]
	 */
	public function getFrontends(): array {
		$baseUrl = trailingslashit(
			empty( HEADLESS_HEAD_BASE_URL ) ? home_url() : HEADLESS_HEAD_BASE_URL
		);

		return apply_filters( Plugin::FILTER_FRONTENDS, [
			new Frontend( $baseUrl )
		], $baseUrl );
	}


}
