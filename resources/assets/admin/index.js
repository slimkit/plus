import Vue from 'vue';
import { sync } from 'vuex-router-sync';
import App from './App.vue';
import store from './store';
import router from './router';
import BootstrapKit from 'simkit-bootstrap-ui-kit';
import './component/commons';

// Filters
import * as filters from './filters';
for (const k in filters) {
  Vue.filter(k, filters[k]);
}

// 注册一个全局自定义指令 v-focus
Vue.directive('focus', {
  // 当绑定元素插入到 DOM 中。
  inserted: function (el) {
    // 聚焦元素
    el.focus()
  }
});

// Injections
import './components/commons';

// sync the router with the vuex store.
// this registers `store.state.route`
sync(store, router);

// Using `slimkit-bootstrap-ui-kit` package.
Vue.use(BootstrapKit);

// create the app instance.
// here we inject the router and store to all child components,
// making them available everywhere as `this.$router` and `this.$store`.
const app = new Vue({
  router,
  store,
  el: '#app',
  render: h => h(App)
});

export { app, router, store };
