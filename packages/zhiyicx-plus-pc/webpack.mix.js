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
mix.setPublicPath('resources/assets/web');
mix.setResourceRoot('/assets/pc/');

let webScripts = [
    'resources/assets/web/js/axios.min.js',
    'resources/assets/web/js/lodash.min.js',
    'resources/assets/web/js/jquery.lazyload.min.js',
    'resources/assets/web/js/jquery.cookie.js',
    'resources/assets/web/js/dexie.min.js',
    'resources/assets/web/js/iconfont.js',
    'resources/assets/web/js/layer.js',
    'resources/assets/web/js/autosize.min.js',
    'resources/assets/web/js/markdown-it.min.js',
    'resources/assets/web/js/markdown-it-container.min.js',
];

let webImScripts = [
    'resources/assets/web/js/easemob/webim.config.js',
    'resources/assets/web/js/easemob/strophe-1.2.8.min.js',
    'resources/assets/web/js/easemob/websdk-1.4.13.js',
];

// More documents see: https://laravel.com/docs/master/mix
if (mix.inProduction()) {

    mix.js('resources/assets/admin', 'admin.js');

    // 合并并且压缩js插件
    mix.scripts(webScripts, 'resources/assets/web/global.min.js');

    // 合并并且压缩环信js
    mix.scripts(webImScripts, 'resources/assets/web/easemob.min.js');
} else {
    mix.setPublicPath('../../public/assets/pc');
    if (mix.config.hmr === true) {
        mix.setResourceRoot('/');
    }

    mix.copy('resources/assets/web', '../../public/assets/pc');
    mix.scripts(webScripts, '../../public/assets/pc/global.min.js');
    mix.scripts(webImScripts, '../../public/assets/pc/easemob.min.js');

    mix.js('resources/assets/admin', '../../public/assets/pc/admin.js');
}
