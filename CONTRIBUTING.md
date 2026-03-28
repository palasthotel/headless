# Contributing

## Repository Structure

This monorepo contains two independently versioned components:

| Component | Path | Published to |
|---|---|---|
| WordPress Plugin | `wp-plugin/` | WordPress.org SVN |
| npm Package | `npm-package/` | npmjs.org + GitHub Packages |

Changes to `wp-plugin/` only affect the plugin release. Changes to `npm-package/` only affect the npm release. Commits touching both will appear in both changelogs.

## Conventional Commits

This project uses [Conventional Commits](https://www.conventionalcommits.org/) — commit messages determine the version bump automatically via release-please.

| Prefix | Bump | Example |
|---|---|---|
| `fix:` | patch | `fix: correct menu slug encoding` |
| `feat:` | minor | `feat: add settings endpoint` |
| `feat!:` or `BREAKING CHANGE:` in footer | **major** | `feat!: drop PHP 7 support` |
| `chore:`, `refactor:`, `docs:` | none | — |

To scope a commit to one component, use the scope in parentheses — this is optional but helps with readability:

```
feat(plugin): add revalidation by tag
fix(npm): correct block schema for core/image
```

> release-please attributes a commit to a component based on which files were changed, not the scope in the message.

## Versioning & Major Version Alignment

The two components are versioned **independently** — a breaking change in the plugin does not automatically bump the npm package, and vice versa.

**Convention: major versions must stay in sync.**

If you introduce a breaking change in one component that requires a corresponding update in the other, make sure both get a `BREAKING CHANGE:` commit in the same push. This ensures release-please opens major release PRs for both components at the same time.

> If you forget this, the `align-major-versions.yml` workflow will catch it: whenever a `x.0.0` tag is pushed and the other component's major is lower, it automatically opens a PR that triggers release-please to bump the lagging component to the same major. Check `.release-please-manifest.json` to see the current tracked versions of both components.

## Development Workflow

```
feature branch  →  PR to main  →  merge
                        │
                  [pr.yml] runs:
                  - npm-package: build + test
                  - wp-plugin: build
```

After merging, release-please opens or updates a release PR for the affected component(s). When the release PR is merged, the tag is created and publishing runs automatically.

**Never manually edit `.release-please-manifest.json`** — release-please manages this file.

## Local Setup

### npm Package

```bash
cd npm-package
npm install
npm run build
npm test
```

### WordPress Plugin Assets (Gutenberg)

```bash
cd wp-plugin
npm install
npm run watch       # dev mode with file watching
npm run build       # production build → wp-plugin/public/dist/
```

### Local WordPress Environment

```bash
cd wp-plugin
npm run wp-env:start   # builds assets + starts wp-env
npm run wp-env:stop
```

### Packaging the Plugin

```bash
cd wp-plugin
npm run pack    # produces plugin.zip at repo root
```

## Required Secrets (for maintainers)

| Secret / Variable | Used by | Purpose |
|---|---|---|
| `RELEASE_PLEASE_TOKEN` | `release-please.yml` | PAT with `contents` + `pull-requests` write — required so release-please's pushes trigger downstream workflows |
| `SVN_USERNAME` | `wordpress-svn-release.yml` | WordPress.org username |
| `SVN_PASSWORD` | `wordpress-svn-release.yml` | WordPress.org password |
| `vars.SVN_REPO_URL` | `wordpress-svn-release.yml` | e.g. `https://plugins.svn.wordpress.org/headless` |

`GITHUB_TOKEN` and OIDC for npmjs.org are handled automatically (no stored npm token needed — configure a Trusted Publisher on npmjs.org for this repo + `npm-publish.yml`).
