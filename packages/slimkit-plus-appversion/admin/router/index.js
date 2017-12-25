import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './routers';

Vue.use(VueRouter);
const scrollBehavior = (to, from, savedPosition) => {
  if (savedPosition) {
    return savedPosition
  } else {
    const position = {};
    if (to.hash) {
      position.selector = to.hash
    }
    if (to.matched.some(m => m.meta.scrollToTop)) {
      position.x = 0
      position.y = 0
    }
    return position
  }
}
const router = new VueRouter({
  mode: 'hash',
  // base: '/h5',
  scrollBehavior,
  routes
});

export default router;