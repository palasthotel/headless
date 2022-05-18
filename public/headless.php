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

if(!defined('HEADLESS_DOMAIN')){
	// TODO: add admin notice that there is configuration missing
	define('HEADLESS_DOMAIN', 'http://localhost:3000');
}

if(!defined('HEADLESS_PREVIEW_TOKEN')){
	// TODO: add admin notice that there is configuration missing
	define('HEADLESS_PREVIEW_TOKEN', "");
}

require_once __DIR__."/vendor/autoload.php";

/**
 * @property Routes $routes
 * @property Extensions $extensions
 * @property Security $security
 * @property Links $links
 * @property Preview $preview
 */
class Plugin extends Components\Plugin {

	const REST_NAMESPACE = "headless/v1";

	const FILTER_PREVIEW_URL = "headless_preview_url";
	const FILTER_POST_LINK = "headless_post_link";
	const FILTER_PREVIEW_DO_REDIRECT = "headless_preview_redirect";

	const ACTION_REGISTER_BLOCK_PREPARATION_EXTENSIONS = "headless_register_block_preparation_extensions";
	const ACTION_REGISTER_REST_ROUTE_EXTENSIONS = "headless_register_rest_route_extensions";

	const FILTER_BLOCKS_PREPARE_FILTER = "headless_rest_api_prepare_filter";
	const FILTER_BLOCKS_PREPARE_BLOCK = "headless_rest_api_prepare_block";


	function onCreate() {

		$this->security = new Security($this);
		$this->routes   = new Routes($this);
		$this->extensions = new Extensions($this);
		$this->links = new Links($this);
		$this->preview = new Preview($this);


	}
}

Plugin::instance();