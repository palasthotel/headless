<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Assets;
use Palasthotel\WordPress\Headless\Components\Component;

/**
 * @property Assets $assets
 */
class Gutenberg extends Component {

	const HANDLE_SCRIPT = "headless_gutenberg_script";
	const HANDLE_STYLE = "headless_gutenberg_styles";

	public function onCreate() {
		parent::onCreate();

		$this->assets = new Assets( $this->plugin );

		add_action( 'init', [ $this, 'init' ], 0 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue' ] );
	}

	public function init() {
		$this->assets->registerScript(
			self::HANDLE_SCRIPT,
			"dist/gutenberg.ts.js"
		);
		wp_localize_script(
			self::HANDLE_SCRIPT,
			"Headless",
			[
				"ajax"                => admin_url( 'admin-ajax.php' ),
				"frontends"           => array_map(
					function ( $frontend ) {
						return $frontend->getBaseUrl();
					},
					$this->plugin->headquarter->getFrontends()
				),
				"actions"             => [
					"revalidate" => Ajax::GET_ACTION,
				],
				"post_id_placeholder" => Preview::POST_ID_PLACEHOLDER,
				"preview_url"         => $this->plugin->preview->getRedirectLink( null ),
			]
		);
		$this->assets->registerStyle(
			self::HANDLE_STYLE,
			"dist/gutenberg.ts.css",
		);
	}

	public function enqueue() {
		if ( ! $this->plugin->post->isHeadlessPostType( get_post_type() ) ) {
			return;
		}
		wp_enqueue_script( self::HANDLE_SCRIPT );
		wp_enqueue_style( self::HANDLE_STYLE );
	}
}
