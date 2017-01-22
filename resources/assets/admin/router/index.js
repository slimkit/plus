import Vue from 'vue';
import VueRouter from 'vue-router';
import auth, { requireAuth } from '../util/auth';
import Login from '../component/Login';

Vue.use(VueRouter);

const Foo = { template: '<div>foo <router-link to="/login">bar</router-link> </div>' };

const router = new VueRouter({
  mode: 'hash',
  base: '/admin/',
  routes: [
    { path: '/', component: Foo, beforeEnter: requireAuth },
    { path: '/login', component: Login },
    {
      path: '/logout',
      beforeEnter (to, form, next) {
        auth.logout();
        next('/login');
      }
    }
  ]
});

export default router;
