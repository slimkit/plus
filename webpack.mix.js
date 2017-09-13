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

/*
 |--------------------------------------------------------------------------
 | Bootstrap SASS & jQuery bundle.
 |--------------------------------------------------------------------------
 |
 | 包含 jQuery 和 Bootstrap 的捆包。
 |
 */

mix.sass('resources/assets/sass/bootstrap.scss', 'public/css')
   .js('resources/assets/js/bootstrap.js', 'public/js');


/*
 |--------------------------------------------------------------------------
 | 后台可运行 js 捆
 |--------------------------------------------------------------------------
 |
 | 不包含 jQuery 和 Bootstrap 的 vue 捆包。
 |
 */

mix.js('resources/assets/admin', 'public/js');
