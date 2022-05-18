# Headless

This plugin adds features to use WordPress as headless CMS.

## New routes

The namespace for all routes is `headless/v1`.

`/menus` - All registered menus

`/menus/{slug}` - Single menu

## Extensions

Post types are extended with the full size **featured_media_url** `string` if existing or `false`.