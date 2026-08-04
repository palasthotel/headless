# Headless

Use WordPress as a headless CMS. This repository contains two components:

- 🐘 **WordPress Plugin** (`wp-plugin/`) — extends the WP REST API with additional fields, custom routes, preview support, and ISR cache revalidation
- 📦 **npm Package** (`npm-package/`) — TypeScript types, Zod schemas and request builders for frontend consumption

---

## 🐘 WordPress Plugin

Install the plugin from [WordPress.org](https://wordpress.org/plugins/headless/) or via the admin dashboard.

### Authentication

All headless REST endpoints require the `?headless=true` query parameter. **That is a
routing flag, not authentication** — anyone can set it. To actually restrict access,
require an API key header:

```php
// wp-config.php
define('HEADLESS_API_KEY_HEADER_KEY', 'X-Headless-Token');
define('HEADLESS_API_KEY_HEADER_VALUE', 'your-secret');
```

If both constants are left empty (default), no header restriction is applied and the
plugin's routes and added fields are readable by anyone who can reach the REST API.
The comparison is done with `hash_equals()`.

`HEADLESS_SECRET_TOKEN` is a different thing: one shared secret, sent to the
frontend's `/api/preview` and `/api/revalidate`. The admin pages put it into the page
so the editor can open a preview, so **every user with `edit_posts` can read it** —
Contributor upwards. Rate-limit those endpoints on the frontend and do not treat the
token as a per-user credential.

The plugin no longer forces WordPress application passwords to be available. Core
offers them over HTTPS or in a local environment; to override that, use the
`headless_application_passwords_available` filter.

### REST API Extensions

The plugin adds fields to existing WP REST responses (posts, revisions, comments):

| Field | Description |
|---|---|
| `content.headless_blocks` | Parsed Gutenberg blocks with prepared data |
| `content.headless_attachment_ids` | Attachment IDs referenced in content |
| `featured_media_url` | Full URL of the featured image |
| `featured_media_src` | Image source as `[url, width, height, resized]` |
| `featured_media_sizes` | All registered image sizes |
| `featured_media_caption/description/alt` | Featured image meta |
| `[taxonomy]` | Term IDs for all REST-visible taxonomies |
| `author_user` | Comment author's `display_name` (if WP user). `nickname` only for requests that may `list_users`, since it defaults to the login name |

Add `?headless_variant=teaser` to strip heavy fields (block content, rendered HTML) for list views.

### Custom Routes

Namespace: `headless/v1`

| Route | Description |
|---|---|
| `GET /menus` | All registered nav menus, keyed by slug |
| `GET /menus/{slug}` | Single nav menu |
| `GET /settings` | Reading settings: `front_page`, `page_on_front`, `home_url` |

### Preview

Redirects the WordPress "Preview" button to your headless frontend:

```php
define('HEADLESS_HEAD_BASE_URL', 'https://your-frontend.com');
define('HEADLESS_SECRET_TOKEN', 'your-preview-secret');
```

The frontend receives a request to `/api/preview?secret_token=...&post={id}&post_type={type}`.

### Cache Revalidation (ISR)

Automatically triggers cache revalidation on your frontend when posts or comments are saved. The plugin calls your frontend's revalidation endpoint with a path or cache tag:

```
GET {HEADLESS_HEAD_BASE_URL}/api/revalidate?secret_token=...&tag=post-{id}
GET {HEADLESS_HEAD_BASE_URL}/api/revalidate?secret_token=...&path=/your-slug
```

You can also trigger revalidation programmatically:

```php
headless_revalidate_by_post_id($post_id);
headless_revalidate_by_path('/some/path');
```

### Extensibility

Register custom block preparations or extend route responses via WordPress actions:

```php
add_action('headless_register_block_preparation_extensions', function($preparations) { ... });
add_action('headless_register_post_route_extensions', function($extensions) { ... });
add_action('headless_register_comment_route_extensions', function($extensions) { ... });
```

Two filters control what the query parameters are allowed to reach:

```php
// Allow application passwords where core would not (e.g. plain HTTP). Off by default.
add_filter('headless_application_passwords_available', '__return_true');

// Decide per key whether hl_meta_* may query it. Protected keys (leading underscore)
// are queryable only for requests that may edit posts.
add_filter('headless_meta_key_is_queryable', function($queryable, $key) {
    return $key === '_my_public_key' ? true : $queryable;
}, 10, 2);
```

---

## 📦 npm Package

```bash
npm install @palasthotel/headless
```

Provides TypeScript types, Zod v4 schemas and URL builders that match the plugin's REST API.

### Request Builders

```ts
import {
  getPostsWithBlocksRequest,
  getPostWithBlocksRequest,
  getMenusRequest,
  getMenuRequest,
  getSettingsRequest,
} from '@palasthotel/headless';

const url = getPostsWithBlocksRequest({ baseUrl: 'https://cms.example.com' });
const url = getMenuRequest({ baseUrl: 'https://cms.example.com' }, 'main-menu');
```

All builders append `?headless=true` automatically.

### Schemas

```ts
import { postWithBlocksResponseSchema, settingsResponseSchema } from '@palasthotel/headless';

const post = postWithBlocksResponseSchema.parse(data);

// settingsResponseSchema is a discriminated union on front_page: "posts" | "page"
const settings = settingsResponseSchema.parse(data);
```

Use `sustainingParse` instead of `.parse()` to log schema errors without throwing:

```ts
import { sustainingParse, isParseError } from '@palasthotel/headless';

const result = sustainingParse(data, postWithBlocksResponseSchema);
if (isParseError(result)) { /* handle gracefully */ }
```

### Types

```ts
import type {
  HeadlessPostResponse,
  HeadlessCommentResponse,
  MenusResponse,
  SettingsResponse,
  Block,
} from '@palasthotel/headless';
```

---

## Repository layout

`wp-plugin/public/` is exactly what ships to WordPress.org. Everything outside it is
repository-only.

| Path | Description |
|---|---|
| `wp-plugin/public/` | the released plugin — plugin header, `classes/`, built `dist/`, `vendor/` autoloader, `README.txt` |
| `wp-plugin/src/` | editor assets, built into `public/dist/` by `wp-scripts` |
| `wp-plugin/headless.php` | DEV wrapper, loads `public/headless.php` when the repository is checked out into `wp-content/plugins/` |
| `npm-package/` | the published `@palasthotel/headless` package |
| `bin/` | release helper scripts |
| `.github/workflows/` | CI/CD — see [.github/WORKFLOWS.md](.github/WORKFLOWS.md) |

## Development

```sh
cd wp-plugin  && npm ci && npm run build   # editor assets → public/dist/
cd npm-package && npm ci && npm run build  # tsdown → dist/
npm test                                   # jest (npm-package)
npm run lint                               # tsc --noEmit (npm-package)
```

`bash bin/pack.sh` stages the payload in `build/headless/` and zips it to
`headless.zip` — the same payload the release deploys. It needs `composer`, because
the packed copy gets a freshly generated `--no-dev` autoloader and the composer files
are dropped from it.

## Releasing

Both components are released by [release-please](https://github.com/googleapis/release-please)
with separate pull requests and version lines: `npm-v*` publishes the npm package,
`plugin-v*` deploys to WordPress.org. There is nothing to bump by hand — commit with
[conventional commits](https://www.conventionalcommits.org/) and merge the release
PR. Details in [.github/WORKFLOWS.md](.github/WORKFLOWS.md), commit conventions in
[CONTRIBUTING.md](CONTRIBUTING.md).

## License

GNU General Public License v3.0 or later — see [LICENSE](LICENSE).

Versions of `@palasthotel/headless` up to and including 3.0.7 were published under
the MIT license; those releases remain MIT. Everything from the next release on is
GPL-3.0-or-later.
