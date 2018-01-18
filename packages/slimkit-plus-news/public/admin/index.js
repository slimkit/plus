import Vue from 'vue';
import './components/commons';
import router from './router';

// import store from './store';

import App from './app.vue';

// Filters
import * as filters from './filters';
for (const k in filters) {
  Vue.filter(k, filters[k]);
}

const app = new Vue({
    router,

    el: '#app',
    render: h => h(App)
});