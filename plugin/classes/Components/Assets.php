<?php


namespace Palasthotel\WordPress\Headless\Components;

/**
 * Helper for registering plugin scripts and styles with automatic versioning.
 *
 * Resolves file paths relative to the plugin root, uses file modification time
 * for cache busting, and reads webpack-generated asset manifests for scripts.
 */
class Assets {

	/**
	 * @var Plugin The plugin instance used to resolve paths and URLs.
	 */
	private Plugin $plugin;

	/**
	 * @param Plugin $plugin The plugin instance.
	 */
	public function __construct(Plugin $plugin) {
		$this->plugin = $plugin;
	}

	/**
	 * Registers a stylesheet with WordPress using a plugin-relative path.
	 *
	 * Logs an error and returns false if the file does not exist.
	 *
	 * @param string   $handle            The stylesheet handle.
	 * @param string   $pluginPathToFile  Path to the file relative to the plugin root.
	 * @param string[] $dependencies      Array of registered stylesheet handles this depends on.
	 * @param string   $media             The media type for the stylesheet.
	 * @return bool True if the stylesheet was registered successfully, false otherwise.
	 */
	public function registerStyle(string $handle, string $pluginPathToFile, array $dependencies = [], string $media = 'all'): bool {
		$filePath = $this->plugin->path . $pluginPathToFile;
		$fileUrl = $this->plugin->url . $pluginPathToFile;
		if (!file_exists($filePath)) {
			error_log("Style file does not exist: $filePath");

			return false;
		}

		return wp_register_style($handle, $fileUrl, $dependencies, filemtime($filePath), $media);

	}

	/**
	 * Registers a script with WordPress using a plugin-relative path.
	 *
	 * If a corresponding .asset.php manifest exists, its dependencies and version
	 * are used; otherwise filemtime is used as the version. Logs an error and
	 * returns false if the file does not exist.
	 *
	 * @param string   $handle            The script handle.
	 * @param string   $pluginPathToFile  Path to the file relative to the plugin root.
	 * @param string[] $dependencies      Additional script dependencies to merge with the manifest.
	 * @param bool     $footer            Whether to enqueue the script in the footer.
	 * @return bool True if the script was registered successfully, false otherwise.
	 */
	public function registerScript(string $handle, string $pluginPathToFile, array $dependencies = [], bool $footer = true): bool {
		$filePath = $this->plugin->path . $pluginPathToFile;
		if (!file_exists($filePath)) {
			error_log("Script file does not exist: $filePath");

			return false;
		}
		$assetsFilePath = "";
		if ($this->endsWithJS($filePath)) {
			$assetsFilePath = str_replace(".js", ".asset.php", $filePath);
		}
		if (!empty($assetsFilePath) && file_exists($assetsFilePath)) {
			$info = include $assetsFilePath;
		} else {
			$info["dependencies"] = [];
			$info["version"] = filemtime($filePath);
		}

		return wp_register_script(
			$handle,
			$this->plugin->url . $pluginPathToFile,
			array_merge($info["dependencies"], $dependencies),
			$info["version"],
			$footer
		);

	}

	/**
	 * Checks whether the given string ends with ".js".
	 *
	 * @param string $haystack The string to check.
	 * @return bool True if the string ends with ".js".
	 */
	private function endsWithJS($haystack): bool {
		$length = strlen(".js");
		if (!$length) {
			return true;
		}

		return substr($haystack, -$length) === ".js";
	}
}
