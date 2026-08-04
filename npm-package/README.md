# @palasthotel/headless

TypeScript types, [Zod](https://zod.dev/) v4 schemas and URL builders for the REST API
of the [Headless](https://wordpress.org/plugins/headless/) WordPress plugin.

The plugin extends the WordPress REST API with prepared block content, custom routes
for menus and site settings, and preview and cache-revalidation support. This package
is the client side of that contract: it builds the request URLs and validates the
responses, so a change in the CMS shows up as a parse error instead of as `undefined`
somewhere in your components.


```bash
npm install @palasthotel/headless
```

Requires Node 22 or newer. Ships both ESM and CommonJS builds with their own type
declarations.

## Request builders

```ts
import {
  getPostsWithBlocksRequest,
  getPostWithBlocksRequest,
  getMenusRequest,
  getMenuRequest,
  getSettingsRequest,
} from '@palasthotel/headless';

const baseUrl = 'https://cms.example.com';

const posts = getPostsWithBlocksRequest({ baseUrl });          // URL
const menu  = getMenuRequest({ baseUrl, slug: 'main-menu' });  // slug goes in the args
```

Every builder returns a `URL` and appends `?headless=true`, the query parameter the
plugin uses to recognise a headless request. The optional second argument renames it,
for sites that configured `HEADLESS_REST_PARAM` / `HEADLESS_REST_VALUE` differently:

```ts
getMenusRequest({ baseUrl }, { name: 'api', value: 'yes' });
```

If the site is configured with an API key
(`HEADLESS_API_KEY_HEADER_KEY` / `HEADLESS_API_KEY_HEADER_VALUE` in `wp-config.php`),
send it as a request header yourself — the builders only produce URLs.

## Schemas

```ts
import { postWithBlocksResponseSchema, settingsResponseSchema } from '@palasthotel/headless';

const post = postWithBlocksResponseSchema.parse(data);

// settingsResponseSchema is a discriminated union on front_page: "posts" | "page"
const settings = settingsResponseSchema.parse(data);
```

`.parse()` throws. When a single unexpected field should not take a page down, use
`sustainingParse`, which returns either the parsed value or a parse error you can log
and step around:

```ts
import { sustainingParse, isParseError } from '@palasthotel/headless';

const result = sustainingParse(data, postWithBlocksResponseSchema);
if (isParseError(result)) {
  console.error(result);
  return null;
}
```

## Types

```ts
import type {
  HeadlessPostResponse,
  HeadlessCommentResponse,
  MenusResponse,
  SettingsResponse,
  Block,
} from '@palasthotel/headless';
```

## Versioning

The package and the WordPress plugin are released separately but keep their major
versions in sync — `3.x` of this package expects `3.x` of the plugin. Both are
generated from the commit history of
[palasthotel/headless](https://github.com/palasthotel/headless), where the plugin
lives as well.

## License

GNU General Public License v3.0 or later — see [LICENSE](LICENSE).

Releases up to and including 3.0.7 were published under the MIT license; those
versions remain MIT.
