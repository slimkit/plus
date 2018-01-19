import 'element-ui/lib/theme-chalk/index.css';
import './style.css';
import 'github-markdown-css';
import Vue from 'vue';
import ElementUI from 'element-ui';
import App from './App.vue';
import router from './createRouter';

Vue.use(ElementUI);

new Vue({
  router,
  el: '#root',
  render: h => h(App)
});
