import TopicHome from '@/page/topic/TopicHome.vue'
import TopicCreate from '@/page/topic/TopicCreate.vue'
import TopicSearch from '@/page/topic/TopicSearch.vue'
import TopicDetail from '@/page/topic/TopicDetail.vue'
import TopicParticipants from '@/page/topic/TopicParticipants.vue'

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
      requiresAuth: true,
    },
  },
  {
    path: '/topic/:topicId',
    name: 'TopicDetail',
    component: TopicDetail,
    meta: {
      title: '话题详情',
    },
  },
  {
    path: '/topic/:topicId/participants',
    name: 'TopicParticipants',
    component: TopicParticipants,
    meta: {
      title: '话题参与者',
    },
  },
]
