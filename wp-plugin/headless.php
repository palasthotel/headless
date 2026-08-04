<?php
/**
 * Plugin Name:       Headless - DEV
 * Description:       Loads public/headless.php when this repository is checked out into wp-content/plugins/. Not shipped — the released plugin is the content of public/.
 * Version:           X.X.X
 * Requires at least: 5.0
 * Tested up to:      7.0.2
 * Requires PHP:      8.0
 * Author:            PALASTHOTEL by Edward
 * Author URI:        http://www.palasthotel.de
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 */

use Palasthotel\WordPress\Headless\Plugin;

include dirname(__FILE__) . "/public/headless.php";

register_activation_hook(__FILE__, function ($multisite) {
	Plugin::instance()->onActivation($multisite);
});

register_deactivation_hook(__FILE__, function ($multisite) {
	Plugin::instance()->onDeactivation($multisite);
});
