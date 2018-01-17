import Vue from 'vue';
import VueRouter from 'vue-router';
import Projects from './components/pages/Projects';
import Setting from './components/pages/Setting';

Vue.use(VueRouter);

export default new VueRouter({
  base: '/test-group-worker/',
  mode: 'history',
  routes: [
    { path: '/', redirect: '/projects' },
    { path: '/projects', component: Projects },
    { path: '/setting', component: Setting },
  ]
});
