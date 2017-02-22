import Vue from 'vue';
import VueRouter from 'vue-router';
import { requireAuth, loggedAuth } from '../util/auth';

// routes.
import settingRouter from './setting';

// components.
import Login from '../component/Login';
import Home from '../component/Home';
import User from '../component/User';
import Component from '../component/Component';

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
        // root.
        { path: '', redirect: '/setting/base' },
        // Setting router.
        settingRouter,
        { path: 'users', component: User },
        { path: 'component/:component(.*)', component: Component }
      ]
    },
    { path: '/login', component: Login, beforeEnter: loggedAuth }
  ]
});

export default router;
