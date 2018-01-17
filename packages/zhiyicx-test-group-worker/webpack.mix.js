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

mix.sourceMaps(! mix.inProduction());

// More documents see: https://laravel.com/docs/master/mix
if (mix.inProduction()) {
  mix.setPublicPath('assets');
  mix.setResourceRoot('/assets/test-group-worker');
  mix.js('resources/assets/main.js', 'assets/app.js');

// Dev build.
} else {
  mix.setPublicPath('../../public/assets/test-group-worker');
  mix.setResourceRoot('//localhost:8080/');
  mix.js('resources/assets/main.js', '../../public/assets/test-group-worker/app.js');
}
