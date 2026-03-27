<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\BlockPreparations\CoreEmbedBlockPreparation;
use Palasthotel\WordPress\Headless\BlockPreparations\EmbedBlockPreparation;
use Palasthotel\WordPress\Headless\BlockPreparations\FreeFormBlockPreparation;
use Palasthotel\WordPress\Headless\BlockPreparations\GalleryBlockPreparation;
use Palasthotel\WordPress\Headless\BlockPreparations\ImageBlockPreparation;
use Palasthotel\WordPress\Headless\BlockPreparations\MoreBlockPreparation;
use Palasthotel\WordPress\Headless\BlockPreparations\ParagraphBlockPreparation;
use Palasthotel\WordPress\Headless\BlockPreparations\ReferenceBlockPreparation;
use Palasthotel\WordPress\Headless\BlockPreparations\TagCloudPreparation;
use Palasthotel\WordPress\Headless\Components\Component;
use Palasthotel\WordPress\Headless\Extensions\CommentAuthorUser;
use Palasthotel\WordPress\Headless\Extensions\ContentAttachments;
use Palasthotel\WordPress\Headless\Extensions\ContentBlocks;
use Palasthotel\WordPress\Headless\Extensions\FeaturedMedia;
use Palasthotel\WordPress\Headless\Extensions\Taxonomies;
use Palasthotel\WordPress\Headless\Extensions\Title;
use Palasthotel\WordPress\Headless\Model\BlockPreparations;
use Palasthotel\WordPress\Headless\Model\CommentRouteExtensions;
use Palasthotel\WordPress\Headless\Model\PostRouteExtensions;
use Palasthotel\WordPress\Headless\Model\TermRouteExtensions;
use Palasthotel\WordPress\Headless\Model\UserRouteExtensions;

/**
 * Registers all built-in block preparations and REST API route extensions.
 *
 * Hooks into the plugin's registration actions during REST API init to add
 * default extensions for posts, comments, blocks, users, and terms.
 * Only active on headless requests with valid API key access.
 */
class Extensions extends Component {

	/**
	 * @var BlockPreparations
	 */
	private BlockPreparations $blockPreparations;

	/**
	 * @var PostRouteExtensions
	 */
	private PostRouteExtensions $postRouteExtensions;

	/**
	 * @var CommentRouteExtensions
	 */
	private CommentRouteExtensions $commentRouteExtensions;

	/**
	 * @var UserRouteExtensions
	 */
	private UserRouteExtensions $userRouteExtensions;

	/**
	 * @var TermRouteExtensions
	 */
	private TermRouteExtensions $termRouteExtensions;

	public function onCreate(): void {
		parent::onCreate();

		if ( ! $this->plugin->security->isHeadlessRequest() ) {
			return;
		}

		$this->postRouteExtensions = new PostRouteExtensions();
		$this->blockPreparations = new BlockPreparations();
		$this->commentRouteExtensions = new CommentRouteExtensions();
		$this->userRouteExtensions = new UserRouteExtensions();
		$this->termRouteExtensions = new TermRouteExtensions();

		add_action( Plugin::ACTION_REGISTER_BLOCK_PREPARATION_EXTENSIONS, [
			$this,
			'block_preparation_extensions_with_prio'
		], 2 );
		add_action( Plugin::ACTION_REGISTER_BLOCK_PREPARATION_EXTENSIONS, [ $this, 'block_preparation_extensions' ] );
		add_action( Plugin::ACTION_REGISTER_POST_ROUTE_EXTENSIONS, [ $this, 'post_route_extensions' ] );
		add_action( Plugin::ACTION_REGISTER_COMMENT_ROUTE_EXTENSIONS, [ $this, 'comment_route_extensions' ] );
		//add_action( Plugin::ACTION_REGISTER_USER_ROUTE_EXTENSIONS, [ $this, 'user_route_extensions' ] );
		//add_action( Plugin::ACTION_REGISTER_TERM_ROUTE_EXTENSIONS, [ $this, 'term_route_extensions' ] );

		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}

