<?php


namespace Palasthotel\WordPress\Headless\Components;

use wpdb;

/**
 * Base class for plugin database handlers.
 *
 * Injects the global wpdb instance and calls init() on construction to allow
 * subclasses to set up table names and other properties.
 */
abstract class Database {

	/**
	 * @var wpdb The WordPress database instance.
	 */
	protected wpdb $wpdb;

	/**
	 * Initializes the database handler by injecting wpdb and calling init().
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->init();
	}

	/**
	 * Initializes table names and other properties. Must be implemented by subclasses.
	 *
	 * @return void
	 */
	abstract function init(): void;

	/**
	 * Loads the WordPress database upgrade functions required for dbDelta().
	 *
	 * Subclasses should call parent::createTables() before running dbDelta().
	 *
	 * @return void
	 */
	public function createTables(): void {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	}
}
