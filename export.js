const fs = require("fs");
const archiver = require("archiver");
const path = require("path");

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
});

archive.on("error", function (err) {
  throw err;
});

archive.pipe(output);

// append files from a sub-directory, putting its contents at the root of archive
archive.directory("assets");
archive.directory("includes");
archive.directory("vendor");

archive.file("contacts-manager.php");

archive.finalize();
