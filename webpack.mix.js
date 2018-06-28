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
  .sass('resources/assets/sass/app.scss', 'public/css')
  .copy('resources/assets/img', 'public/img');


mix.webpackConfig({
  resolve: {

    alias: {
      '@': path.resolve(__dirname, 'resources/assets/js'),
      'Img': path.resolve(__dirname, 'resources/assets/img'),
    }
  }
});
mix.browserSync('127.0.0.1:8000');