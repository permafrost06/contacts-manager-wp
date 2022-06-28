"use strict";
const { VueLoaderPlugin } = require("vue-loader");
const path = require("path");

module.exports = {
  mode: "development",
  entry: ["./src/main.js"],
  output: {
    path: path.resolve(__dirname, "assets/js"),
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        use: "vue-loader",
      },
      {
        test: /\.css$/,
        use: ["vue-style-loader", "css-loader"],
      },
      {
        test: /\.js$/,
        use: "babel-loader",
      },
    ],
  },
  plugins: [new VueLoaderPlugin()],
};
