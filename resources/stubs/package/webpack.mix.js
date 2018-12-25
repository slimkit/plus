let mix = require('laravel-mix');
let fs = require('fs');

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

mix.disableNotifications();
mix.sourceMaps(!mix.inProduction());
mix.setResourceRoot('/assets/{name}/');

// More documents see: https://laravel.com/docs/master/mix
if (mix.inProduction()) {
  mix.setPublicPath('assets');
  mix.js('resources/assets/main.js', 'assets/app.js');
  
// Dev build.
} else {
  mix.setPublicPath('../../public/assets/{name}/');
  if (mix.config.hmr === true) {
    mix.setResourceRoot('http://127.0.0.1:8080/');
  }
  mix.js('resources/assets/main.js', '../../public/assets/{name}/app.js');
}

