<?php

namespace Palasthotel\WordPress\Headless;

/**
 * Utility helpers for the headless plugin.
 */
class Utils {

	/**
	 * Sanitizes HTML content, allowing only a safe subset of tags, and strips newlines.
	 *
	 * Permitted tags: a (href, target, rel), b, i, strong.
	 *
	 * @param string $html The raw HTML string to sanitize.
	 * @return string The sanitized HTML with newlines removed.
	 */
	public static function prepareHTML($html){
		return  str_replace( "\n", "", wp_kses(
			$html,
			[
				"a"      => [
					"href" => [],
					"target" => [],
					"rel" => [],
				],
				"b"      => [],
				"i"      => [],
				"strong" => []
			]
		));
	}
}