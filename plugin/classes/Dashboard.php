<?php

namespace Palasthotel\WordPress\Headless;

class Dashboard extends Components\Component {
	public function onCreate() {
		parent::onCreate();

		add_action('wp_dashboard_setup', [$this, 'setup']);

	}

	public function setup(){
		wp_add_dashboard_widget(
			Plugin::DOMAIN,
			__("Headless", Plugin::DOMAIN),
			array($this, 'render')
		);
	}

	public function render(){
        $timeFormat = get_option('time_format');
        $dateFormat = get_option('date_format');

        $lastRevalidationRun = $this->plugin->schedule->getLastRevalidationRun();
        $lastRevalidationRunDate = strtotime($lastRevalidationRun);
        $nextRevalidationRun = $this->plugin->schedule->getNextSchedule();

		$frontends = $this->plugin->headquarter->getFrontends();
		?>
            <p>Last automatic revalidation run: <?= date_i18n($dateFormat,$lastRevalidationRunDate)." ".date_i18n($timeFormat, $lastRevalidationRunDate); ?></p>
            <p>Next automatic revalidation run: <?= ($nextRevalidationRun === false) ? "🚨 Broken" : date_i18n($dateFormat, $nextRevalidationRun)." ".date_i18n($timeFormat, $nextRevalidationRun); ?></p>
            <p>Pending posts to be revalidated: <?= $this->plugin->dbRevalidation->countPendingPosts(); ?></p>
            <p>Available frontends:</p>
            <ol>
                <?php
                foreach ($frontends as $frontend){
                    $cleared = false;
                    $path = "";
	                if(isset($_POST["headless_invalidate"])){
		                $path = "/".urlencode(sanitize_text_field($_POST["headless_invalidate"]));
		                $cleared = $this->plugin->revalidate->revalidateByPath($frontend, $path);
	                }
                    $basePath = $frontend->getBaseUrl();
                    $suffix = "";
                    if($cleared === true){
                        $suffix = "$path 🧹 <strong>cleared</strong>";
                    } else if($cleared instanceof \WP_Error){
                        $msg = $cleared->get_error_message();
                        $suffix = "$path 🚨 $msg";
                    }
                    echo "<li>$basePath$suffix</li>";
                }
                ?>
            </ol>
            <form method="post">
		        /<input type="text" name="headless_invalidate" placeholder="path/to/invalidate" />
            </form>
		<?php
	}
}
