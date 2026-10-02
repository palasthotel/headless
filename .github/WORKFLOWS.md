# CI/CD Workflows

This repository uses six GitHub Actions workflows. Two components are versioned independently via [release-please](https://github.com/googleapis/release-please):

| Component | Path | Tag format |
|---|---|---|
| npm package | `npm-package/` | `npm-v*` |
| WordPress plugin | `wp-plugin/` | `plugin-v*` |

Bump type is determined by conventional commits: `fix:` → patch, `feat:` → minor, `feat!:` / `BREAKING CHANGE:` → major.

The plugin side uses the shared WordPress plugin workflows and scripts of
[palasthotel/github-workflows](https://github.com/palasthotel/github-workflows) at
`v1` - every input and the reasons behind the deploy steps are described in its
[docs/wp-plugin.md](https://github.com/palasthotel/github-workflows/blob/main/docs/wp-plugin.md).
What is specific to this repository:

| | |
|---|---|
| wordpress.org slug | `headless` |
| plugin project (`root`) | `wp-plugin/` - payload `wp-plugin/public/`, version file `wp-plugin/package.json` |
| build step | `npm ci && npm run build` in `wp-plugin/` (`public/dist/` is not in the repository) |
| plugin tags | `plugin-v<version>`, not `v<version>` - which is why the deploy is not the shared reusable workflow (see below) |
| SVN | the readme is `README.txt`, as it has always been in the SVN trunk |

---

## Overview

```
Push to main
    │
    ├──▶ [release-please.yml]
    │        Creates / updates release PRs
    │
    │    On a release PR (opened / synchronize)
    ├──▶ [update-plugin-version.yml]
    │        Updates headless.php Version + README.txt Stable tag and changelog
    │
    │    On PR to main
    └──▶ [pr.yml]
             npm package: lint + build + test
             plugin: php -l, lint + build, pack + payload checks, version carriers


Merge release PR  →  release-please pushes tag
    │
    ├── npm-v*  ──▶ [npm-publish.yml]
    │                   Publish to npmjs.org + GitHub Packages
    │
    ├── plugin-v*  ──▶ [wordpress-svn-release.yml]
    │                       Build + deploy to WordPress.org SVN
    │                       Upload headless.zip to GitHub Release
    │
    └── npm-v* or plugin-v*  ──▶ [align-major-versions.yml]
                                      Check if major versions match
                                      If not → open alignment PR
```

---

## Workflows

### `pr.yml` — PR Checks

**Trigger:** Any pull request targeting `main`

Runs both jobs in parallel on every PR — regardless of which files changed.

```
PR opened / updated
        │
        ├──▶ npm-package job
        │       npm ci → npm run lint → npm run build → npm test
        │
        └──▶ wp-plugin job  (shared wp-plugin-pr.yml, root: wp-plugin)
                php -l on PHP 8.0, 8.2, 8.3, 8.4
                npm ci → npm run lint (tsc) → npm run build → pack
                payload: the enqueued dist/ files and vendor/autoload.php are there,
                         no composer.json, package.json, symlinks or "Headless - DEV"
                version carriers agree (not on release PRs)
```

---

### `release-please.yml` — Release PR Management

**Trigger:** Push to `main` — calls the shared `wp-plugin-release-please.yml`
**Token:** an installation token of the Palasthotel Release Bot app, minted per run from `vars.RELEASE_BOT_APP_ID` + `secrets.RELEASE_BOT_PRIVATE_KEY` — required so downstream workflows trigger on the resulting push, which they would not with `GITHUB_TOKEN`. The app id has to be a **Variable**: a reusable workflow's inputs cannot read Secrets

Reads conventional commits since the last tag and maintains two separate release PRs. On merge, creates the tag and a GitHub Release.

```
Push to main
      │
      ▼
  release-please
      │
      ├── commits in npm-package/?
      │       └──▶ opens / updates PR  "chore(main): release npm v3.x.x"
      │                 bumps npm-package/package.json
      │                 updates npm-package/CHANGELOG.md
      │
      └── commits in wp-plugin/?
              └──▶ opens / updates PR  "chore(main): release plugin v3.x.x"
                        bumps wp-plugin/package.json
                        updates wp-plugin/CHANGELOG.md

  PR merged
      └──▶ pushes tag (npm-v3.x.x  or  plugin-v3.x.x)
           creates GitHub Release
```

> **Note:** release-please overwrites the release PR branch on every run — it does not rebase. If you push a commit to the PR branch (e.g. via `update-plugin-version.yml`) and main gets another commit, release-please will re-create the branch and your commit will be re-applied by the workflow.

---

### `update-plugin-version.yml` — Plugin Version Files

**Trigger:** `pull_request` on `main` — types: `opened`, `synchronize` — calls the
shared `wp-plugin-sync-version.yml` with `root: wp-plugin`
**Condition:** only release-please PRs whose head branch lives in this repository - the
shared workflow checks both. On the npm release PR the plugin version is unchanged and
the readme already has its entry, so it commits nothing.
**Token:** the Release Bot installation token, so the commit it pushes re-triggers the
PR checks; a push made with `GITHUB_TOKEN` triggers nothing, which would leave the
release PR without check results for the commit that actually gets released.

Keeps `headless.php` and `README.txt` in sync with the version in `wp-plugin/package.json` before the PR is merged and the tag is created.

```
Release PR opened / updated
              │
              ▼
    sync-version.sh  (palasthotel/github-workflows@v1)
              │
              ├── reads version from wp-plugin/package.json
              ├── updates "Version:" header in wp-plugin/public/headless.php
              ├── updates "Stable tag:" in wp-plugin/public/README.txt
              └── prepends new = x.y.z = section from wp-plugin/CHANGELOG.md
              │
              ▼
    git commit + push → back onto the release PR branch
```

---

### `npm-publish.yml` — Publish npm Package

**Trigger:** Push of a `npm-v*` tag
**Auth:** OIDC Trusted Publisher (no stored token needed for npmjs.org)

```
Tag: npm-v3.x.x
      │
      ▼
  checkout + setup Node 24
      │
      ├── npm ci  (npm-package/)
      ├── npm audit --audit-level=critical
      ├── npm run build
      │
      ├──▶ Publish to npmjs.org
      │       OIDC token exchange (Trusted Publisher configured on npmjs.org)
      │       npm publish
      │
      └──▶ Publish to GitHub Packages
              setup-node (registry: npm.pkg.github.com)
              npm publish --access public
              NODE_AUTH_TOKEN = GITHUB_TOKEN
```

**Required secrets / vars:**
- npmjs.org Trusted Publisher configured for this repo + `npm-publish.yml`
- `GITHUB_TOKEN` (automatic)

---

### `wordpress-svn-release.yml` — Deploy to WordPress.org

**Trigger:** Push of a `plugin-v*` tag

The same steps as the shared `wp-plugin-svn-deploy.yml`, with its scripts checked out
from `palasthotel/github-workflows@v1`, but kept in this repository: the shared
workflow expects `v<version>` tags. Given a version instead, it would attach the zip to
a release `v<version>`, and `softprops/action-gh-release` creates a missing release
together with its tag. Switch to the shared workflow once it takes a tag prefix.

```
Tag: plugin-v3.x.x
      │
      ├── strip prefix → VERSION=3.x.x
      ├── check-version.sh: package.json, headless.php and README.txt say VERSION
      │
      ├── npm ci + npm run build  (wp-plugin/)
      │       compiles Gutenberg assets → wp-plugin/public/dist/
      │
      ├── pack.sh  (SLUG=headless ROOT=wp-plugin)
      │       rsync -rL wp-plugin/public/ → wp-plugin/build/headless/
      │       composer install --no-dev + dump-autoload --optimize
      │       drop composer.json/composer.lock from the payload
      │       zip → wp-plugin/headless.zip
      │
      ├──▶ Upload headless.zip to the GitHub Release plugin-v3.x.x
      │       (softprops/action-gh-release, continue-on-error)
      │
      ├── svn checkout https://plugins.svn.wordpress.org/headless/ → ./svn/
      │
      └── svn-prepare.sh + svn commit
              trunk/ and tags/$VERSION/ ← wp-plugin/build/headless/ (rsync -rL)
              svn propdel svn:special, svn add / svn rm
              svn commit "Release version $VERSION"
```

The SVN payload comes from `wp-plugin/build/headless/`, the same directory that was
zipped, so the download on wordpress.org and the GitHub release asset are identical.
`assets/` is not touched — the plugin-page media lives only in SVN, not in this
repository.

**Retry:** the workflow also accepts `workflow_dispatch` with a version input (e.g.
`3.0.4`), for when a tag-triggered run failed. A tag push reads the workflow file as
it was at the tagged commit, so re-running the tag event replays the old file;
dispatch from a branch runs the current one.

**Required secrets:**
- `SVN_USERNAME` — WordPress.org username with commit rights, usually `palasthotel`
- `SVN_PASSWORD` — WordPress.org password

---

### `align-major-versions.yml` — Major Version Alignment

**Trigger:** Push of any `npm-v*` or `plugin-v*` tag
**Token:** the same Release Bot installation token — required for `git push` + `gh pr create`, and so the PR checks run on the branch it pushes

After every release, checks whether both components share the same major version. If they diverge, automatically opens a PR with a `BREAKING CHANGE:` commit in the lagging component's directory — which causes release-please to open a major release PR for it on merge.

```
Tag pushed (npm-v* or plugin-v*)
        │
        ▼
  read .release-please-manifest.json
        │
        ├── npm major == plugin major?  →  done, nothing to do
        │
        └── major mismatch detected
                │
                ├── determine which component is lagging
                ├── create branch: chore/align-{component}-major-v{X}
                ├── touch {component}/CHANGELOG.md  (attributes commit to component)
                ├── commit with BREAKING CHANGE footer
                ├── git push
                └── gh pr create  (skipped if PR already exists)

  PR merged
        └──▶ release-please sees BREAKING CHANGE in component path
                  └──▶ opens major release PR  (v{X}.0.0)
```

> Duplicate protection: if an alignment PR for that branch already exists, the workflow exits early.
