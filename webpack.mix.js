let mix = require('laravel-mix')
let path = require('path')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath(path.join('public', 'assets'))
mix.setResourceRoot('/assets/')
mix.sourceMaps(!mix.inProduction())
mix.disableNotifications()

if (mix.config.hmr === true) {
  mix.setResourceRoot('/')
}

/*
 |--------------------------------------------------------------------------
 | Bootstrap SASS & jQuery bundle.
 |--------------------------------------------------------------------------
 |
 | 包含 jQuery 和 Bootstrap 的捆包。
 |
 */

mix.sass('resources/sass/bootstrap.scss', path.join('public', 'assets', 'css')).
  js('resources/js/bootstrap.js', path.join('public', 'assets', 'js'))

/*
 |--------------------------------------------------------------------------
 | 后台可运行 js 捆
 |--------------------------------------------------------------------------
 |
 | 不包含 jQuery 和 Bootstrap 的 vue 捆包。
 |
 */

mix.js('resources/assets/admin', path.join('public', 'assets', 'js'))
