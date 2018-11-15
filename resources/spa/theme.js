const path = require("path");
const themeFile = path.resolve(
  __dirname,
  process.env.VUE_APP_THEME || "src/console/theme.js"
);

module.exports = require(themeFile);
