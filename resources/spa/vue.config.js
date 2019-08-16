const path = require('path')
function resolve (dir) {
  return path.join(__dirname, dir)
}

module.exports = {
  publicPath: process.env.BASE_URL || '/',
  lintOnSave: true,

  // compiler: false,
  css: {
    // eslint-disable-next-line no-eval
    sourceMap: !!eval(process.env.GENERATE_CSS_MAP),
    loaderOptions: {
      less: {
        globalVars: require('./theme'),
      },
    },
  },

  chainWebpack: config => {
    config.plugin('html').tap(args => {
      args[0].chunksSortMode = 'none'
      return args
    })
    config.module
      .rule('yaml').test(/\.ya?ml$/)
      .use('json-loader').loader('json-loader').end()
      .use('yaml-loader').loader('yaml-loader')
    config.resolve.alias
      .set('@', resolve('src'))
    config.output.chunkFilename(`js/[name]-[chunkhash].js`)
  },

  devServer: {
    open: false,
    disableHostCheck: true,
    proxy: {
      '/api': {
        target: process.env.VUE_APP_API_HOST,
        changeOrigin: true,
      },
      '/storage': {
        target: process.env.VUE_APP_API_HOST,
        changeOrigin: true,
      },
    },
  },

  pwa: {
    name: process.env.VUE_APP_NAME || 'Plus (ThinkSNS+)',
    themeColor: '#59B6D7',
    msTileColor: '#59B6D7',
  },

  pluginOptions: {
    i18n: {
      locale: 'en',
      fallbackLocale: 'en',
      localeDir: 'locales',
      enableInSFC: false,
    },
  },
}
