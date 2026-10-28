const path = require("path");
const defaultConfig = require("@wordpress/scripts/config/webpack.config.js");
const { CycloneDxWebpackPlugin } = require("@cyclonedx/webpack-plugin");

/**
 * Production builds also write a CycloneDX SBOM of what webpack bundled
 * (public/dist/cyclonedx/bom.json and bom.xml). The release workflows of
 * palasthotel/github-workflows send it to Dependency-Track and keep the cyclonedx
 * directory out of the plugin payload, so it never ships to wordpress.org.
 *
 * @param {Object} config A webpack config.
 * @return {Object} The config, with the SBOM plugin in production mode.
 */
function withSbom(config) {
  if (config.mode !== "production") {
    return config;
  }
  return {
    ...config,
    plugins: [
      ...config.plugins,
      new CycloneDxWebpackPlugin({
        specVersion: "1.6",
        outputLocation: "./cyclonedx",
        includeWellknown: false,
        reproducibleResults: true,
      }),
    ],
  };
}

module.exports = withSbom({
  ...defaultConfig,
  entry: {
    gutenberg: path.resolve(__dirname, "./src/gutenberg.ts"),
    admin: path.resolve(__dirname, "./src/admin.ts"),
  },
  output: {
    path: path.resolve(__dirname, "./public/dist"),
  },
});
