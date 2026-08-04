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

### Which changes get `fix:` or `feat:`

Only changes that matter to someone consuming the released artefact — a site
running the plugin, or a project depending on the npm package. `fix:` and `feat:`
decide the version *and* write the line that ends up in the changelog users read,
so the question to ask before committing is whether a consumer would care about
that line.

Everything else takes a type that releases nothing — workflows and CI, release
tooling, repository documentation, internal refactoring, and anything touching
files that are not shipped. As a rule of thumb, a change confined to files
outside `wp-plugin/public/` and `npm-package/src/` is almost never a `fix:`.

That includes hardening. Blocking direct access to a file that is not part of the
download is `chore:`, not `fix:` — nothing changes for anyone who installed the
plugin.

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

`wp-plugin/public/dist/` is generated and gitignored — the release pipeline builds it,
so there is nothing to commit and no stale asset to review. Run `npm run build` before
`bin/pack.sh`; the script refuses to pack an unbuilt payload. (`npm run wp-env:start`
builds first, so that path is covered.)

### Local WordPress Environment

```bash
cd wp-plugin
npm run wp-env:start   # builds assets + starts wp-env
npm run wp-env:stop
```

### Packaging the Plugin

```bash
cd wp-plugin
npm run pack    # stages build/headless/ and produces headless.zip at repo root
```

## Required Secrets (for maintainers)

| Secret / Variable | Used by | Purpose |
|---|---|---|
| `vars.RELEASE_BOT_APP_ID` | `release-please.yml`, `align-major-versions.yml`, `update-plugin-version.yml` | App id of the org-owned Palasthotel Release Bot. A **variable**, not a secret — the workflows also accept it from Secrets, because that is an easy place to put it by mistake |
| `secrets.RELEASE_BOT_PRIVATE_KEY` | the same three | its private key — an installation token is minted per run, which is what makes the pushed tag trigger the deploy workflows |

Two things have to be true beyond the two values existing: the app must be
**installed on this repository**, and if the private key is an organisation secret,
this repository must be in its selected-repositories list. Otherwise
`create-github-app-token` receives an empty input and the release job fails before
release-please runs.
| `SVN_USERNAME` | `wordpress-svn-release.yml` | WordPress.org username |
| `SVN_PASSWORD` | `wordpress-svn-release.yml` | WordPress.org password |
| `vars.SVN_REPO_URL` | `wordpress-svn-release.yml` | e.g. `https://plugins.svn.wordpress.org/headless` |

`GITHUB_TOKEN` and OIDC for npmjs.org are handled automatically (no stored npm token needed — configure a Trusted Publisher on npmjs.org for this repo + `npm-publish.yml`).

`npm-publish.yml` deliberately clears `NODE_AUTH_TOKEN` before publishing so npm
falls back to the OIDC exchange. **Do not add an npm token secret**: it would not be
read, and an unused publish credential is worth exactly its blast radius. That the
exchange works is visible on the registry — published versions carry a SLSA
provenance attestation, which a token publish does not produce.
