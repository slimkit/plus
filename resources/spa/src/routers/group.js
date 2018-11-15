/**
 * 圈子路由模块
 */

import GroupHome from "@/page/group/GroupHome.vue";

export default [
  {
    name: "groupHome",
    path: "/group",
    component: GroupHome,
    meta: {
      title: "圈子首页",
      keepAlive: true,
      requiresAuth: true
    }
  }
];