	/**
	 * Registers block preparations that must run before all others.
	 *
	 * @param BlockPreparations $extensions The block preparations collection.
	 * @return void
	 */
	public function block_preparation_extensions_with_prio( BlockPreparations $extensions ) {
		// needs to be the very first preparation step so others can apply to the result
		$extensions->add( new ReferenceBlockPreparation() );
	}

	/**
	 * Registers the default set of block preparations.
	 *
	 * @param BlockPreparations $extensions The block preparations collection.
	 * @return void
	 */
	public function block_preparation_extensions( BlockPreparations $extensions ) {
		$extensions->add( new ParagraphBlockPreparation() );
		$extensions->add( new MoreBlockPreparation() );
		$extensions->add( new ImageBlockPreparation() );
		$extensions->add( new GalleryBlockPreparation() );
		$extensions->add( new FreeFormBlockPreparation() );
		$extensions->add( new TagCloudPreparation() );
		// order is important --->
		$extensions->add( new CoreEmbedBlockPreparation() );
		$extensions->add( new EmbedBlockPreparation() );
		// <--- order is important
	}

	/**
	 * Registers the default post route extensions.
	 *
	 * @param PostRouteExtensions $extensions The post route extensions collection.
	 * @return void
	 */
	public function post_route_extensions( PostRouteExtensions $extensions ) {
		$extensions->add( new Title() );
		$extensions->add( new FeaturedMedia() );
		$extensions->add( new ContentBlocks( $this->blockPreparations ) );
		$extensions->add( new ContentAttachments() );
		$extensions->add( new Taxonomies() );
	}

	/**
	 * Registers the default comment route extensions.
	 *
	 * @param CommentRouteExtensions $extensions The comment route extensions collection.
	 * @return void
	 */
	public function comment_route_extensions( CommentRouteExtensions $extensions ) {
		$extensions->add( new CommentAuthorUser() );
	}

	/**
	 * Fires all extension registration actions and attaches REST response filters.
	 *
	 * Called on rest_api_init. Only proceeds if the request has valid API key access.
	 *
	 * @return void
	 */
	public function rest_api_init() {

		if ( ! $this->plugin->security->hasApiKeyAccess() ) {
			return;
		}

		do_action( Plugin::ACTION_REGISTER_BLOCK_PREPARATION_EXTENSIONS, $this->blockPreparations );
		do_action( Plugin::ACTION_REGISTER_POST_ROUTE_EXTENSIONS, $this->postRouteExtensions );
		do_action( Plugin::ACTION_REGISTER_COMMENT_ROUTE_EXTENSIONS, $this->commentRouteExtensions );
		do_action( Plugin::ACTION_REGISTER_USER_ROUTE_EXTENSIONS, $this->userRouteExtensions );
		do_action( Plugin::ACTION_REGISTER_TERM_ROUTE_EXTENSIONS, $this->termRouteExtensions );

		$post_types = get_post_types( [ "public" => true, 'show_in_rest' => true ] );
		foreach ( $this->postRouteExtensions->get() as $extension ) {
			foreach ( $post_types as $type ) {
				add_filter( 'rest_prepare_' . $type, [ $extension, 'response' ], 99, 3 );
			}
			add_filter( 'rest_prepare_revision', [ $extension, 'response' ], 99, 3 );
		}
		foreach ( $this->commentRouteExtensions->get() as $extension ) {
			add_filter( 'rest_prepare_comment', [ $extension, 'response' ], 99, 3 );
		}
		foreach ($this->userRouteExtensions->get() as $extension) {
			add_filter( 'rest_prepare_user', [$extension, 'response'], 99, 3);
		}
		foreach ($this->termRouteExtensions->get() as $extension) {
			foreach ($extension->taxonomies() as $taxonomy) {
				add_filter('rest_prepare_'.$taxonomy, [$extension, 'response'], 99, 3);
			}
		}
	}

}
