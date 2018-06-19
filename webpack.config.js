const { VueLoaderPlugin } = require('vue-loader');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const path = require('path')

module.exports = {
  mode: 'development',
  entry: [
    './assets/js/main.js'
  ],
  output: {
    path: path.resolve(__dirname, "dist"),
    filename: 'js/build.js'
  },
  watch: true,
  watchOptions: {
    
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        use: 'vue-loader'
      },
      {
        test: /\.js$/,
        use: 'babel-loader'
      },
      {
        test: /\.scss/,
        use: ExtractTextPlugin.extract({
            use: [
              {loader: 'css-loader', options: { importLoaders: 1} }, 
              'postcss-loader',
              'sass-loader',
            ]
        })
      }
    ]
  },
  plugins: [
    new VueLoaderPlugin(),
    new ExtractTextPlugin(
      {filename: 'css/style.css'}
    ),
  ],
  resolve: {
      alias: {
          'vue$': 'vue/dist/vue.esm.js'
      }
  }
}