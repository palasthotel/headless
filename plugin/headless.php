<?php

/**
 * Plugin Name: Headless
 * Plugin URI: https://github.com/palasthotel/headless
 * Description: Adds features to use WordPress as headless CMS
 * Version: 1.7.1
 * Author: Palasthotel (Edward Bock) <edward.bock@palasthotel.de>
 * Author URI: http://www.palasthotel.de
 * Requires at least: 5.0
 * Tested up to: 6.1.1
 * Requires PHP: 7.4
 * Text Domain: headless
 * License: http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @copyright Copyright (c) 2022, Palasthotel
 * @package Palasthotel\WordPress\Headless
 *
 */

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Store\RevalidationDatabase;

if ( ! defined( 'HEADLESS_HEAD_BASE_URL' ) ) {
	define( 'HEADLESS_HEAD_BASE_URL', '' );
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

if ( ! defined( 'HEADLESS_API_KEY_HEADER_KEY' ) ) {
	define( 'HEADLESS_API_KEY_HEADER_KEY', "" );
}
if ( ! defined( 'HEADLESS_API_KEY_HEADER_VALUE' ) ) {
	define( 'HEADLESS_API_KEY_HEADER_VALUE', "" );
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
 * @property RevalidationDatabase $dbRevalidation
 * @property Schedule $schedule
 * @property Gutenberg $gutenberg
 * @property Headers $headers
 * @property Post $post
 */
class Plugin extends Components\Plugin {

	const REST_NAMESPACE = "headless/v1";

	const FILTER_PREVIEW_REDIRECT_URL = "headless_preview_redirect_url";
	const FILTER_PREVIEW_URL = "headless_preview_url";

	const ACTION_REGISTER_BLOCK_PREPARATION_EXTENSIONS = "headless_register_block_preparation_extensions";
	const ACTION_REGISTER_POST_ROUTE_EXTENSIONS = "headless_register_post_route_extensions";
	const ACTION_REGISTER_COMMENT_ROUTE_EXTENSIONS = "headless_register_comment_route_extensions";
	const ACTION_REGISTER_USER_ROUTE_EXTENSIONS = "headless_register_user_route_extensions";
	const ACTION_REGISTER_TERM_ROUTE_EXTENSIONS = "headless_register_term_route_extensions";

	const FILTER_BLOCKS_PREPARE_FILTER = "headless_rest_api_prepare_filter";
	const FILTER_BLOCKS_PREPARE_BLOCK = "headless_rest_api_prepare_block";
	const FILTER_PREPARE_POST = "headless_rest_api_prepare_post";

	const FILTER_REST_RESPONSE_HEADERS = "headless_rest_response_headers";

	const FILTER_REVALIDATE_URLS = "headless_revalidate_urls";
	const OPTION_LAST_REVALIDATION_RUN = "headless_last_revalidation_run";
	const SCHEDULE_REVALIDATE = "headless_schedule_revalidate";

	const OPTION_SCHEMA_VERSION = "headless_schema_version";


	function onCreate() {

		$this->dbRevalidation = new RevalidationDatabase();

		$this->security   = new Security( $this );
		$this->headers    = new Headers( $this );
		$this->routes     = new Routes( $this );
		$this->extensions = new Extensions( $this );
		$this->query      = new Query( $this );
		$this->preview    = new Preview( $this );
		$this->revalidate = new Revalidate( $this );
		$this->gutenberg  = new Gutenberg( $this );
		$this->post       = new Post( $this );

		$this->schedule = new Schedule( $this );

		new Migration( $this );

	}

	public function onSiteActivation() {
		parent::onSiteActivation();
		$this->dbRevalidation->createTables();

	}
}

Plugin::instance();
