import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const Foo = { template: '<div>foo <router-link to="/demo">bar</router-link> </div>' };
const Bar = { template: '<div>bar <router-link to="/">foo</router-link> </div>' };

const router = new VueRouter({
  mode: 'hash',
  base: '/admin/',
  routes: [
    { path: '/', component: Foo },
    { path: '/demo', component: Bar }
  ]
});

export default router;
