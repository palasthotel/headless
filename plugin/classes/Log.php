<?php

namespace Palasthotel\WordPress\Headless;

/**
 * Provides logging for the headless plugin.
 *
 * Writes info and warning messages to CronLogger if available,
 * falling back to error_log. Also forwards messages to WP-CLI when running in CLI context.
 */
class Log extends Components\Component {
	/**
	 * @var null|\CronLogger\Log
	 */
	private $log;

	public function onCreate(): void {
		parent::onCreate();
		add_action("cron_logger_init", function(\CronLogger\Plugin $logger){
			$this->log = $logger->log;
		});
	}

	/**
	 * Logs an info-level message.
	 *
	 * @param string $message The message to log.
	 * @return void
	 */
	public function add($message){
		if(class_exists('\CronLogger\Log') && $this->log instanceof \CronLogger\Log){
			$this->log->addInfo($message);
		} else {
			error_log($message);
		}
		if(class_exists("\WP_CLI")){
			\WP_CLI::log($message);
		}
	}

	/**
	 * Logs a warning-level message, prefixed with "WARNING:".
	 *
	 * @param string $message The warning message to log.
	 * @return void
	 */
	public function warning($message){
		if(class_exists('\CronLogger\Log') && $this->log instanceof \CronLogger\Log){
			$this->log->addInfo("WARNING: ".$message);
		} else {
			error_log("WARNING: ".$message);
		}
		if(class_exists("\WP_CLI")){
			\WP_CLI::warning($message);
		}
	}
}
