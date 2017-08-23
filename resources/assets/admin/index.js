// jQuery and Bootstrap-SASS
// -------------------------
// Questions: Why use CommonJS require?
// Answer: Because es6 module export lead to jquery plug-in can not run.
// -------------------------
window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');

import Vue from 'vue';
import { sync } from 'vuex-router-sync';
import App from './App.vue';
import store from './store';
import router from './router';

// sync the router with the vuex store.
// this registers `store.state.route`
sync(store, router);

// 注册一个全局自定义指令 v-focus
Vue.directive('focus', {
  // 当绑定元素插入到 DOM 中。
  inserted: function (el) {
    // 聚焦元素
    el.focus()
  }
})

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
