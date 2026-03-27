# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a dual-component project:
- **WordPress PHP plugin** (`wp-plugin/`) — extends the WP REST API with headless-specific features (block parsing, preview, revalidation, security, custom routes)
- **npm package** (`npm-package/`) — TypeScript types, Zod schemas, and request builders for frontend consumption

## Commands

### NPM package

```bash
cd npm-package
npm run build       # bundle with tsdown (outputs CJS + ESM to dist/)
npm run test        # run Jest tests
```

### WordPress plugin assets (Gutenberg)

```bash
cd wp-plugin
npm run watch           # wp-scripts start (dev mode)
npm run build           # wp-scripts build → outputs to wp-plugin/plugin/dist/
npm run wp-env:start    # build + start local WP environment
npm run wp-env:stop
npm run pack            # package plugin as plugin.zip (runs bin/pack.sh)
```

### Running a single test

```bash
cd npm-package && npx jest __tests__/schema.blocks.test.ts
```

## Architecture

### NPM package (`npm-package/`)

| Directory | Purpose |
|---|---|
| `npm-package/src/@types/` | TypeScript interfaces for WP REST responses (posts, menus, comments, etc.) |
| `npm-package/src/schema/` | Zod v4 validation schemas — mirrors `@types/` but with runtime validation |
| `npm-package/src/schema/blocks/` | Block schemas (base, text, image, list, element blocks) |
| `npm-package/src/request/` | URL/query builders — construct REST API requests with headless params |
| `npm-package/src/mapping/` | Data transformation utilities |
| `npm-package/src/util/` | `sustaining-parse.ts` (graceful Zod parsing that logs but doesn't throw), `hierarchy.ts` |

`npm-package/src/index.ts` is the barrel export. Bundled in unbundle mode (separate files preserved) with tsdown.

### WordPress plugin (`wp-plugin/`)

| Path | Purpose |
|---|---|
| `wp-plugin/plugin/classes/Plugin.php` | Singleton entry point, bootstraps all subsystems |
| `wp-plugin/plugin/classes/Extensions/` | REST API field extensions (add fields to existing WP endpoints) |
| `wp-plugin/plugin/classes/BlockPreparations/` | Transform raw block data before REST response (Image, Gallery, Paragraph, Reference, Embed) |
| `wp-plugin/plugin/classes/Routes.php` | Registers custom REST routes under `headless/v1` namespace |
| `wp-plugin/plugin/classes/Preview.php` | Preview link generation for headless frontends |
| `wp-plugin/plugin/classes/Revalidate.php` | ISR/on-demand revalidation support |
| `wp-plugin/plugin/classes/Security.php` | API key header validation, headless request detection |
| `wp-plugin/plugin/public-functions.php` | Public PHP API for other plugins to integrate |
| `wp-plugin/src/` | Gutenberg editor assets (React/TS), built with `@wordpress/scripts` → `wp-plugin/plugin/dist/` |

Custom REST routes are under `headless/v1`:
- `GET /menus` — all registered menus
- `GET /menus/{slug}` — single menu

## Release Workflow

This repo uses **release-please** for automated semver versioning with two independent components:

| Component | Tag format | Triggered by changes in |
|---|---|---|
| `npm` — `@palasthotel/headless` | `npm-v*` | `npm-package/` |
| `plugin` — WordPress plugin | `plugin-v*` | `wp-plugin/` |

**Conventional commits** determine the bump type (`fix:` → patch, `feat:` → minor, `feat!:` / `BREAKING CHANGE:` → major). Only MAJOR versions are kept in sync between the two components by convention.

### What gets updated automatically

- **npm release PR**: bumps `npm-package/package.json`, prepends to `npm-package/CHANGELOG.md`
- **plugin release PR**: bumps `wp-plugin/package.json`, `wp-plugin/plugin/headless.php` (`Version:` header), prepends to `wp-plugin/plugin/CHANGELOG.md`
- **After plugin tag is pushed**: `update-plugin-readme.yml` runs `bin/update-plugin-readme.sh` to convert the new CHANGELOG.md section to WordPress format and prepend it to `== Changelog ==` in `wp-plugin/plugin/README.txt`, then commits to main

### What triggers publishing

- Push of a `npm-v*` tag → `npm-publish.yml` runs `npm run build && npm publish` (uses `NPM_AUTH_TOKEN` secret)
- Push of a `plugin-v*` tag → `wordpress-svn-release.yml` builds and deploys to WordPress.org SVN

### Key files

- `release-please-config.json` — component definitions and path filters
- `.release-please-manifest.json` — current tracked versions (do not edit manually)
- `bin/update-plugin-readme.sh` — post-release README.txt updater
- `bin/pack.sh` — packages `wp-plugin/plugin/` as `plugin.zip`

## Key Technical Notes

- **Zod v4** — upgraded from v3 in 3.0.0; `z.record()` signature changed (now requires explicit key type)
- **tsdown** — replaced tsup; runs in unbundle mode to preserve separate module files (needed for correct DTS)
- **`sustainingParse`** — use this instead of `.parse()` for graceful degradation: logs warnings but doesn't throw on schema mismatch
- **Node >= 20, pnpm >= 9** required
- PHP namespace: `Palasthotel\WordPress\Headless\` (PSR-4, autoloaded via Composer)
