<?php

namespace Palasthotel\WordPress\Headless\Routes;

use Palasthotel\WordPress\Headless\Components\Component;
use Palasthotel\WordPress\Headless\Plugin;

/**
 * Registers the REST API endpoint for retrieving site settings.
 *
 * Exposes GET /headless/v1/settings with front page configuration and home URL.
 * Requires both a headless request and valid API key access.
 */
class Settings extends Component {

	/**
	 * Registers the settings REST route.
	 *
	 * @return void
	 */
	public function init(): void {
		register_rest_route( Plugin::REST_NAMESPACE, '/settings', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => [ $this, 'get_settings' ],
			'permission_callback' => function(){
				return $this->plugin->security->isHeadlessRequest() &&
				       $this->plugin->security->hasApiKeyAccess();
			},
		) );
	}

	/**
	 * Returns key site settings for the headless frontend.
	 *
	 * @return array{front_page: string, page_on_front: int, home_url: string}
	 */
	public function get_settings(): array {
		return [
			"front_page" => get_option( 'show_on_front' ),
			"page_on_front" => intval(get_option( 'page_on_front' )),
			"home_url" => home_url(),
		];
	}
}
