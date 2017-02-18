import Vue from 'vue';
import VueRouter from 'vue-router';
import auth, { requireAuth, loggedAuth } from '../util/auth';

// components.
import Login from '../component/Login';
import Home from '../component/Home';
import Settings from '../component/Settings';
import User from '../component/User';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'hash',
  base: '/admin/',
  routes: [
    {
      path: '/',
      component: Home,
      beforeEnter: requireAuth,
      children: [
        { path: '', component: Settings },
        { path: 'users', component: User }
      ]
    },
    { path: '/login', component: Login, beforeEnter: loggedAuth },
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
