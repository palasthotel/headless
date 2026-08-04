=== Headless ===
Contributors: palasthotel, edwardbock, janaeggebrecht
Donate link: http://palasthotel.de/
Tags: gutenberg, block, developer, utils
Requires at least: 5.0
Tested up to: 7.0.2
Requires PHP: 8.0
Stable tag: 3.0.4
License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Adds features to use WordPress as headless CMS

== Description ==

Adds features to use WordPress as a headless CMS: extra fields and prepared block
content on the REST API, custom routes for menus and site settings, a preview that
points at your frontend instead of the WordPress theme, and cache revalidation for
frontends that support it.

= Configuration =

The plugin is configured with constants in `wp-config.php`:

* `HEADLESS_HEAD_BASE_URL` — base URL of your frontend. Preview and revalidation requests go here.
* `HEADLESS_SECRET_TOKEN` — shared token sent to the frontend's `/api/preview` and `/api/revalidate` endpoints.
* `HEADLESS_API_KEY_HEADER_KEY` and `HEADLESS_API_KEY_HEADER_VALUE` — require this HTTP header on requests that use the plugin's REST additions.

= Who can read the responses =

The plugin's REST additions activate on requests carrying `?headless=true`. **That
query parameter is a routing flag, not authentication** — anyone can set it. Unless
you configure `HEADLESS_API_KEY_HEADER_KEY` and `HEADLESS_API_KEY_HEADER_VALUE`, the
`/headless/v1/menus` and `/headless/v1/settings` routes and the added post fields are
readable by anyone who can reach your REST API. Configure the API key if that is not
what you want.

`HEADLESS_SECRET_TOKEN` is a single shared secret, and the admin pages hand it to the
browser so the editor can open a preview. Every user who can edit posts — Contributor
upwards — can therefore read it and use it against your frontend's preview and
revalidation endpoints directly. Treat it as a secret shared with your whole editorial
team, and give the frontend its own rate limiting.

== Installation ==

1. Upload `headless.zip` to the `/wp-content/plugins/` directory
1. Extract the Plugin to a `headless` Folder
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Application passwords stopped being available after updating =

Earlier versions forced application passwords on unconditionally. WordPress itself
only offers them over HTTPS or in a local environment, because the password travels in
an `Authorization` header on every request. The plugin no longer overrides that. If you
knowingly want them on a plain-HTTP site, opt back in:

`add_filter( 'headless_application_passwords_available', '__return_true' );`

The better fix is a TLS certificate.

= Comment responses no longer contain author_user.nickname =

`nickname` defaults to the account's login name, and the comments endpoint is public,
so the field was handing out usernames. It is now only included for requests by a user
who may list users. `display_name` is unchanged and is what you want for rendering.

= Queries on meta keys starting with an underscore return everything =

WordPress treats a leading underscore as protected meta. `hl_meta_keys`,
`hl_meta_exists` and `hl_meta_not_exists` now ignore protected keys for requests
that may not edit posts — otherwise a `like` comparison lets anyone read a protected
value one character at a time by watching which posts come back. Authenticated
requests that may edit posts are unaffected. To decide per key yourself:

`add_filter( 'headless_meta_key_is_queryable', function( $queryable, $key ) {
	return $key === '_my_public_key' ? true : $queryable;
}, 10, 2 );`

= hl_post_type no longer accepts every post type =

Only post types that are public and exposed in the REST API are accepted, and `any`
resolves to that same set. Post types WordPress would not show in the REST API are no
longer passed into the query.

== Screenshots ==

== Changelog ==

= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.4 =
* leave the application password SSL check to WordPress (8f5701b)
* repair the single menu route (b735870)
* require a nonce for the revalidation endpoints (4e6bd21)
* stop protected post meta from being queried anonymously (5f9f8ee)
* stop publishing comment authors' login names (1c73947)


= 3.0.3 =
* **deps-dev:** updates


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
