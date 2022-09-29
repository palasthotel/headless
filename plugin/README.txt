=== Headless ===
Contributors: palasthotel, edwardbock
Donate link: http://palasthotel.de/
Tags: gutenberg, block, developer, utils
Requires at least: 5.0
Tested up to: 6.0.2
Requires PHP: 7.4
Stable tag: 1.6.0
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

= 1.6.1 =
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


