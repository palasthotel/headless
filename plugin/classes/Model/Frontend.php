<?php

namespace Palasthotel\WordPress\Headless\Model;

/**
 * Represents a headless frontend instance identified by its base URL.
 */
class Frontend {

	/**
	 * @var string The base URL of the headless frontend, with trailing slash.
	 */
	private string $baseUrl;

	/**
	 * @param string $baseUrl The base URL of the frontend.
	 */
	public function __construct( string $baseUrl ) {
		$this->baseUrl = $baseUrl;
	}

	/**
	 * Returns the base URL of the frontend.
	 *
	 * @return string The base URL.
	 */
	public function getBaseUrl(): string {
		return $this->baseUrl;
	}
}
