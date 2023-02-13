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

	public function getNextSchedule(){
		return wp_next_scheduled(Plugin::SCHEDULE_REVALIDATE);
	}

	public function getLastRevalidationRun(): int{
		return intval(get_option(Plugin::OPTION_LAST_REVALIDATION_RUN, 0));
	}

	public function setLastRevalidationRun(int $time) {
		update_option(Plugin::OPTION_LAST_REVALIDATION_RUN, $time);
	}

	public function revalidate(){
		$lastRun = $this->getLastRevalidationRun();
		$now = time();

		$postIds = $this->plugin->dbRevalidation->getPendingPosts();

		if(class_exists("\WP_CLI")){
			\WP_CLI::log("headless: lastRun $lastRun ");
			\WP_CLI::log("headless: revalidate post ids ".implode(", ", $postIds));
		}
		foreach ($postIds as $id){
			$this->plugin->revalidate->revalidatePost($id);
			$this->plugin->dbRevalidation->setPostRevalidated($id);
		}

		// do stuff like revalidating landingpages
		do_action(Plugin::ACTION_REVALIDATION_SIDE_EFFECT, $postIds);

		$this->setLastRevalidationRun($now);

	}


}
