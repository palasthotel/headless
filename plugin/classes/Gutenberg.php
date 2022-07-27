<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Assets;
use Palasthotel\WordPress\Headless\Components\Component;

/**
 * @property Assets $assets
 */
class Gutenberg extends Component {

	const HANDLE_SCRIPT = "headless_gutenberg_script";

	public function onCreate() {
		parent::onCreate();

		$this->assets = new Assets( $this->plugin );

		add_action( 'init', [ $this, 'init' ], 0 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue' ] );
		add_action( 'wp_ajax_headless_reload', [ $this, 'reload' ] );
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
				"ajax" => admin_url('admin-ajax.php'),
				"actions" => [
					"reload" => "headless_reload",
				]
			]
		);
	}

	public function enqueue() {
		wp_enqueue_script( self::HANDLE_SCRIPT );
	}

	public function reload(){
		$postId = intval($_GET["post"]);
		$result = $this->plugin->revalidate->revalidatePost($postId);
		if($result instanceof \WP_Error){
			wp_send_json_error($result);
		} else {
			wp_send_json_success($result);
		}
		exit;
	}
}