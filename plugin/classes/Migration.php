<?php

namespace Palasthotel\WordPress\Headless;

class Migration extends Components\Component {

	const LATEST_VERSION = 2;

	private function setSchemaVersion(int $version): bool {
		return update_option(Plugin::OPTION_SCHEMA_VERSION, $version);
	}
	private function getSchemaVersion(): int {
		return intval(get_option(Plugin::OPTION_SCHEMA_VERSION, 0));
	}

	public function onCreate() {
		parent::onCreate();

		if($this->getSchemaVersion() < 2){
			// drop every table that was created before version 2
			$tableName = $this->plugin->dbRevalidation->table;
			$this->plugin->dbRevalidation->wpdb->query("DROP TABLE IF EXISTS $tableName");
			$this->plugin->dbRevalidation->createTables();
			$this->setSchemaVersion(2);
		}

	}

}
