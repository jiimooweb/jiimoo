let mix = require('laravel-mix');



/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/backend.js', 'public/js')
  .extract(['vue', "vue-router", "axios", 'element-ui'])
  .copy('resources/assets/img', 'public/img');


mix.webpackConfig({
  resolve: {

    alias: {
      '@': path.resolve(__dirname, 'resources/assets/js'),
      'img': path.resolve(__dirname, 'resources/assets/img'),
    }
  }
});
