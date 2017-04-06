import webpack from 'webpack';
import path from 'path';
import autoprefixer from 'autoprefixer';
import ExtractTextPlugin from 'extract-text-webpack-plugin';
import { StatsWriterPlugin } from 'webpack-stats-plugin';
import lodash from 'lodash';
import fs from 'fs';
import formatter from 'eslint-friendly-formatter';
import OptimizeCSSPlugin from 'optimize-css-assets-webpack-plugin';

// 环境变量获取
const NODE_ENV = process.env.NODE_ENV || 'development';
const isHot = process.argv.includes('--hot');

// 是否是正式环境编译
const isProd = NODE_ENV === 'production';

// 各项资源地址定义
const assetsRoot = path.join(__dirname, 'resources', 'assets');
const buildAssetsRoot = path.resolve(__dirname, 'public/');

// 入口配置
const entry = {
  admin: path.join(assetsRoot, 'admin', 'index.js')
};

function cssLoaders (options = {}) {
  let cssLoader = {
    loader: 'css-loader',
    options: {
      minimize: isProd,
      sourceMap: options.sourceMap
    }
  };

  // generate loader string to be used with extract text plugin
  function generateLoaders (loader, loaderOptions) {
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
    // less: generateLoaders('less'),
    sass: generateLoaders('sass', { indentedSyntax: true }),
    scss: generateLoaders('sass'),
    // stylus: generateLoaders('stylus'),
    // styl: generateLoaders('stylus')
  };
};

function styleLoaders(options = {}) {
  let output = [];
  const loaders = cssLoaders(options);

  for (let extension in loaders) {
    let loader = loaders[extension]
    output.push({
      test: new RegExp('\\.' + extension + '$'),
      use: loader
    });
  }

  return output;
};

function MixManifest(stats) {
  let flattenedPaths = [].concat.apply([], lodash.values(stats.assetsByChunkName));

  let manifest = flattenedPaths.reduce((manifest, filename) => {
    let original = filename.replace(/\.(\w{20})(\..+)/, '$2');
    manifest['/'+original] = '/'+filename;
    // manifest[original] = filename;

    return manifest;
  }, {});

  // return stats;
  return JSON.stringify(manifest, null, 2);
};

// 环境插件～不同环境启用的不同插件.
const plugins = isProd ?
[
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
] : 
[
  // https://github.com/glenjamin/webpack-hot-middleware#installation--usage
  new webpack.NoEmitOnErrorsPlugin(),
  ...(isHot ? [
    new webpack.HotModuleReplacementPlugin(),
  ] : [])
];

const webpackConfig = {
  devtool: isProd ? false : 'source-map',
  entry: entry,
  output: {
    path: isHot ? '/' : buildAssetsRoot,
    publicPath: isHot ? 'http://localhost:8080/' : '../',
    filename: isProd ? 'js/[name].[chunkhash].js' : 'js/[name].js',
  },
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    modules: [
      assetsRoot,
      path.join(__dirname, 'node_modules'),
    ],
    alias: {
      'jquery': `jquery/dist/jquery.${isProd ? 'min.js' : 'js'}`,
      'vue$': 'vue/dist/vue.common.js'
    }
  },
  module: {
    rules: [
      ...styleLoaders({ sourceMap: !isProd }),
      {
        test: /\.(js|vue)$/,
        loader: 'eslint-loader',
        enforce: "pre",
        include: [ assetsRoot ],
        options: { formatter },
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          loaders: cssLoaders({
            sourceMap: !isProd
          }),
          postcss: [
            autoprefixer({
              browsers: [
                'Android 2.3',
                'Android >= 4',
                'Chrome >= 20',
                'Firefox >= 24',
                'Explorer >= 8',
                'iOS >= 6',
                'Opera >= 12',
                'Safari >= 6'
              ]
            })
          ]
        }
      },
      {
        test: /\.js$/,
        loader: 'babel-loader',
        include: [assetsRoot]
      },
      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        loader: 'url-loader',
        query: {
          limit: 10000,
          name: isProd ? 'images/[hash].[ext]' : `images/[name].[ext]`
        }
      },
      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
        loader: 'url-loader',
        query: {
          limit: 10000,
          name: isProd ? 'fonts/[hash].[ext]' : `fonts/[name].[ext]`
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
      filename: isProd ? 'css/[name].[chunkhash].css' : 'css/[name].css'
    }),
    // split vendor js into its own file
    // new webpack.optimize.CommonsChunkPlugin({
    //   name: 'vendor',
    //   minChunks: function (module, count) {
    //     // any required modules inside node_modules are extracted to vendor
    //     return (
    //       module.resource &&
    //       /\.js$/.test(module.resource) &&
    //       module.resource.indexOf(
    //         path.join(__dirname, 'node_modules')
    //       ) === 0
    //     )
    //   }
    // }),
    // new webpack.optimize.CommonsChunkPlugin({
    //   name: 'manifest',
    //   chunks: ['vendor']
    // }),
    // new webpack.optimize.OccurrenceOrderPlugin(),
    new StatsWriterPlugin({
      filename: 'mix-manifest.json',
      transform: MixManifest
    }),
    // 依托关键加载的插件
    ...plugins
  ],

  // Webpack Dev Server Configuration.
  devServer: {
    historyApiFallback: true,
    noInfo: true,
    compress: true
  }

};

// mix.
if (isHot) {

  // hot file.
  let hotFile = buildAssetsRoot+'/hot';
  if (fs.existsSync(hotFile)) {
    fs.unlinkSync(hotFile);
  }
  // hot reloading enabled
  fs.writeFileSync(hotFile, 'hot reloading enabled');
}

export default webpackConfig;
