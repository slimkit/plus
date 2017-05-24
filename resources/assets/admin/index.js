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
