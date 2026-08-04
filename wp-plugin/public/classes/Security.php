<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

/**
 * Handles security checks for headless REST API access.
 *
 * Detects headless requests via a dedicated query parameter, validates
 * request variants, and enforces optional API key header authentication.
 * Also enables WordPress application passwords globally.
 */
class Security extends Component {

	public function onCreate(): void {
		parent::onCreate();
		add_filter('wp_is_application_passwords_available', [$this, 'applicationPasswordsAvailable']);
	}

	/**
	 * Keeps application passwords available for headless authentication.
	 *
	 * Core allows them on SSL sites and in local environments. This used to force
	 * them on unconditionally, which meant a plain-HTTP site could hand out
	 * credentials that then travel in an Authorization header on every request.
	 * Sites that accept that risk can opt back in via the
	 * Plugin::FILTER_APPLICATION_PASSWORDS_AVAILABLE filter.
	 *
	 * @param bool $available Whether core considers application passwords available.
	 * @return bool True if application passwords may be used.
	 */
	public function applicationPasswordsAvailable( $available ): bool {
		return (bool) apply_filters( Plugin::FILTER_APPLICATION_PASSWORDS_AVAILABLE, $available );
	}

	/**
	 * Checks whether the current request is a headless REST request.
	 *
	 * @return bool True if the HEADLESS_REST_PARAM query parameter matches HEADLESS_REST_VALUE.
	 */
	public function isHeadlessRequest(): bool {
		return isset( $_GET[ HEADLESS_REST_PARAM ] ) && HEADLESS_REST_VALUE == $_GET[ HEADLESS_REST_PARAM ];
	}

	/**
	 * Checks whether the current headless request matches a specific variant.
	 *
	 * @param string $variant The variant value to check against HEADLESS_REST_VARIANT_PARAM.
	 * @return bool True if the variant parameter matches the given value.
	 */
	public function isHeadlessRequestVariant(string $variant): bool {
		return isset( $_GET[ HEADLESS_REST_VARIANT_PARAM ] ) && $variant == $_GET[ HEADLESS_REST_VARIANT_PARAM ];
	}

	/**
	 * Checks whether the current request has valid API key access.
	 *
	 * If no API key constants are configured, all requests are granted access.
	 * Otherwise, the request must include the correct API key header value.
	 *
	 * @return bool True if access is granted.
	 */
	public function hasApiKeyAccess(): bool {

		if ( empty(HEADLESS_API_KEY_HEADER_KEY) || empty(HEADLESS_API_KEY_HEADER_VALUE)) {
			// if api key values are empty there is no api key restriction
			return true;
		}

		$header =  "HTTP_" . strtoupper( str_replace("-", "_",HEADLESS_API_KEY_HEADER_KEY ));
		if ( ! isset( $_SERVER[ $header ] ) ) {
			// on missing header in request deny access
			return false;
		}
		// hash_equals compares in constant time, so the number of matching leading
		// characters cannot be read off the response time.
		return hash_equals( (string) HEADLESS_API_KEY_HEADER_VALUE, (string) $_SERVER[ $header ] );
	}

}
