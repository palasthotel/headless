<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\BlockPreparations\ImageBlockPreparation;
use Palasthotel\WordPress\Headless\BlockPreparations\ParagraphBlockPreparation;
use Palasthotel\WordPress\Headless\Components\Component;
use Palasthotel\WordPress\Headless\Extensions\Blocks;
use Palasthotel\WordPress\Headless\Extensions\FeaturedMediaUrl;
use Palasthotel\WordPress\Headless\Extensions\Title;
use Palasthotel\WordPress\Headless\Interfaces\IBlockPreparationExtension;
use Palasthotel\WordPress\Headless\Interfaces\IPostRouteExtension;

/**
 * @property FeaturedMediaUrl $featuredMediaUrl
 */
class Extensions extends Component {

	/**
	 * @var IPostRouteExtension[] $postRouteExtensions
	 */
	private array $postRouteExtensions = [];

	/**
	 * @var IBlockPreparationExtension[]
	 */
	private array $blockPreparationExtensions = [];

	public function onCreate() {
		parent::onCreate();
		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}

	public function rest_api_init() {

		if(!$this->plugin->security->hasApiKeyAccess()) return;

		add_action(Plugin::ACTION_REGISTER_BLOCK_PREPARATION_EXTENSIONS, $this);
		$this->addBlockPreparation(new ParagraphBlockPreparation());
		$this->addBlockPreparation(new ImageBlockPreparation());

		/**
		 * @var IPostRouteExtension[]
		 */
		$this->addPostExtension(new Title());
		$this->addPostExtension(new FeaturedMediaUrl());
		$this->addPostExtension(new Blocks($this->blockPreparationExtensions));

		add_action(Plugin::ACTION_REGISTER_REST_ROUTE_EXTENSIONS, $this);

		$post_types = get_post_types( [ "public" => true ] );
		foreach ($this->postRouteExtensions as $extension) {
			foreach ( $post_types as $type ) {
				add_filter( 'rest_prepare_' . $type, [ $extension, 'response' ], 99, 3 );
			}
		}

	}

	public function addPostExtension(IPostRouteExtension $extension){
		$this->postRouteExtensions[] = $extension;
	}

	public function addBlockPreparation(IBlockPreparationExtension $extension){
		$this->blockPreparationExtensions[] = $extension;
	}




}