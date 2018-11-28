import Vue from 'vue';
import VueRouter from 'vue-router';

// components.
import AuthList from './component/pc/AuthList.vue';
import Navmenu from './component/pc/Navmenu.vue';
import Footnav from './component/pc/Footnav.vue';
import Setting from './component/pc/Setting.vue';
import LoginManage from './component/pc/LoginManage.vue';
import Navmanage from './component/pc/Navmanage.vue';
import Report from './component/report/Report.vue';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'hash',
  routes: [
    // root.
    {
      path: '/',
      redirect: 'setting'
    },
    {
      path: '/setting',
      component: Setting,
    },
    {
      path: '/loginmanage',
      component: LoginManage
    },
    {
      path: '/navmenu',
      component: Navmenu,
    },
    {
      path: '/footnav',
      component: Footnav,
    },
    {
      path: '/navmanage',
      component: Navmanage,
    },
    {
      path: '/navmanage/:navId/:parentId',
      component: Navmanage,
    },
    {
      path: '/authlist',
      component: AuthList,
    },
    {
      path: '/report',
      component: Report,
    }
    ]
});

export default router;
