<?php

namespace Palasthotel\WordPress\Headless;

class Migration extends Components\Component {

	const LATEST_VERSION = 3;

	private function setSchemaVersion(int $version): bool {
		return update_option(Plugin::OPTION_SCHEMA_VERSION, $version);
	}
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
