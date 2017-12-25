import Vue from 'vue';
import TimeAgo from 'vue-timeago';
import App from './App.vue';
import router from './router/index';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-default/index.css';

Vue.use(ElementUI);
Vue.use(TimeAgo, {
  name: 'timeago',
  locale: 'zh-CN',
  locales: {
    'zh-CN': require('vue-timeago/locales/zh-CN.json')
  }
});
new Vue({
  el: '#app',
  router,
  render: h => h(App)
})