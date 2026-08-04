#!/bin/sh
# Stages wp-plugin/public/ in build/headless/ - the exact payload deployed to
# WordPress.org - and zips it to headless.zip in the project root.
#
# The build directory is left in place on purpose: the release workflow rsyncs
# from it into the SVN checkout, so the zip and the SVN trunk are byte-identical.
#
# public/dist/ is not in the repository - run "npm run build" in wp-plugin/ first.
set -e

PLUGIN_SLUG="headless"
SCRIPT_DIR=$(cd "$(dirname "$0")" && pwd)
PROJECT_PATH=$(cd "$SCRIPT_DIR/.." && pwd)
BUILD_PATH="$PROJECT_PATH/build"
DEST_PATH="$BUILD_PATH/$PLUGIN_SLUG"

if [ ! -f "$PROJECT_PATH/wp-plugin/public/dist/gutenberg.js" ]; then
  echo "wp-plugin/public/dist/ is missing or incomplete - run \"npm run build\" in wp-plugin/ first." >&2
  exit 1
fi

echo "Generating build directory..."
rm -rf "$BUILD_PATH"
mkdir -p "$DEST_PATH"

echo "Syncing files..."
rsync -rL "$PROJECT_PATH/wp-plugin/public/" "$DEST_PATH/"

echo "Installing the production autoloader..."
cd "$DEST_PATH"
composer install --no-dev --no-interaction --quiet
composer dump-autoload --no-dev --optimize --quiet
rm -f composer.json composer.lock
cd "$PROJECT_PATH"

echo "Generating zip file..."
cd "$BUILD_PATH" || exit 1
rm -f "${PLUGIN_SLUG}.zip"
zip -q -r "${PLUGIN_SLUG}.zip" "$PLUGIN_SLUG/"
mv "${PLUGIN_SLUG}.zip" "$PROJECT_PATH/"

cd "$PROJECT_PATH" || exit 1
echo "${PLUGIN_SLUG}.zip file generated!"
echo "Build done!"
