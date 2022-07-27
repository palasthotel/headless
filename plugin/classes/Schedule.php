<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

class Schedule extends Component {



	public function onCreate() {
		parent::onCreate();

		add_action('admin_init', [$this, 'init']);
		add_action(Plugin::SCHEDULE_REVALIDATE, [$this, 'revalidate']);
	}

	public function init(){
		if(!wp_next_scheduled(Plugin::SCHEDULE_REVALIDATE)){
			wp_schedule_event(time(), 'hourly', Plugin::SCHEDULE_REVALIDATE);
		}
	}

	public function revalidate(){
		$lastRun = get_option(Plugin::OPTION_LAST_REVALIDATION_RUN, "");
		$now = date("Y-m-d H:i:s");

		$postIds = $this->plugin->dbRevalidation->getPendingPosts($lastRun);

		foreach ($postIds as $id){
			$this->plugin->revalidate->revalidatePost($id);
		}

		update_option(Plugin::OPTION_LAST_REVALIDATION_RUN, $now);

	}


}