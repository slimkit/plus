import TopicHome from '@/page/topic/TopicHome.vue'
import TopicCreate from '@/page/topic/TopicCreate.vue'
import TopicSearch from '@/page/topic/TopicSearch.vue'

export default [
  {
    path: '/topic',
    name: 'TopicHome',
    component: TopicHome,
    meta: {
      title: '话题',
    },
  },
  {
    path: '/topic/create',
    name: 'TopicCreate',
    component: TopicCreate,
    meta: {
      title: '创建话题',
      requiresAuth: true,
    },
  },
  {
    path: '/topic/search',
    name: 'TopicSearch',
    component: TopicSearch,
    meta: {
      title: '搜索话题',
      keepAlive: true,
      requiresAuth: true,
    },
  },
]
