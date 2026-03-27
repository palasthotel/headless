#!/usr/bin/env bash
# Updates plugin/README.txt after a plugin release:
# - Bumps "Stable tag:" to the current version from plugin-assets/package.json
# - Prepends the new version's changelog section (converted from plugin/CHANGELOG.md) to == Changelog ==
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

PACKAGE_JSON="$ROOT_DIR/plugin-assets/package.json"
CHANGELOG="$ROOT_DIR/plugin/CHANGELOG.md"
README="$ROOT_DIR/plugin/README.txt"

VERSION=$(node -e "process.stdout.write(require('$PACKAGE_JSON').version)")
echo "Updating README.txt for plugin version $VERSION"

# ── 1. Update Stable tag ────────────────────────────────────────────────────
sed -i '' "s/^Stable tag: .*/Stable tag: $VERSION/" "$README"

# ── 2. Extract and convert the changelog section for this version ────────────
# CHANGELOG.md section starts with "## [X.Y.Z]" and ends before the next "## ["
SECTION=$(awk "
  /^## \[$VERSION\]/ { found=1; next }
  found && /^## \[/ { exit }
  found { print }
" "$CHANGELOG")

if [[ -z "$SECTION" ]]; then
  echo "No CHANGELOG.md entry found for $VERSION — skipping changelog update"
  exit 0
fi

# Convert Markdown to WordPress readme format:
#   - Drop empty lines at start/end
#   - Drop "### Category" headings
#   - Strip Markdown links from "* item ([#123](...)) " → "* item"
WP_LINES=""
while IFS= read -r line; do
  # Skip category headings
  [[ "$line" =~ ^###  ]] && continue
  # Strip inline Markdown links: [text](url) → text
  line=$(echo "$line" | sed 's/\[([^]]*)\]([^)]*)/\1/g; s/\[\([^]]*\)\]([^)]*)/\1/g')
  WP_LINES+="$line"$'\n'
done <<< "$SECTION"

# Trim leading/trailing blank lines
WP_LINES=$(echo "$WP_LINES" | sed '/./,$!d' | sed -e :a -e '/^\n*$/{$d;N;ba}')

WP_ENTRY="= $VERSION ="$'\n'"$WP_LINES"

# ── 3. Prepend entry after "== Changelog ==" ─────────────────────────────────
TMPFILE=$(mktemp)
awk -v entry="$WP_ENTRY" '
  /^== Changelog ==/ {
    print
    print ""
    print entry
    print ""
    injected = 1
    next
  }
  { print }
' "$README" > "$TMPFILE"
mv "$TMPFILE" "$README"

echo "README.txt updated successfully"
