=== Headless ===
Contributors: palasthotel, edwardbock
Donate link: http://palasthotel.de/
Tags: gutenberg, block, developer, utils
Requires at least: 5.0
Tested up to: 6.7.2
Requires PHP: 8.0
Stable tag: 3.0.3
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl

Adds features to use WordPress as headless CMS

== Description ==

Adds features to use WordPress as headless CMS

== Installation ==

1. Upload `headless.zip` to the `/wp-content/plugins/` directory
1. Extract the Plugin to a `headless` Folder
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 3.0.3 =
* **deps-dev:** bump @wordpress/env from 11.2.0 to 11.3.0 in /wp-plugin (243def9)
* **deps-dev:** bump @wordpress/env from 11.2.0 to 11.3.0 in /wp-plugin (de337f6)
* **deps-dev:** bump @wordpress/env from 11.3.0 to 11.4.0 in /wp-plugin (63aa847)
* **deps-dev:** bump @wordpress/env from 11.3.0 to 11.4.0 in /wp-plugin (f01c5f8)
* **deps-dev:** bump @wordpress/scripts from 31.7.0 to 31.8.0 in /wp-plugin (ac25403)
* **deps-dev:** bump @wordpress/scripts from 31.8.0 to 32.0.0 in /wp-plugin (8d02de2)
* **deps-dev:** bump @wordpress/scripts in /wp-plugin (0034b96)
* **deps-dev:** bump @wordpress/scripts in /wp-plugin (85a8ec4)
* **deps:** bump @wordpress/components from 32.4.0 to 32.5.0 in /wp-plugin (03806b2)
* **deps:** bump @wordpress/components in /wp-plugin (4c753f2)
* **deps:** bump @wordpress/data from 10.42.0 to 10.43.0 in /wp-plugin (d5cbd41)
* **deps:** bump @wordpress/data from 10.42.0 to 10.43.0 in /wp-plugin (f46823e)
* **deps:** bump @wordpress/data from 10.43.0 to 10.44.0 in /wp-plugin (445c834)
* **deps:** bump @wordpress/data from 10.43.0 to 10.44.0 in /wp-plugin (afb7433)
* **deps:** bump @wordpress/edit-post from 8.42.0 to 8.44.0 in /wp-plugin (10a3aff)
* **deps:** bump @wordpress/edit-post from 8.42.0 to 8.44.0 in /wp-plugin (f122b06)
* **deps:** bump @wordpress/element from 6.42.0 to 6.43.0 in /wp-plugin (1c1015d)
* **deps:** bump @wordpress/element from 6.42.0 to 6.43.0 in /wp-plugin (7a65e87)
* **deps:** bump @wordpress/icons from 10.32.0 to 12.0.0 in /wp-plugin (5b3c813)
* **deps:** bump @wordpress/icons from 10.32.0 to 12.0.0 in /wp-plugin (35efa8c)
* **deps:** bump @wordpress/icons from 12.0.0 to 12.1.0 in /wp-plugin (802cb80)
* **deps:** bump @wordpress/icons from 12.0.0 to 12.1.0 in /wp-plugin (8590637)
* **deps:** bump @wordpress/plugins from 7.42.0 to 7.43.0 in /wp-plugin (4799e3f)
* **deps:** bump @wordpress/plugins from 7.42.0 to 7.43.0 in /wp-plugin (f02b5b7)
* **deps:** bump @wordpress/plugins from 7.43.0 to 7.44.0 in /wp-plugin (1629fa2)
* **deps:** bump @wordpress/plugins from 7.43.0 to 7.44.0 in /wp-plugin (d66dca1)


= 3.0.2 =
* **core-image:** use alt text from block if set (b799413)
* **core-image:** use block alt if set (e2d0e6c)
* **core-image:** use image alt text from block props if set in block (0ed3974)


= 3.0.1 =
* **core-image:** use alt text from block if set (b799413)
* **core-image:** use block alt if set (e2d0e6c)
* **core-image:** use image alt text from block props if set in block (0ed3974)


= 2.3.1 =
* Fix: Preview links

= 2.3.0 =
* Feature: Revalidate feature can be deactivated via hook
* Feature: Preview feature can be deactivated via hook
* Fix Menu Schema
* Update: NPM Packages

= 2.2.4 =
* Feature: Add filter "headless_rest_response_data"
* Fix: Gutenberg preview in new tab link

= 2.2.3 =
* Bugfix: fatal error without hl_post_type in rest api

= 2.2.2 =
* Feature: revalidate comments on schedule
* Bugfix: hl_post_type filter with any now working


= 2.2.0 =
* Feature: Revalidate pending posts via dashboard button
* Fix: date format on dashboard

= 2.1.2 =
* parallel to npm package update

= 2.1.0 =
* Feature: smaller response sizes with headless_variant=teaser

= 2.0.0 =
* BREAKING CHANGES
* Moves from @palasthotel/wp-fetch to @palasthotel/wp-rest

= 1.9.3 =
* Bugfix: post preview with wordpress 6.4.x fixed

= 1.9.2 =
* Bugfix: Allow revalidation timestamp to be null

= 1.9.1 =
* Bugfix: Undefined property innerHTML in ImageBockPreparation.php

= 1.9.0 =
* Feature: Add headless_revalidate_permalink_path filter
* Optimization: Add revalidation state "error"
* Optimization: Add cli log messages
* Optimization: Add cron logger support for messages

= 1.8.0 =
* Feature: Dashboard widget
* Refactor: revalidation hooks and process
* Optimization: Gutenberg panel
* Optimization: migration to new revalidation database schema

= 1.7.5 =
* Optimization: Preview links are only changed for headless post types

= 1.7.4 =
* Bugfix: view preview notice fix

= 1.7.3 =
* Bugfix: save post in draft state before open preview tab

= 1.7.2 =
* Added: Filter 'headless_is_headless_post_type'
* Fixed some issues with previews

= 1.7.1 =
* Use taxonomy name for headless posts as fallback for rest_base

= 1.7.0 =
* BREAKING CHANGE: core/block for block references has changed
* Optimization: changed preview url magic to redirect
* Removed: filter headless_post_link because it is not healthy
* Removed: filter headless_preview_redirect because it is not in use

= 1.6.2 =
* Add headless_rest_api_prepare_post filter for uniform post responses

= 1.6.1 =
* Optimization: revalidation uses url array
* Bugfix: Remove domain from page rest api response

= 1.6.0 =
* Feature: Tag Cloud Block extension
* Feature: User extensions
* Feature: Term extensions
* Optimization: stale-while-revalidate cache-control header for headless requests to the rest api
* Optimization: api key restriction

= 1.5.5 =
* Headless settings as rest api

= 1.5.3 =
* Featured media sizes to rest api

= 1.5.1 =
* Optimization: add image sizes

= 1.5.0 =
* Feature: Comment extensions with display_name and nickname
* Feature: Revalidation via gutenberg button
* Feature: Revalidation system via schedules

= 1.4.2 =
* add embed block preparations
* update wp fetch lib

= 1.4.1 =
* renamed attribute for reference block because of react attribute name problems

= 1.4.0 =
* add reference block preparation for content resolution
* add paragraph block preparation for smaller response size

= 1.3.0 =
* add featured media attributes to posts
* extend core/image and core/gallery

= 1.2.1 =
* Allow blockName null for freeform blocks

= 1.2.0 =
* Post Meta Query for rest requests

= 1.1.1 =
* Preview feature

= 1.0.0 =
* First release

== Upgrade Notice ==

== Arbitrary section ==

* BREAKING CHANGE 1.7.0: core/block for block references has changed
