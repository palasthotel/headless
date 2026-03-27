<?php

namespace Palasthotel\WordPress\Headless\Components;

use ReflectionClass;
use ReflectionException;

/**
 * Abstract base class for WordPress plugins using a singleton pattern.
 *
 * Resolves the plugin's file path, URL, and basename via reflection, registers
 * activation and deactivation hooks, and provides multisite support.
 * Subclasses must implement onCreate() for component initialization.
 */
abstract class Plugin {

	/**
	 * @var ReflectionClass Reflection of the concrete plugin class, used to resolve paths.
	 */
	private $ref;

	/**
	 * @var bool Whether the textdomain registration window has passed.
	 */
	private $tooLateForTextdomain;

	/**
	 * @var string Absolute path to the plugin directory, with trailing slash.
	 */
	public $path;

	/**
	 * @var string URL to the plugin directory, with trailing slash.
	 */
	public $url;

	/**
	 * @var string Plugin basename (e.g. plugin-folder/plugin-file.php).
	 */
	public $basename;

	/**
	 * @throws ReflectionException
	 */
	public function __construct() {
		$this->ref      = new ReflectionClass( get_called_class() );
		$this->path     = plugin_dir_path( $this->ref->getFileName() );
		$this->url      = plugin_dir_url( $this->ref->getFileName() );
		$this->basename = plugin_basename( $this->ref->getFileName() );

		$this->tooLateForTextdomain = false;
		$this->onCreate();
		$this->tooLateForTextdomain = true;

		register_activation_hook( $this->ref->getFileName(), [$this, "onActivation"]);
		register_deactivation_hook( $this->ref->getFileName(), [$this, "onDeactivation"]);

	}

	// -----------------------------------------------------------------------------
	// lifecycle methods
	// -----------------------------------------------------------------------------
	/**
	 * Called during construction. Implement to initialize plugin components and hooks.
	 *
	 * @return void
	 */
	abstract function onCreate();

	/**
	 * Handles plugin activation, dispatching to each site on multisite networks.
	 *
	 * @param bool $networkWide Whether the plugin is being activated network-wide.
	 * @return void
	 */
	public function onActivation( $networkWide ) {
		if ( $networkWide ) {
			$this->foreachMultisite( [ $this, 'onSiteActivation' ] );
		} else {
			$this->onSiteActivation();
		}
	}

	/**
	 * Called during activation for the current site. Override to run site-specific setup.
	 *
	 * @return void
	 */
	public function onSiteActivation() {

	}

	/**
	 * Handles plugin deactivation, dispatching to each site on multisite networks.
	 *
	 * @param bool $networkWide Whether the plugin is being deactivated network-wide.
	 * @return void
	 */
	public function onDeactivation( $networkWide ) {
		if ( $networkWide ) {
			$this->foreachMultisite( [ $this, 'onSiteDeactivation' ] );
		} else {
			$this->onSiteDeactivation();
		}
	}

	/**
	 * Called during deactivation for the current site. Override to run site-specific teardown.
	 *
	 * @return void
	 */
	public function onSiteDeactivation() {

	}

	// -----------------------------------------------------------------------------
	// utility methods
	// -----------------------------------------------------------------------------

	/**
	 * Registers the plugin textdomain for translations.
	 *
	 * Must be called within onCreate(). Calling it after construction will log an error.
	 *
	 * @param string $domain                 The textdomain identifier.
	 * @param string $relativeLanguagesPath  Path to the languages directory relative to the plugin file.
	 * @return void
	 */
	public function loadTextdomain( string $domain, string $relativeLanguagesPath ) {
		if ( $this->tooLateForTextdomain ) {
			error_log( "Too late: You need to setTextdomain in onCreate Method of the Plugin class." );
			return;
		}
		add_action( 'init', function () use ( $domain, $relativeLanguagesPath ) {
			load_plugin_textdomain(
				$domain,
				false,
				dirname(plugin_basename($this->ref->getFileName())) . "Plugin.php/" . $relativeLanguagesPath
			);
		} );
	}

	/**
	 * Iterates over all sites in a multisite network and calls the given callback for each.
	 *
	 * Does nothing if not running on a multisite installation.
	 *
	 * @param callable $onSite Callback to invoke for each site (called after switch_to_blog).
	 * @return void
	 */
	public function foreachMultisite(callable $onSite){
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			$network_site = get_network()->site_id;
			$args         = ['fields' => 'ids'];
			$site_ids     = get_sites( $args );

			// run the activation function for each blog id
			foreach ( $site_ids as $site_id ) {
				switch_to_blog( $site_id );
				$onSite();
			}

			// switch back to the network site
			switch_to_blog( $network_site );
		}
	}

	// -----------------------------------------------------------------------------
	// singleton pattern
	// -----------------------------------------------------------------------------
	/**
	 * @var static[] Map of class name to singleton instance.
	 */
	private static $instances = [];

	/**
	 * Returns the singleton instance for the called class.
	 *
	 * @return static The plugin instance.
	 */
	public static function instance() {
		$class = get_called_class();
		if ( ! isset( self::$instances[ $class ] ) ) {
			self::$instances[ $class ] = new static();
		}

		return self::$instances[ $class ];
	}
}
