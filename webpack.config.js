const webpackConfig = require('./node_modules/laravel-mix/setup/webpack.config')
const vuxLoader = require('vux-loader')

module.exports = vuxLoader.merge(webpackConfig, {
  options: {},
  plugins: [{ name: 'vux-ui' }]
})