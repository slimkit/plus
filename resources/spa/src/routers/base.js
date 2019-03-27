import AboutUs from '@/page/AboutUs.vue'

/* TODO */
import Settings from '@/page/Settings.vue'

import ProfileHome from '@/page/profile/ProfileHome.vue'
import UserInfo from '@/page/UserInfo.vue'
import UserHome from '@/page/UserHome.vue'
import UserFans from '@/page/UserFans.vue'
import UserFriends from '@/page/profile/UserFriends.vue'
/* TODO END */

import Discover from '@/page/Discover.vue'

import Find from '@/page/find/Find.vue'
import FindPop from '@/page/find/FindPop.vue'
import FindRec from '@/page/find/FindRec.vue'
import FindNew from '@/page/find/FindNew.vue'
import FindNer from '@/page/find/FindNer.vue'

import SearchUser from '@/page/find/SearchUser.vue'

import WechatSignin from '@/page/wechat/WechatSignin'
import WechatSignup from '@/page/wechat/WechatSignup.vue'
import WechatBindUser from '@/page/wechat/WechatBindUser.vue'

import Location from '@/page/Location.vue'

import $lstore from '@/plugins/lstore'

import i18n from '@/i18n'

export default [
  {
    path: '/discover',
    component: Discover,
    meta: {
      title: i18n.t('discover'),
    },
  },
  {
    name: 'find',
    path: '/find',
    redirect: '/find/pop',
    component: Find,
    meta: {
      title: i18n.t('find'),
      requiresAuth: false,
    },
    children: [
      {
        path: 'pop',
        component: FindPop,
        meta: {
          keepAlive: true,
        },
      },
      {
        path: 'new',
        component: FindNew,
        meta: {
          keepAlive: true,
        },
      },
      {
        path: 'rec',
        component: FindRec,
        meta: {
          keepAlive: true,
        },
      },
      {
        path: 'ner',
        component: FindNer,
        meta: {
          keepAlive: true,
        },
        beforeEnter (to, from, next) {
          $lstore.hasData('H5_CURRENT_POSITION') ? next() : next('/location')
        },
      },
    ],
  },
  {
    path: '/search/user',
    component: SearchUser,
    meta: {
      title: i18n.t('find'),
      keepAlive: true,
    },
  },
  {
    path: '/location',
    component: Location,
  },
  {
    path: '/profile',
    component: ProfileHome,
    meta: {
      title: i18n.t('profile.name'),
      requiresAuth: true,
    },
  },
  {
    name: 'UserFriends',
    path: '/users/friends',
    component: UserFriends,
    meta: {
      title: i18n.t('follow.friend'),
      requiresAuth: true,
    },
  },
  {
    name: 'UserDetail',
    path: '/users/:userId(\\d+)',
    component: UserHome,
    meta: {
      title: i18n.t('profile.home.name'),
      keepAlive: true,
      requiresAuth: true,
    },
  },
  {
    name: 'userfans',
    component: UserFans,
    path: '/users/:userId(\\d+)/:type(followers|followings)',
    meta: {
      title: i18n.t('fans'),
      keepAlive: true,
      requiresAuth: true,
    },
  },
  {
    path: '/info',
    component: UserInfo,
    meta: {
      title: i18n.t('profile.info'),
      requiresAuth: true,
    },
  },
  {
    path: '/setting',
    component: Settings,
    meta: {
      title: i18n.t('setting.name'),
      requiresAuth: true,
    },
  },
  {
    path: '/about',
    component: AboutUs,
    meta: {
      title: i18n.t('setting.about.name'),
    },
  },
  {
    path: '/wechat',
    component: WechatSignin,
    meta: {
      title: i18n.t('setting.login.logging'),
      forGuest: true,
    },
    beforeEnter (to, from, next) {
      navigator.userAgent.toLowerCase().indexOf('micromessenger') > -1
        ? to.query.code
          ? next()
          : next('/signin')
        : next('/signin')
    },
  },
  {
    path: '/wechat/signup',
    component: WechatSignup,
    meta: {
      title: i18n.t('setting.login.supply'),
      forGuest: true,
    },
    beforeEnter (to, from, next) {
      const accessToken = window.$lstore.getData('H5_WECHAT_MP_ASTOKEN', true)
      const nickname = window.$lstore.getData('H5_WECHAT_NICKNAME', true)
      accessToken && nickname ? next() : next('/wechat')
    },
  },
  {
    path: '/wechat/bind',
    component: WechatBindUser,
    meta: {
      title: i18n.t('setting.login.bind'),
      forGuest: true,
    },
    beforeEnter (to, from, next) {
      const accessToken = window.$lstore.getData('H5_WECHAT_MP_ASTOKEN', true)
      accessToken ? next() : next('/wechat')
    },
  },
]
