import webpack from 'webpack';
import path from 'path';
import autoprefixer from 'autoprefixer';
import ExtractTextPlugin from 'extract-text-webpack-plugin';
import { StatsWriterPlugin } from 'webpack-stats-plugin';
import lodash from 'lodash';
import fs from 'fs';

// 环境变量获取
const NODE_ENV = process.env.NODE_ENV || 'development';
const isHot = process.argv.includes('--hot');

// 是否是正式环境编译
const isProd = NODE_ENV === 'production'

// 各项资源地址定义
const assetsRoot = path.join(__dirname, 'resources', 'assets');
const buildAssetsRoot = path.resolve(__dirname, 'public/');

console.log(buildAssetsRoot);

// 入口配置
const entry = {
  admin: path.join(assetsRoot, 'admin', 'index.js'),
}

const cssLoaders = (options = {}) => {
  // generate loader string to be used with extract text plugin
  function generateLoaders (loaders) {
    var sourceLoader = loaders.map(function (loader) {
      var extraParamChar
      if (/\?/.test(loader)) {
        loader = loader.replace(/\?/, '-loader?')
        extraParamChar = '&'
      } else {
        loader = loader + '-loader'
        extraParamChar = '?'
      }
      return loader + (options.sourceMap ? extraParamChar + 'sourceMap' : '')
    }).join('!')

    // Extract CSS when that option is specified
    // (which is the case during production build)
    if (options.extract) {
      return ExtractTextPlugin.extract('vue-style-loader', sourceLoader)
    } else {
      return ['vue-style-loader', sourceLoader].join('!')
    }
  }

  // http://vuejs.github.io/vue-loader/en/configurations/extract-css.html
  return {
    css: generateLoaders(['css']),
    sass: generateLoaders(['css', 'sass?indentedSyntax']),
    scss: generateLoaders(['css', 'sass'])
  }
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
}

// 环境插件～不同环境启用的不同插件.
const plugins = isProd ?
[
  new webpack.optimize.UglifyJsPlugin({
    compress: {
      warnings: false
    }
  })
] : 
[
  new webpack.NoErrorsPlugin()
]

const webpackConfig = {
  devtool: isProd ? false : 'source-map',
  entry: entry,
  output: {
    path: isHot ? '/' : path.join(buildAssetsRoot),
    publicPath: isHot ? 'http://localhost:8080/' : '../',
    filename: isProd ? 'js/[name].[chunkhash].js' : 'js/[name].js',
  },
  resolve: {
    extensions: ['', '.js', '.vue', '.json'],
    fallback: [path.join(__dirname, 'node_modules')],
    alias: {
      'jquery': 'jquery/src/jquery',
      'vue$': 'vue/dist/vue.common.js',
      'admin': path.resolve(assetsRoot, 'admin'),
      'assets': assetsRoot
    }
  },
  resolveLoader: {
    fallback: [path.join(__dirname, 'node_modules')]
  },
  module: {
    preLoaders: [
      // vue
      {
        test: /\.vue$/,
        loader: 'eslint-loader',
        include: [
          assetsRoot
        ],
        exclude: /node_modules/
      },

      // js
      {
        test: /\.js$/,
        loader: 'eslint-loader',
        include: [
          assetsRoot
        ],
        exclude: /node_modules/
      }
    ],
    loaders: [
      // vue
      {
        test: /\.vue$/,
        loader: 'vue-loader',
      },

      // js
      {
        test: /\.js$/,
        loader: 'babel-loader',
        include: [
          assetsRoot
        ],
        exclude: /node_modules/
      },

      // image
      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        loader: 'url-loader',
        query: {
          limit: 10000,
          name: isProd ? 'images/[hash].[ext]' : `images/[name].[ext]`
        }
      },

      // fonts
      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
        loader: 'url-loader',
        query: {
          limit: 10000,
          name: isProd ? 'fonts/[hash].[ext]' : `fonts/[name].[ext]`
        }
      },
    ],
  },

  vue: {
    loaders: cssLoaders({
      sourceMap: !isProd,
      extract: true,
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
        // browsers: ['last 2 versions']
      })
    ],
  },

  plugins: [
    new webpack.DefinePlugin({
      'process.env': {
        'NODE_ENV': JSON.stringify(NODE_ENV),
      },
    }),
    new ExtractTextPlugin(isProd ? 'css/[name].[chunkhash].css' : 'css/[name].css'),
    new webpack.optimize.OccurrenceOrderPlugin(),
    new StatsWriterPlugin({
      filename: 'mix-manifest.json',
      transform: MixManifest
    }),
    // 依托关键加载的插件
    ...plugins,
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
