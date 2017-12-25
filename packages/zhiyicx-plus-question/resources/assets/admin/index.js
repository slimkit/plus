import Vue from 'vue';
import { sync } from 'vuex-router-sync';

// Filters
import * as filters from './filters';
for (const k in filters) {
  Vue.filter(k, filters[k]);
}

// Injections

import './components/commons';
import router from './router';
import store from './store';
sync(store, router);

// Root component
import App from './App';

new Vue({

  router,
  store,

  el: '#app',
  render: h => h(App)

});

