<?php

/**
 * Plugin Name: Headless
 * Plugin URI: https://github.com/palasthotel/headless
 * Description: Adds features to use WordPress as headless CMS
 * Version: 0.0.1
 * Author: Palasthotel (Edward Bock) <edward.bock@palasthotel.de>
 * Author URI: http://www.palasthotel.de
 * Requires at least: 5.0
 * Tested up to: 5.9.1
 * Requires PHP: 8.0
 * Text Domain: headless
 * License: http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @copyright Copyright (c) 2022, Palasthotel
 * @package Palasthotel\WordPress\Headless
 *
 */

namespace Palasthotel\WordPress\Headless;

require_once __DIR__."/vendor/autoload.php";

/**
 * @property Routes $routes
 * @property Extensions $extensions
 * @property ApiKey $apiKey
 */
class Plugin extends Components\Plugin {

	const REST_NAMESPACE = "headless/v1";


	function onCreate() {

		// TODO: add security to rest api (api key)

		$this->apiKey = new ApiKey($this);
		$this->routes = new Routes($this);
		$this->extensions = new Extensions($this);

	}
}

Plugin::instance();