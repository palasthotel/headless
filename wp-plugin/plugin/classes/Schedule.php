<?php

namespace Palasthotel\WordPress\Headless;

use Palasthotel\WordPress\Headless\Components\Component;

/**
 * Manages the WordPress cron schedule for periodic cache revalidation.
 *
 * Registers an hourly cron event that processes pending post and comment
 * revalidations from the database queue. Unschedules the event when
 * revalidation is inactive.
 */
class Schedule extends Component {

	public function onCreate(): void {
		parent::onCreate();

		add_action('admin_init', [$this, 'init']);
		add_action(Plugin::SCHEDULE_REVALIDATE, [$this, 'revalidate']);
	}

	/**
	 * Sets up or removes the revalidation cron schedule based on active state.
	 *
	 * Schedules an hourly event if revalidation is active and not yet scheduled.
	 * Removes the event if revalidation is inactive.
	 *
	 * @return void
	 */
	public function init(){
		if($this->plugin->revalidate->isRevalidationInactive()){
			$next = $this->getNextSchedule();
			if($next){
				wp_unschedule_event($next, Plugin::SCHEDULE_REVALIDATE);
			}
			return;
		}
		if(!wp_next_scheduled(Plugin::SCHEDULE_REVALIDATE)){
			wp_schedule_event(time(), 'hourly', Plugin::SCHEDULE_REVALIDATE);
		}
	}

	/**
	 * Returns the timestamp for the next scheduled revalidation run.
	 *
	 * @return int|false The Unix timestamp, or false if not scheduled.
	 */
	public function getNextSchedule(){
		return wp_next_scheduled(Plugin::SCHEDULE_REVALIDATE);
	}

	/**
	 * Returns the Unix timestamp of the last completed revalidation run.
	 *
	 * @return int The timestamp, or 0 if it has never run.
	 */
	public function getLastRevalidationRun(): int{
		return intval(get_option(Plugin::OPTION_LAST_REVALIDATION_RUN, 0));
	}

	/**
	 * Persists the timestamp of the last revalidation run.
	 *
	 * @param int $time Unix timestamp of the run.
	 * @return void
	 */
	public function setLastRevalidationRun(int $time) {
		update_option(Plugin::OPTION_LAST_REVALIDATION_RUN, $time);
	}

	/**
	 * Processes all pending post and comment revalidations from the database queue.
	 *
	 * Called by the cron event. Iterates pending items, triggers revalidation,
	 * updates each item's state to "revalidated" or "error", then fires a side-effect action.
	 *
	 * @return void
	 */
	public function revalidate(){

		if($this->plugin->revalidate->isRevalidationInactive()) return;

		$lastRun = $this->getLastRevalidationRun();
		$now = time();

		$commentIds = $this->plugin->dbRevalidation->getPendingComments();
		foreach ($commentIds as $id){
			$comment = get_comment($id);
			$postId = $comment->comment_post_ID;
			$results = $this->plugin->revalidate->revalidateComments($postId);

			$success = true;
			foreach ($results as $result){
				if($result instanceof \WP_Error){
					$this->plugin->log->warning($result->get_error_message());
					$title = get_the_title($id);
					$this->plugin->log->warning("revalidate comment id: $id ; post: $postId $title");
					$success = false;
				}
			}

			if($success){
				$this->plugin->dbRevalidation->setCommentState($id);
			} else {
				$this->plugin->dbRevalidation->setCommentState($id, "error");
			}
		}

		$postIds = $this->plugin->dbRevalidation->getPendingPosts();

		$this->plugin->log->add("headless: lastRun $lastRun ");
		$this->plugin->log->add("headless: revalidate post ids ".implode(", ", $postIds));

		foreach ($postIds as $id){
			$results = $this->plugin->revalidate->revalidatePost($id);

			$success = true;
			foreach ($results as $result){
				if($result instanceof \WP_Error){
					$this->plugin->log->warning($result->get_error_message());
					$title = get_the_title($id);
					$this->plugin->log->warning("revalidate post id: $id $title");
					$success = false;
				}
			}

			if($success){
				$this->plugin->dbRevalidation->setPostState($id);
			} else {
				$this->plugin->dbRevalidation->setPostState($id, "error");
			}
		}




		// do stuff like revalidating landingpages
		do_action(Plugin::ACTION_REVALIDATION_SIDE_EFFECT, $postIds);



		$this->setLastRevalidationRun($now);

	}


}
