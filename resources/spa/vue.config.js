module.exports = {
  baseUrl: process.env.BASE_URL || "/",
  lintOnSave: true,

  // compiler: false,
  css: {
    sourceMap: !!eval(process.env.GENERATE_CSS_MAP),
    loaderOptions: {
      less: {
        globalVars: require("./theme")
      }
    }
  },

  configureWebpack: {
    output: {
      chunkFilename: "js/[name]-[chunkhash].js"
    }
  },

  chainWebpack: config => {
    config.plugin("html").tap(args => {
      args[0].chunksSortMode = "none";
      return args;
    });
  },

  devServer: {
    open: false,
    disableHostCheck: true,
    proxy: {
      "/api": {
        target: process.env.VUE_APP_API_HOST,
        changeOrigin: true
      }
    }
  },

  pwa: {
    name: process.env.VUE_APP_NAME || "Plus (ThinkSNS+)",
    themeColor: "#59B6D7",
    msTileColor: "#59B6D7"
  }
};
