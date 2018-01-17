import Vue from 'vue';
import VueRouter from 'vue-router';
import Projects from './components/pages/Projects.vue';

Vue.use(VueRouter);

export default new VueRouter({
  base: '/test-group-worker/',
  mode: 'history',
  routes: [
    { path: '/', component: Projects },
  ]
});
