<?php

namespace Palasthotel\WordPress\Headless\Routes;

use Palasthotel\WordPress\Headless\Components\Component;
use Palasthotel\WordPress\Headless\Plugin;
use WP_Term;

class Menus extends Component {

	private $menu;

	public function init(){
		register_rest_route( Plugin::REST_NAMESPACE, '/menus', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_all_menus' ],
			'permission_callback' => '__return_true', // TODO: check if api key is set
		) );
		register_rest_route( Plugin::REST_NAMESPACE, '/menus/(?P<menu>[\S]+)', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_menu' ],
			'permission_callback' => '__return_true', // TODO: check if api key is set
			'args'                => [
				'menu' => [
					'validate_callback' => function ( $value, $request, $param ) {
						$value = sanitize_text_field( $value );
						$menu  = wp_get_nav_menu_items( $value );
						if ( ! $menu ) {

							return false;
						}
						$this->menu = $menu;

						return true;

					},
				],
			]
		) );
	}

	public function get_all_menus() {
		return array_map( function ( WP_Term $term ) {
			return $term->slug;
		}, wp_get_nav_menus() );
	}

	public function get_menu() {
		return array_map( function ( $menuItem ) {
			if ( isset( $menuItem->title ) ) {
				$menuItem->title = html_entity_decode( $menuItem->title );
			}
			if ( isset( $menuItem->attr_title ) ) {
				$menuItem->attr_title = html_entity_decode( $menuItem->attr_title );
			}

			return $menuItem;
		}, $this->menu );
	}
}