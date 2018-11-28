const fs = require("fs");
const { resolve } = require("path");

/**
 * resolve real path.
 * @param  {string} relativePath
 * @return {string}
 */
const resolveRealPath = relativePath => resolve(
  fs.realpathSync(process.cwd()),
  relativePath
);

//
//++++++++++++++++++++++++++++++++++++++++++
//+  Defined target and source filename.   +
//++++++++++++++++++++++++++++++++++++++++++
//
const targetFilename = resolveRealPath('dist/404.html');
const sourceFilename = resolveRealPath("dist/index.html");

// If source filename not found,
// Throw a system error.
if (!fs.existsSync(sourceFilename)) {
  throw new Error(`The file "${sourceFilename}" not found.`);
}

// Write source to target contents.
fs.writeFileSync(
  targetFilename,
  fs.readFileSync(sourceFilename)
);
