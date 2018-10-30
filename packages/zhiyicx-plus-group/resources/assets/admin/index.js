import Vue from 'vue';

import './components/commons';
import router from './router';

// Root component
import App from './App';

new Vue({
  router,
  el: '#app',
  render: h => h(App)
});
