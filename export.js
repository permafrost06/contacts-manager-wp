const fs = require("fs");
const archiver = require("archiver");
const path = require("path");

const assetFile = path.join(__dirname, "includes", "Assets.php");

fs.copyFileSync(assetFile, "Assets_copy.php");

const content = fs.readFileSync(assetFile, "utf-8");

const removed = content.replace(
  /\/\* remove-next-line-in-production \*\/\s+.*$\s+\/\* uncomment-next-line-in-production \*\/\s+\/\/\s+/gm,
  ""
);

const change_version = removed.replace(
  /filemtime.+$/gm,
  "CONTACTS_MANAGER_VERSION,"
);

fs.writeFileSync(assetFile, change_version, "utf8");

const dir = path.resolve(path.join(__dirname, "dist"));

if (!fs.existsSync(dir)) {
  fs.mkdirSync(dir);
}

const output = fs.createWriteStream("dist/contacts-manager.zip");
const archive = archiver("zip");

output.on("close", function () {
  console.log(archive.pointer() + " total bytes");
  console.log(
    "archiver has been finalized and the output file descriptor has closed."
  );

  fs.copyFileSync("Assets_copy.php", assetFile);
  fs.unlinkSync("Assets_copy.php");
});

archive.on("error", function (err) {
  throw err;
});

archive.pipe(output);

archive.directory("assets");
archive.directory("includes");
archive.directory("vendor");

archive.file("contacts-manager.php");

archive.finalize();
