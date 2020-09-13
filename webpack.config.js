const path = require("path");

const HtmlWebpackPlugin = require("html-webpack-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");

module.exports = {
  entry: [path.join(__dirname, "./src", "index.tsx")],
  output: {
    path: path.join(__dirname, "./src", "./../public"),
    filename: "index.js"
  },
  module: {
    rules: [
      { test: /\.tsx?$/, loader: "ts-loader" },

      {
        test: /\.jsx?$/,
        exclude: /node_modules/,
        use: ["babel-loader"]
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: ["babel-loader"]
      },
      {
        test: /\.css$/,
        use: ["style-loader", "css-loader"]
      },
      {
        test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
        use: [
          {
            loader: "file-loader",
            options: {
              name: "[name].[ext]",
              outputPath: "fonts/"
            }
          }
        ]
      },
      {
        test: /\.(gif|png|jpe?g|svg)$/i,
        use: [
          "file-loader",
          {
            loader: "image-webpack-loader",
            options: {
              disable: true
            }
          }
        ]
      }
    ]
  },
  devtool: "inline-source-map",
  devServer: {
    contentBase: __dirname + "/src/",
    hot: true,
    headers: {
      "Access-Control-Allow-Origin": "*"
    },
    historyApiFallback: true,
    proxy: {
      "/react/php/komus_new/test.php": {
        target: "http://localhost:80"
      }
    }
  },
  plugins: [
    new CleanWebpackPlugin(),
    new HtmlWebpackPlugin({
      title: "Hot Module Replacement",
      template: "./src/index.html"
    })
  ],
  resolve: {
    extensions: [".js", ".ts", ".tsx", ".jsx"]
  }
};
