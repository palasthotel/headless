<?php


namespace Palasthotel\WordPress\Headless\Store;


use Palasthotel\WordPress\Headless\Components\Database;

/**
 * Manages the revalidation queue database table.
 *
 * Stores posts and comments pending cache revalidation, tracks their state
 * (pending, revalidated, error), and provides methods to add, query, and update entries.
 */
class RevalidationDatabase extends Database {

	const TYPE_POST = "post";
	const TYPE_COMMENT = "comment";

	/**
	 * @var string The full database table name for revalidation queue entries.
	 */
	public string $table;

	function init(): void {
		$this->table = $this->wpdb->prefix . "headless_revalidate";
	}

	/**
	 * Inserts or replaces a content entry in the revalidation queue with state "pending".
	 *
	 * @param string $id   The content ID.
	 * @param string $type The content type (post or comment).
	 * @return int|false The number of rows affected, or false on error.
	 */
	private function addContent(string $id, string $type) {
		return $this->wpdb->replace(
			$this->table,
			[
				"content_id" => $id,
				"content_type" => $type,
				"revalidated_at" => null,
				"revalidation_state" => "pending",
			]
		);
	}

	/**
	 * Adds a post to the revalidation queue.
	 *
	 * @param int $post_id The post ID to queue.
	 * @return int|false The number of rows affected, or false on error.
	 */
	public function addPost(int $post_id) {
		return $this->addContent($post_id, self::TYPE_POST);
	}

	/**
	 * Adds a comment to the revalidation queue.
	 *
	 * @param string $comment_id The comment ID to queue.
	 * @return int|false The number of rows affected, or false on error.
	 */
	public function addComment(string $comment_id) {
		return $this->addContent($comment_id, self::TYPE_COMMENT);
	}


	/**
	 * @return Int[]
	 */
	/**
	 * Returns all content IDs with state "pending" for the given content type.
	 *
	 * @param string $type The content type (post or comment).
	 * @return int[] Array of content IDs.
	 */
	private function getPendingContents(string $type): array {
		$sql = $this->wpdb->prepare(
			"SELECT content_id FROM $this->table WHERE content_type = '%s' AND revalidation_state = 'pending'",
			$type
		);
		return $this->wpdb->get_col($sql);
	}

	/**
	 *
	 * @return Int[]
	 */
	public function getPendingPosts(): array {
		return $this->getPendingContents(self::TYPE_POST);
	}

	/**
	 * Returns all comment IDs currently pending revalidation.
	 *
	 * @return int[] Array of comment IDs.
	 */
	public function getPendingComments(): array {
		return $this->getPendingContents(self::TYPE_COMMENT);
	}


	/**
	 * Counts content entries with state "pending" for the given content type.
	 *
	 * @param string $type The content type (post or comment).
	 * @return int The count of pending entries.
	 */
	private function countPendingContents(string $type): int {
		$sql = $this->wpdb->prepare(
			"SELECT count(content_id) FROM $this->table WHERE content_type = '%s' AND revalidation_state = 'pending'",
			$type
		);
		return intval($this->wpdb->get_var($sql));
	}

	/**
	 * Returns the count of posts currently pending revalidation.
	 *
	 * @return int The number of pending posts.
	 */
	public function countPendingPosts(): int {
		return $this->countPendingContents(self::TYPE_POST);
	}

	/**
	 * Returns the count of comments currently pending revalidation.
	 *
	 * @return int The number of pending comments.
	 */
	public function countPendingComments(): int {
		return $this->countPendingContents(self::TYPE_COMMENT);
	}

	/**
	 * Updates the revalidation state and timestamp for a content entry.
	 *
	 * @param int    $id    The content ID.
	 * @param string $type  The content type (post or comment).
	 * @param string $state The new state (e.g. "revalidated" or "error").
	 * @return int|false The number of rows updated, or false on error.
	 */
	public function setContentState(int $id, string $type, $state = "revalidated") {
		return $this->wpdb->update(
			$this->table,
			[
				"revalidated_at" => current_time('mysql'),
				"revalidation_state" => $state,
			],
			[
				"content_id" => $id,
				"content_type" => $type,
			],
			["%s", "%s"],
			["%d", "%s"]
		);
	}

	/**
	 * Updates the revalidation state for a post entry.
	 *
	 * @param int    $post_id The post ID.
	 * @param string $state   The new state (default "revalidated").
	 * @return int|false The number of rows updated, or false on error.
	 */
	public function setPostState(int $post_id, $state = "revalidated") {
		return $this->setContentState($post_id, self::TYPE_POST, $state);
	}

	/**
	 * Updates the revalidation state for a comment entry.
	 *
	 * @param int    $comment_id The comment ID.
	 * @param string $state      The new state (default "revalidated").
	 * @return int|false The number of rows updated, or false on error.
	 */
	public function setCommentState(int $comment_id, $state = "revalidated") {
		return $this->setContentState($comment_id, self::TYPE_COMMENT, $state);
	}

	/**
	 * Creates the revalidation queue table if it does not already exist.
	 *
	 * @return void
	 */
	public function createTables(): void {
		parent::createTables();
		\dbDelta("CREATE TABLE IF NOT EXISTS $this->table
			(
			 id bigint(20) unsigned auto_increment,
    		 content_id bigint(20) unsigned NOT NULL,
    		 content_type varchar(40) NOT NULL,
    		 revalidation_state varchar(30),
    		 revalidated_at TIMESTAMP NULL default null,
			 primary key (id),
    		 key (content_id),
			 key (content_type),
    		 key (revalidation_state),
    		 key (revalidated_at),
    		 unique key exact_content (content_id, content_type)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
	}
}
