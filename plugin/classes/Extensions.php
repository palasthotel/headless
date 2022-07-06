<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\BlockPreparations\FreeFormBlockPreparation;
use Palasthotel\WordPress\Headless\BlockPreparations\GalleryBlockPreparation;
use Palasthotel\WordPress\Headless\BlockPreparations\ImageBlockPreparation;
use Palasthotel\WordPress\Headless\BlockPreparations\MoreBlockPreparation;
use Palasthotel\WordPress\Headless\BlockPreparations\ParagraphBlockPreparation;
use Palasthotel\WordPress\Headless\BlockPreparations\ReferenceBlockPreparation;
use Palasthotel\WordPress\Headless\Components\Component;
use Palasthotel\WordPress\Headless\Extensions\ContentAttachments;
use Palasthotel\WordPress\Headless\Extensions\ContentBlocks;
use Palasthotel\WordPress\Headless\Extensions\FeaturedMedia;
use Palasthotel\WordPress\Headless\Extensions\Taxonomies;
use Palasthotel\WordPress\Headless\Extensions\Title;
use Palasthotel\WordPress\Headless\Model\BlockPreparations;
use Palasthotel\WordPress\Headless\Model\PostRouteExtensions;

/**
 */
class Extensions extends Component {

	private BlockPreparations $blockPreparations;
	private PostRouteExtensions $postRouteExtensions;

	public function onCreate() {
		parent::onCreate();

		if ( ! $this->plugin->security->isHeadlessRequest() ) {
			return;
		}

		$this->postRouteExtensions = new PostRouteExtensions();
		$this->blockPreparations   = new BlockPreparations();

		add_action( Plugin::ACTION_REGISTER_BLOCK_PREPARATION_EXTENSIONS, [ $this, 'block_preparation_extensions_with_prio' ], 2 );
		add_action( Plugin::ACTION_REGISTER_BLOCK_PREPARATION_EXTENSIONS, [ $this, 'block_preparation_extensions' ] );
		add_action( Plugin::ACTION_REGISTER_POST_ROUTE_EXTENSIONS, [ $this, 'post_route_extensions' ] );

		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}

	public function block_preparation_extensions_with_prio(BlockPreparations $extensions){
		// needs to be the very first preparation step so others can apply to the result
		$extensions->add(new ReferenceBlockPreparation());
	}

	public function block_preparation_extensions( BlockPreparations $extensions ) {
		$extensions->add( new ParagraphBlockPreparation() );
		$extensions->add( new MoreBlockPreparation() );
		$extensions->add( new ImageBlockPreparation() );
		$extensions->add( new GalleryBlockPreparation() );
		$extensions->add( new FreeFormBlockPreparation() );
	}

	public function post_route_extensions( PostRouteExtensions $extensions ) {
		$extensions->add( new Title() );
		$extensions->add( new FeaturedMedia() );
		$extensions->add( new ContentBlocks( $this->blockPreparations ) );
		$extensions->add( new ContentAttachments() );
		$extensions->add( new Taxonomies() );
	}

	public function rest_api_init() {

		if ( ! $this->plugin->security->hasApiKeyAccess() ) {
			return;
		}

		do_action( Plugin::ACTION_REGISTER_BLOCK_PREPARATION_EXTENSIONS, $this->blockPreparations );
		do_action( Plugin::ACTION_REGISTER_POST_ROUTE_EXTENSIONS, $this->postRouteExtensions );

		$post_types = get_post_types( [ "public" => true, 'show_in_rest' => true ] );
		foreach ( $this->postRouteExtensions->get() as $extension ) {
			foreach ( $post_types as $type ) {
				add_filter( 'rest_prepare_' . $type, [ $extension, 'response' ], 99, 3 );
			}
			add_filter( 'rest_prepare_revision', [ $extension, 'response' ], 99, 3 );
		}
	}

}