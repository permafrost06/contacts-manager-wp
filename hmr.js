const fs = require("fs");
const path = require("path");

const enable = process.argv[2] === "enable" ? true : false;
const disable = process.argv[2] === "disable" ? true : false;

if (!(enable || disable)) {
  console.log("Parameter not provided/is incorrect");
  return;
}

const assetFile = path.join(__dirname, "includes", "Assets.php");

let content = fs.readFileSync(assetFile, "utf-8");

const matches = content.matchAll(
  /\s+\/\*(?<magic_comment>[ \w-]+)\*\/\s+(?<next_line>.+)$/gm
);

for (const match of matches) {
  let line;

  if (match.groups.magic_comment.match(/enable/)) {
    if (enable) {
      line = match.groups.next_line.replace(/^\/\/\s*/, "");
    } else if (disable) {
      line = match.groups.next_line.replace(/(^[^/]{2}.+$)/, "// $1");
    }
  }

  if (match.groups.magic_comment.match(/disable/)) {
    if (enable) {
      line = match.groups.next_line.replace(/(^[^/]{2}.+$)/, "// $1");
    } else if (disable) {
      line = match.groups.next_line.replace(/^\/\/\s*/, "");
    }
  }

  content = content.replace(match.groups.next_line, line);
}

fs.writeFileSync(assetFile, content, "utf8");

if (enable) {
  console.log("HMR enabled successfully");
}

if (disable) {
  console.log("HMR disabled successfully");
}
