import Vue from 'vue'
import VueRouter from 'vue-router'

import routes from './routes'

Vue.use(VueRouter)

const router = new VueRouter({
  routes,
  base: process.env.BASE_URL,
  strict: process.env.NODE_ENV !== 'production',
  mode: process.env.VUE_APP_ROUTER_MODE || 'hash',
  scrollBehavior (to, from, savedPosition) {
    if (to.hash) {
      return {
        selector: to.hash,
      }
    }
    if (savedPosition) {
      // hack 滚动到保存的位置， 原生方法失效的问题
      setTimeout(() => {
        window.scrollTo(savedPosition.x, savedPosition.y)
      }, 100)
    } else {
      const {
        meta: { keepAlive = false, toTop = false },
      } = from
      if (keepAlive && !toTop) {
        from.meta.savedPosition =
          document.body.scrollTop || document.documentElement.scrollTop
      }
      setTimeout(() => {
        window.scrollTo(0, to.meta.savedPosition)
      }, 100)
    }
  },
})

/**
 * 路由守卫 登录检测 islogin
 *
 * 需要登录的页面路由需要添加
 * 登录后不可访问的路由需要添加
 * meta.forGuest = true
 *
 */
router.beforeEach((to, from, next) => {
  const logged = !!window.$lstore.hasData('H5_ACCESS_TOKEN')
  const forGuest = to.matched.some(record => record.meta.forGuest)
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)

  if (logged) {
    forGuest ? next({ path: '/feeds?type=hot' }) : next()
  } else {
    forGuest
      ? next()
      : requiresAuth
        ? next({ path: '/signin', query: { redirect: to.fullPath } })
        : next()
  }
})

export default router
