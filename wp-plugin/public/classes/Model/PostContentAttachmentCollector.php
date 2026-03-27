<?php

namespace Palasthotel\WordPress\Headless\Model;

/**
 * Tracks attachment IDs referenced within post content during block preparation.
 *
 * Acts as a static registry mapping post IDs to arrays of attachment IDs
 * found in the post's content blocks.
 */
class PostContentAttachmentCollector {

	/**
	 * @var array<int, int[]> Map of post ID to array of attachment IDs.
	 */
	public static array $map = [];

	/**
	 * Returns all attachment IDs collected for the given post.
	 *
	 * @param int $postId The post ID.
	 * @return int[] Array of attachment IDs, or empty array if none collected.
	 */
	public static function get($postId){
		return static::$map[ $postId ] ?? [];
	}

	/**
	 * Registers an attachment ID for the given post, avoiding duplicates.
	 *
	 * @param int $postId       The post ID to associate the attachment with.
	 * @param int $attachmentId The attachment ID to register.
	 * @return void
	 */
	public static function add($postId, $attachmentId){
		$attachmentId = intval($attachmentId);
		if(!isset(static::$map[$postId])){
			static::$map[$postId] = [];
		}

		if(!in_array($attachmentId, static::$map[$postId])){
			static::$map[$postId][] = $attachmentId;
		}
	}
}