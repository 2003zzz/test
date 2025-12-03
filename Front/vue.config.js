const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const isProd = process.env.NODE_ENV === "production";
const path = require('path');
const vueSrc = "./src";

module.exports = {
  publicPath: isProd ? process.env.VUE_APP_DIR : '/',
  transpileDependencies: isProd ? ['vuetify'] : [],
  runtimeCompiler: true,
  configureWebpack: {
    optimization: {
      minimizer: isProd ? [
        new UglifyJsPlugin({
          uglifyOptions: {
            output: {
              comments: false
            },
            compress: {
              drop_console: true
            },
          }
        })
      ] : []
    },
    resolve: {
      alias: {
        "@": path.resolve(__dirname, vueSrc)
      },
      extensions: ['.js', '.vue', '.json', '.png']
    }
  },
  productionSourceMap: false,
  assetsDir: "./",
  devServer: {
    proxy: 'https://awebdevserv/ptt05/',
    public: 'http://localhost:8080',
    disableHostCheck: true,
    hot: true
  },
}
