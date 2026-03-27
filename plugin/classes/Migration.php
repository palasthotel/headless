<?php

namespace Palasthotel\WordPress\Headless;

/**
 * Handles database schema migrations for the headless plugin.
 *
 * Checks the current schema version on component creation and runs
 * any necessary upgrade steps, including dropping and recreating tables.
 */
class Migration extends Components\Component {

	const LATEST_VERSION = 3;

	/**
	 * Persists the current schema version to the database.
	 *
	 * @param int $version The schema version number to store.
	 * @return bool True if the option was updated, false otherwise.
	 */
	private function setSchemaVersion(int $version): bool {
		return update_option(Plugin::OPTION_SCHEMA_VERSION, $version);
	}
	/**
	 * Retrieves the current schema version from the database.
	 *
	 * @return int The stored schema version, or 0 if not set.
	 */
	private function getSchemaVersion(): int {
		return intval(get_option(Plugin::OPTION_SCHEMA_VERSION, 0));
	}

	public function onCreate(): void {
		parent::onCreate();

		if($this->getSchemaVersion() < 3){
			// drop every table that was created before version 3
			$tableName = $this->plugin->dbRevalidation->table;
			global $wpdb;
			$wpdb->query("DROP TABLE IF EXISTS $tableName");
			$this->plugin->dbRevalidation->createTables();
			$this->setSchemaVersion(3);
		}

	}

}
