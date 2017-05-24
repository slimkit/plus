import Vue from 'vue';
import VueRouter from 'vue-router';
import { requireAuth, loggedAuth } from '../util/auth';

// routes.
import settingRouter from './setting';
import userRouter from './user';
import smsRouter from './sms';
import walletRouter from './wallet';

// components.
import Login from '../component/Login';
import Home from '../component/Home';
import Component from '../component/Component';

Vue.use(VueRouter);

const baseRoutes = [
  { path: '', redirect: '/setting' },
  { path: 'component/:component(.*)', component: Component }
];

const childrenRoutes = [
  settingRouter,
  userRouter,
  smsRouter,
  walletRouter,
];

const router = new VueRouter({
  mode: 'hash',
  // base: '/admin/',
  routes: [
    {
      path: '/',
      component: Home,
      beforeEnter: requireAuth,
      children: [...baseRoutes, ...childrenRoutes]
    },
    { path: '/login', component: Login, beforeEnter: loggedAuth }
  ]
});

export default router;
