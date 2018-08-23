import Vue from 'vue';
import router from './router';

import App from './app.vue';

const app = new Vue({
  router,
  el: '#app',
  render: h => h(App)
});