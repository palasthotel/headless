# CI/CD Workflows

This repository uses six GitHub Actions workflows. Two components are versioned independently via [release-please](https://github.com/googleapis/release-please):

| Component | Path | Tag format |
|---|---|---|
| npm package | `npm-package/` | `npm-v*` |
| WordPress plugin | `wp-plugin/` | `plugin-v*` |

Bump type is determined by conventional commits: `fix:` → patch, `feat:` → minor, `feat!:` / `BREAKING CHANGE:` → major.

---

## Overview

```
Push to main
    │
    ├──▶ [release-please.yml]
    │        Creates / updates release PRs
    │
    │    On plugin release PR (opened / synchronize)
    ├──▶ [update-plugin-version.yml]
    │        Updates headless.php Version + README.txt Stable tag
    │
    │    On PR to main
    └──▶ [pr.yml]
             Build + test both components


Merge release PR  →  release-please pushes tag
    │
    ├── npm-v*  ──▶ [npm-publish.yml]
    │                   Publish to npmjs.org + GitHub Packages
    │
    ├── plugin-v*  ──▶ [wordpress-svn-release.yml]
    │                       Build + deploy to WordPress.org SVN
    │                       Upload plugin.zip to GitHub Release
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
        │       npm ci → npm run build → npm test
        │
        └──▶ wp-plugin job
                npm ci → npm run build
```

---

### `release-please.yml` — Release PR Management

**Trigger:** Push to `main`
**Token:** `RELEASE_PLEASE_TOKEN` (PAT — required so downstream workflows trigger on the resulting push)

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

**Trigger:** `pull_request_target` on `main` — types: `opened`, `synchronize`
**Condition:** Only runs for release-please plugin PRs (`release-please--*--plugin`)

Keeps `headless.php` and `README.txt` in sync with the version in `wp-plugin/package.json` before the PR is merged and the tag is created.

```
Plugin release PR opened / updated
              │
              ▼
    bash bin/update-plugin-version.sh
              │
              ├── reads version from wp-plugin/package.json
              ├── updates "Version:" header in wp-plugin/public/headless.php
              ├── updates "Stable tag:" in wp-plugin/public/README.txt
              └── prepends new = x.y.z = section to README.txt changelog
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

```
Tag: plugin-v3.x.x
      │
      ├── strip prefix → VERSION=3.x.x
      │
      ├── checkout + setup PHP 8.2 + Node 24
      │
      ├── npm ci + npm run build  (wp-plugin/)
      │       compiles Gutenberg assets → wp-plugin/public/dist/
      │
      ├── npm run pack
      │       rsync wp-plugin/public/ → build/plugin/
      │       composer install --no-dev
      │       zip → plugin.zip
      │
      ├──▶ Upload plugin.zip to GitHub Release
      │       (softprops/action-gh-release, continue-on-error)
      │
      ├── svn checkout  $SVN_REPO_URL  →  ./svn/
      │
      └── SVN commit
              rm trunk/*  +  rm tags/$VERSION
              cp wp-plugin/public/* → trunk/  +  tags/$VERSION/
              svn add --force .
              svn rm deleted files
              svn commit "Release version $VERSION"
```

**Required secrets / vars:**
- `SVN_USERNAME` — WordPress.org username
- `SVN_PASSWORD` — WordPress.org password
- `vars.SVN_REPO_URL` — e.g. `https://plugins.svn.wordpress.org/headless`

---

### `align-major-versions.yml` — Major Version Alignment

**Trigger:** Push of any `npm-v*` or `plugin-v*` tag
**Token:** `RELEASE_PLEASE_TOKEN` (PAT — required for git push + gh pr create)

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
