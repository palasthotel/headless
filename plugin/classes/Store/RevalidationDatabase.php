<?php


namespace Palasthotel\WordPress\Headless\Store;


use Palasthotel\WordPress\Headless\Components\Database;

/**
 * @property string $table
 */
class RevalidationDatabase extends Database {

	function init() {
		$this->table = $this->wpdb->prefix . "headless_revalidate";
	}

	/**
	 * @param int $post_id
	 * @param string $at
	 *
	 * @return bool|int
	 */
	public function addPost( $post_id, $at = "" ) {
		return $this->wpdb->replace(
			$this->table,
			[
				"content_id"    => $post_id,
				"content_type"  => "post",
				"revalidate_at" => empty($at) ? date( "Y-m-d H:i:s" ) : $at,
			]
		);
	}

	/**
	 * @param string $after Y-m-d H:i:s
	 *
	 * @return Int[]
	 */
	public function getPendingPosts(string $after = "") {
		$afterSQL = !empty($after) ? $this->wpdb->prepare("AND revalidate_at >= %s", $after) : "";
		$sql = "SELECT content_id FROM $this->table WHERE content_type = 'post' $afterSQL";
		return  $this->wpdb->get_col( $sql);
	}

	public function createTables() {
		parent::createTables();
		\dbDelta( "CREATE TABLE IF NOT EXISTS $this->table
			(
			 id bigint(20) unsigned auto_increment,
    		 content_id bigint(20) unsigned NOT NULL,
    		 content_type varchar(40) NOT NULL,
    		 revalidate_at DATETIME NOT NULL,
			 primary key (id),
    		 key (content_id),
			 key (content_type),
    		 unique key exact_content (content_id, content_type)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
	}
}