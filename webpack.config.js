// const webpackConfig = require('./node_modules/laravel-mix/setup/webpack.config')
// const vuxLoader = require('vux-loader')

// module.exports = vuxLoader.merge(webpackConfig, {
//   options: {},
//   plugins: [{ name: 'vux-ui' }]
// })


/**
 * As our first step, we'll pull in the user's webpack.mix.js
 * file. Based on what the user requests in that file,
 * a generic config object will be constructed for us.
 */

require('./node_modules/laravel-mix/src/index');//修改
require(Mix.paths.mix());

/**
 * Just in case the user needs to hook into this point
 * in the build process, we'll make an announcement.
 */

Mix.dispatch('init', Mix);

/**
 * Now that we know which build tasks are required by the
 * user, we can dynamically create a configuration object
 * for Webpack. And that's all there is to it. Simple!
 */

let WebpackConfig = require('./node_modules/laravel-mix/src/builder/WebpackConfig');//修改

module.exports = new WebpackConfig().build();
//添加内容
const vuxLoader = require('vux-loader')
const webpackConfig = module.exports // 原来的 module.exports 代码赋值给变量 webpackConfig

module.exports = vuxLoader.merge(webpackConfig, {
  plugins: ['vux-ui'] 
})