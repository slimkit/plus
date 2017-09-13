import Vue from 'vue';
import VueRouter from 'vue-router';
import { requireAuth, loggedAuth } from '../util/auth';

// routes.
import settingRouter from './setting';
import userRouter from './user';
import smsRouter from './sms';
import walletRouter from './wallet';
import adRouter from './ad';
import paidRouter from './paid';
import certificationRouter from './certification';
import coversationRouter from './conversation';
import captchaRouter from './captcha';
import goldRouter from './gold';
import rewardRouter from './reward';

// components.
import Login from '../component/Login';
import Home from '../component/Home';
import Package from '../component/Package';
import Component from '../component/Component';

Vue.use(VueRouter);

const baseRoutes = [
  { path: '', redirect: '/setting' },
  { path: 'package/:key', component: Package },
  { path: 'component/:component(.*)', component: Component }
];

const childrenRoutes = [
  settingRouter,
  userRouter,
  smsRouter,
  walletRouter,
  adRouter,
  paidRouter,
  certificationRouter,
  coversationRouter,
  captchaRouter,
  goldRouter,
  rewardRouter
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
