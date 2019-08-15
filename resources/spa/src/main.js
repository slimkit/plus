import { version } from '../package.json'
import Vue from 'vue'

import 'github-markdown-css'
import './style/tsplus.less'
import './icons/iconfont.js' // from http://www.iconfont.cn h5 仓库
import './util/rem'
import './util/prototype' // 原型拓展

import Message from './plugins/message/'
import AsyncImage from './components/FeedCard/v-async-image.js'

import imgCropper from '@/plugins/imgCropper'
import lstore from '@/plugins/lstore/index.js'

import Ajax from './api/api.js'
import mixin from './mixin.js'
import * as filters from './filters.js'
import components from './components.js'

import store from './stores/'
import router from './routers/'
import i18n from './i18n'
import App from './app'
import bus from './bus'
import './registerServiceWorker'
import './vendor'

import * as WebIM from '@/vendor/easemob'

export { version }

Vue.mixin(mixin)

components.forEach(component => {
  Vue.component(component.name, component)
})

Vue.config.productionTip = false

Vue.prototype.$http = Ajax
Vue.prototype.$Message = Message
Vue.prototype.$MessageBundle = filters.plusMessageFirst
Vue.prototype.$bus = bus

Vue.use(AsyncImage)
Vue.use(imgCropper)
Vue.use(lstore)

for (const k in filters) {
  Vue.filter(k, filters[k])
}
if (!window.initUrl) {
  window.initUrl = window.location.href.replace(/(\/$)/, '')
}

/* eslint-disable no-new */
new Vue({
  store,
  router,
  i18n,
  created () {
    process.env.VUE_APP_EASEMOB_APP_KEY && WebIM.openWebIM()
  },
  render: h => h(App),
}).$mount('#app')

// Exposed versions
/* eslint-disable no-console */
console.info(
  `%cWelcome to Plus(ThinkSNS+)! Release %c v${version} `,
  'color: #00A9E0;',
  'background:#35495e; padding: 1px; border-radius: 3px; color: #fff;'
)
console.info(
  `%cDevelopment by SlimKit Group 👉 https://github.com/slimkit \nApache-2.0 Licensed | Copyright © ${new Date().getFullYear()} Chengdu ZhiYiChuangXiang Technology Co., Ltd. All rights reserved.`,
  'color: #00A9E0;'
)
