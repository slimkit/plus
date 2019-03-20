/**
 * 圈子路由模块
 */

import i18n from '@/i18n'
import GroupHome from '@/page/group/GroupHome.vue'

export default [
  {
    name: 'groupHome',
    path: '/group',
    component: GroupHome,
    meta: {
      title: i18n.t('group.index'),
      keepAlive: true,
      requiresAuth: true,
    },
  },
]
