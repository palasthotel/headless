<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Assets;
use Palasthotel\WordPress\Headless\Components\Component;

/**
 * Registers and enqueues plugin scripts and styles for the admin and block editor.
 *
 * Handles the Gutenberg editor script/style and the general admin script,
 * including localized data for frontend URLs, AJAX actions, and preview settings.
 */
class PluginAssets extends Component {

    const HANDLE_ADMIN_SCRIPT = "headless_admin_script";
	const HANDLE_GUTENBERG_SCRIPT = "headless_gutenberg_script";
	const HANDLE_GUTENBERG_STYLE = "headless_gutenberg_styles";

	/**
	 * @var Assets Asset registration helper.
	 */
	public Assets $assets;

	public function onCreate(): void {
		parent::onCreate();

		$this->assets = new Assets( $this->plugin );

		add_action( 'admin_init', [ $this, 'admin_init' ], 0 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue' ] );
	}

	/**
	 * Registers all plugin scripts and styles and enqueues the admin script for editors.
	 *
	 * @return void
	 */
	public function admin_init() {
		$this->assets->registerScript(
			self::HANDLE_GUTENBERG_SCRIPT,
			"dist/gutenberg.js"
		);
		wp_localize_script(
			self::HANDLE_GUTENBERG_SCRIPT,
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
			self::HANDLE_GUTENBERG_STYLE,
			"dist/gutenberg.css",
		);
        $this->assets->registerScript(
            self::HANDLE_ADMIN_SCRIPT,
            "dist/admin.js",
        );
        if(current_user_can("edit_posts")){
            wp_localize_script(
                self::HANDLE_ADMIN_SCRIPT,
                "HeadlessAdmin",
                [
					"revalidate_is_active" => $this->plugin->revalidate->isRevalidationActive(),
					"preview_is_active" => $this->plugin->preview->isPreviewActive(),
                    "preview_path" => $this->plugin->preview->getHeadlessPreviewPath(),
                    "frontends" => array_map(function($frontend){
                        return $frontend->getBaseUrl();
                    }, $this->plugin->headquarter->getFrontends()),
                ]
            );
            wp_enqueue_script(self::HANDLE_ADMIN_SCRIPT);
        }
	}

	/**
	 * Enqueues the Gutenberg script and style for headless post types in the block editor.
	 *
	 * @return void
	 */
	public function enqueue() {
		if ( ! $this->plugin->post->isHeadlessPostType( get_post_type() ) ) {
			return;
		}
		wp_enqueue_script( self::HANDLE_GUTENBERG_SCRIPT );
		wp_enqueue_style( self::HANDLE_GUTENBERG_STYLE );
	}
}
