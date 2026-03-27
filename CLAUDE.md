# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a dual-component project:
- **WordPress PHP plugin** (`/plugin`) — extends the WP REST API with headless-specific features (block parsing, preview, revalidation, security, custom routes)
- **npm package** (`@palasthotel/headless`) — TypeScript types, Zod schemas, and request builders for frontend consumption

## Commands

### NPM package

```bash
npm run build       # bundle with tsdown (outputs CJS + ESM to dist/)
npm run test        # run Jest tests
npm run pack        # package for release (runs bin/pack.sh)
```

### Gutenberg editor assets

```bash
cd plugin-assets
npm run watch       # wp-scripts start (dev mode)
npm run build       # wp-scripts build (production)
```

### WordPress dev environment

```bash
npm run wp-env start   # start local WP environment
npm run wp-env stop
```

### Running a single test

```bash
npx jest __tests__/schema.blocks.test.ts
```

## Architecture

### NPM package (`src/`)

| Directory | Purpose |
|---|---|
| `src/@types/` | TypeScript interfaces for WP REST responses (posts, menus, comments, etc.) |
| `src/schema/` | Zod v4 validation schemas — mirrors `@types/` but with runtime validation |
| `src/schema/blocks/` | Block schemas (base, text, image, list, element blocks) |
| `src/request/` | URL/query builders — construct REST API requests with headless params |
| `src/mapping/` | Data transformation utilities |
| `src/util/` | `sustaining-parse.ts` (graceful Zod parsing that logs but doesn't throw), `hierarchy.ts` |

`src/index.ts` is the barrel export. Bundled in unbundle mode (separate files preserved) with tsdown.

### WordPress plugin (`plugin/`)

| Path | Purpose |
|---|---|
| `plugin/classes/Plugin.php` | Singleton entry point, bootstraps all subsystems |
| `plugin/classes/Extensions/` | REST API field extensions (add fields to existing WP endpoints) |
| `plugin/classes/BlockPreparations/` | Transform raw block data before REST response (Image, Gallery, Paragraph, Reference, Embed) |
| `plugin/classes/Routes.php` | Registers custom REST routes under `headless/v1` namespace |
| `plugin/classes/Preview.php` | Preview link generation for headless frontends |
| `plugin/classes/Revalidate.php` | ISR/on-demand revalidation support |
| `plugin/classes/Security.php` | API key header validation, headless request detection |
| `plugin/public-functions.php` | Public PHP API for other plugins to integrate |

Custom REST routes are under `headless/v1`:
- `GET /menus` — all registered menus
- `GET /menus/{slug}` — single menu

### Gutenberg assets (`plugin-assets/`)

React/TypeScript Gutenberg editor plugins built with `@wordpress/scripts`. Output goes to `plugin/dist/`.

## Release Workflow

This repo uses **release-please** for automated semver versioning with two independent components:

| Component | Tag format | Triggered by changes in |
|---|---|---|
| `npm` — `@palasthotel/headless` | `npm-v*` | `src/`, `package.json`, `tsconfig.json`, `tsdown.config.ts`, `jest.config.ts`, `__tests__/` |
| `plugin` — WordPress plugin | `plugin-v*` | `plugin/`, `plugin-assets/` |

**Conventional commits** determine the bump type (`fix:` → patch, `feat:` → minor, `feat!:` / `BREAKING CHANGE:` → major). Only MAJOR versions are kept in sync between the two components by convention.

### What gets updated automatically

- **npm release PR**: bumps `package.json`, prepends to `CHANGELOG.md`
- **plugin release PR**: bumps `plugin-assets/package.json`, `plugin/headless.php` (`Version:` header), `plugin/README.txt` (`Stable tag:`), prepends to `plugin/CHANGELOG.md`
- **After plugin tag is pushed**: `update-plugin-readme.yml` runs `bin/update-plugin-readme.sh` to convert the new CHANGELOG.md section to WordPress format and prepend it to `== Changelog ==` in `plugin/README.txt`, then commits to main

### What triggers publishing

- Push of a `npm-v*` tag → `npm-publish.yml` runs `npm run build && npm publish` (uses `NPM_AUTH_TOKEN` secret)
- WordPress.org SVN publishing is a separate manual step

### Key files

- `release-please-config.json` — component definitions and path filters
- `.release-please-manifest.json` — current tracked versions (do not edit manually)
- `bin/update-plugin-readme.sh` — post-release README.txt updater

## Key Technical Notes

- **Zod v4** — upgraded from v3 in 3.0.0; `z.record()` signature changed (now requires explicit key type)
- **tsdown** — replaced tsup; runs in unbundle mode to preserve separate module files (needed for correct DTS)
- **`sustainingParse`** — use this instead of `.parse()` for graceful degradation: logs warnings but doesn't throw on schema mismatch
- **Node >= 20, pnpm >= 9** required
- PHP namespace: `Palasthotel\WordPress\Headless\` (PSR-4, autoloaded via Composer)
