<?php

namespace Palasthotel\WordPress\Headless;

class Migration extends Components\Component {

	const LATEST_VERSION = 1;

	private function setSchemaVersion(int $version): bool {
		return update_option(Plugin::OPTION_SCHEMA_VERSION, $version);
	}
	private function getSchemaVersion(): int {
		return intval(get_option(Plugin::OPTION_SCHEMA_VERSION, 0));
	}

	public function onCreate() {
		parent::onCreate();

		if($this->getSchemaVersion() < 1){
			$this->plugin->dbRevalidation->createTables();
			$this->setSchemaVersion(1);
		}

	}

}