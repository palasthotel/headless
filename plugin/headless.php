<?php

/**
 * Plugin Name: Headless
 * Plugin URI: https://github.com/palasthotel/headless
 * Description: Adds features to use WordPress as headless CMS
 * Version: 1.4.2
 * Author: Palasthotel (Edward Bock) <edward.bock@palasthotel.de>
 * Author URI: http://www.palasthotel.de
 * Requires at least: 5.0
 * Tested up to: 6.0.0
 * Requires PHP: 8.0
 * Text Domain: headless
 * License: http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @copyright Copyright (c) 2022, Palasthotel
 * @package Palasthotel\WordPress\Headless
 *
 */

namespace Palasthotel\WordPress\Headless;

if( !defined('HEADLESS_HEAD_BASE_URL')) {
	define('HEADLESS_HEAD_BASE_URL', '');
}

if ( ! defined( 'HEADLESS_SECRET_TOKEN' ) ) {
	define( 'HEADLESS_SECRET_TOKEN', "" );
}

if ( ! defined( 'HEADLESS_REST_PARAM' ) ) {
	define( 'HEADLESS_REST_PARAM', "headless" );
}
if ( ! defined( 'HEADLESS_REST_VALUE' ) ) {
	define( 'HEADLESS_REST_VALUE', 'true' );
}

require_once __DIR__ . "/vendor/autoload.php";

/**
 * @property Routes $routes
 * @property Extensions $extensions
 * @property Security $security
 * @property Links $links
 * @property Preview $preview
 * @property Query $query
 * @property Revalidate $revalidate
 */
class Plugin extends Components\Plugin {

	const REST_NAMESPACE = "headless/v1";

	const FILTER_PREVIEW_URL = "headless_preview_url";
	const FILTER_POST_LINK = "headless_post_link";
	const FILTER_PREVIEW_DO_REDIRECT = "headless_preview_redirect";

	const ACTION_REGISTER_BLOCK_PREPARATION_EXTENSIONS = "headless_register_block_preparation_extensions";
	const ACTION_REGISTER_POST_ROUTE_EXTENSIONS = "headless_register_post_route_extensions";

	const FILTER_BLOCKS_PREPARE_FILTER = "headless_rest_api_prepare_filter";
	const FILTER_BLOCKS_PREPARE_BLOCK = "headless_rest_api_prepare_block";


	function onCreate() {

		$this->security   = new Security( $this );
		$this->routes     = new Routes( $this );
		$this->extensions = new Extensions( $this );
		$this->query      = new Query( $this );
		$this->links      = new Links( $this );
		$this->preview    = new Preview( $this );
		$this->revalidate = new Revalidate($this);

	}
}

Plugin::instance();