<?php

namespace Palasthotel\WordPress\Headless\Routes;

use Palasthotel\WordPress\Headless\Components\Component;
use Palasthotel\WordPress\Headless\Plugin;

/**
 * Registers REST API endpoints for retrieving WordPress navigation menus.
 *
 * Exposes GET /headless/v1/menus for all menus and
 * GET /headless/v1/menus/{slug} for a specific menu.
 * Requires both a headless request and valid API key access.
 */
class Menus extends Component {

	/**
	 * @var array Cached menu items for the current request.
	 */
	private array $menu;

	/**
	 * Registers the menus REST routes.
	 *
	 * @return void
	 */
	public function init(){
		register_rest_route( Plugin::REST_NAMESPACE, '/menus', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_all_menus' ],
			'permission_callback' => function(){
				return $this->plugin->security->isHeadlessRequest() &&
				       $this->plugin->security->hasApiKeyAccess();
			},
		) );
		register_rest_route( Plugin::REST_NAMESPACE, '/menus/(?P<menu>[\S]+)', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_menu' ],
			'permission_callback' => function(){
				return $this->plugin->security->isHeadlessRequest() &&
				       $this->plugin->security->hasApiKeyAccess();
			},
		) );
	}

	/**
	 * Returns all registered navigation menus keyed by slug.
	 *
	 * @return array<string, array> Map of menu slug to menu items array.
	 */
	public function get_all_menus() {
		$menus = wp_get_nav_menus();
		$menusResponse = [];
		foreach ($menus as $menu){
			$menusResponse[$menu->slug] = $this->getMenuResponse($menu);
		}
		return $menusResponse;
	}

	/**
	 * Returns the cached menu items for the current menu request.
	 *
	 * @return array The menu items array.
	 */
	public function get_menu() {
		return $this->menu;
	}

	/**
	 * Retrieves and prepares menu items for a given menu, decoding HTML entities in titles.
	 *
	 * @param \WP_Term|object $menu The menu object or term to fetch items for.
	 * @return array The array of prepared menu item objects, or empty array if not found.
	 */
	private function getMenuResponse($menu) {
		$menu  = wp_get_nav_menu_items( $menu );
		if ( ! $menu ) {
			return [];
		}

		return array_map( function ( $menuItem ) {
			if ( isset( $menuItem->title ) ) {
				$menuItem->title = html_entity_decode( $menuItem->title );
			}
			if ( isset( $menuItem->attr_title ) ) {
				$menuItem->attr_title = html_entity_decode( $menuItem->attr_title );
			}

			return $menuItem;
		}, $menu );
	}
}
