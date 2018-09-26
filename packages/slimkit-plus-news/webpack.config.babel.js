import path from 'path';
import webpack from 'webpack';
import autoprefixer from 'autoprefixer';

import ExtractTextPlugin from 'extract-text-webpack-plugin';
import OptimizeCSSPlugin from 'optimize-css-assets-webpack-plugin';
import WebpackLaravelMixManifest from 'webpack-laravel-mix-manifest';

import CleanWebpackPlugin from 'clean-webpack-plugin';

// 环境变量获取
const NODE_ENV = process.env.NODE_ENV || 'development';

// 是否是正式环境编译
const isProd = NODE_ENV === 'production';

// 各项资源地址定义
const Root = path.join(__dirname, 'public');
const BuildRoot = path.resolve(__dirname, 'resource/assets/');

// 入口配置
const entry = {
    // index: path.join(Root, 'index.js')
    index: path.join(Root, 'admin/index.js')
};

// cssLoaders
function cssLoaders(options = {}) {
    const cssLoader = {
        loader: 'css-loader',
        options: {
            minimize: isProd,
            sourceMap: options.sourceMap
        }
    };

    // generate loader string to be used with extract text plugin
    function generateLoaders(loader, loaderOptions) {
        let loaders = [cssLoader];
        if (loader) {
            loaders.push({
                loader: loader + '-loader',
                options: Object.assign({}, loaderOptions, {
                    sourceMap: options.sourceMap
                })
            });
        }

        return ExtractTextPlugin.extract({
            use: loaders,
            fallback: 'vue-style-loader'
        });
    }

    // https://vue-loader.vuejs.org/en/configurations/extract-css.html
    return {
        css: generateLoaders(),
        postcss: generateLoaders(),
        sass: generateLoaders('sass', {
            indentedSyntax: true
        }),
        scss: generateLoaders('sass'),
    };
}

// styleloaders
function styleLoaders(options = {}) {
    let output = [];
    const loaders = cssLoaders(options);

    for (let extension in loaders) {
        let loader = loaders[extension];
        output.push({
            test: new RegExp('\\.' + extension + '$'),
            use: loader
        });
    }

    return output;
}

// 环境插件～不同环境启用的不同插件.
const plugins = isProd ? [
    new webpack.optimize.UglifyJsPlugin({
        compress: {
            warnings: false
        },
        sourceMap: false
    }),
    // Compress extracted CSS. We are using this plugin so that possible
    // duplicated CSS from different components can be deduped.
    new OptimizeCSSPlugin({
        cssProcessorOptions: {
            safe: true
        }
    })
] : [
    // https://github.com/glenjamin/webpack-hot-middleware#installation--usage
    new webpack.NoEmitOnErrorsPlugin(),
];
const webpackConfig = {
    devtool: isProd ? false : 'source-map',
    entry: entry,
    output: {
        path: BuildRoot,
        publicPath: '../',
        filename: isProd ? 'js/[name].js' : 'js/[name].js',
    },
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        modules: [
            Root,
            path.join(__dirname, 'node_modules'),
        ],
        alias: {
            'jquery': `jquery/dist/jquery.${isProd ? 'min.js' : 'js'}`,
            'vue$': `vue/dist/vue.common.js`,
            'bootstrap-sass$': `bootstrap-sass/assets/javascripts/bootstrap.${isProd ? 'min.js' : 'js'}`
        }
    },
    module: {
        rules: [
            ...styleLoaders({
                sourceMap: !isProd
            }),
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    loaders: cssLoaders({
                        sourceMap: !isProd
                    })
                }
            },
            {
                test: /\.js$/,
                loader: 'babel-loader',
                include: [Root]
            },
            {
                test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
                loader: 'url-loader',
                query: {
                    limit: 10000,
                    name: isProd ? 'images/[name].[ext]' : `images/[name].[ext]`
                }
            },
            {
                test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
                loader: 'url-loader',
                query: {
                    limit: 10000,
                    name: isProd ? 'fonts/[name].[ext]' : `fonts/[name].[ext]`
                }
            }
        ]
    },
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                'NODE_ENV': JSON.stringify(NODE_ENV),
            },
        }),
        // extract css into its own file
        new ExtractTextPlugin({
            filename: isProd ? 'css/[name].css' : 'css/[name].css'
        }),

        // split vendor js into its own file
        new webpack.optimize.CommonsChunkPlugin({
            name: 'vendor',
            minChunks: function(module, count) {
                // any required modules inside node_modules are extracted to vendor
                return (
                    module.resource &&
                    /\.js$/.test(module.resource) &&
                    module.resource.indexOf(
                        path.join(__dirname, 'node_modules')
                    ) === 0
                );
            }
        }),

        new webpack.optimize.CommonsChunkPlugin({
            name: 'manifest',
            chunks: ['vendor']
        }),

        new webpack.optimize.OccurrenceOrderPlugin(),

        new WebpackLaravelMixManifest(),

        new CleanWebpackPlugin(
            ['./*'], // 匹配删除的文件
            {
                root: BuildRoot, // 根目录
                verbose: true, // 开启在控制台输出信息
                dry: false // 启用删除文件
            }
        ),
        // 依托关键加载的插件
        ...plugins
    ],
};

export default webpackConfig;