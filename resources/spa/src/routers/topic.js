import i18n from '@/i18n'

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
      title: i18n.t('feed.topic.name'),
    },
  },
  {
    path: '/topic/create',
    name: 'TopicCreate',
    component: TopicCreate,
    meta: {
      title: i18n.t('feed.topic.create'),
      requiresAuth: true,
    },
  },
  {
    path: '/topic/:topicId/edit',
    name: 'TopicEdit',
    component: TopicCreate,
    meta: {
      title: i18n.t('feed.topic.edit'),
      requiresAuth: true,
    },
  },
  {
    path: '/topic/search',
    name: 'TopicSearch',
    component: TopicSearch,
    meta: {
      title: i18n.t('feed.topic.search'),
      keepAlive: true,
      requiresAuth: true,
    },
  },
  {
    path: '/topic/:topicId',
    name: 'TopicDetail',
    component: TopicDetail,
    meta: {
      title: i18n.t('feed.topic.detail'),
    },
  },
  {
    path: '/topic/:topicId/participants',
    name: 'TopicParticipants',
    component: TopicParticipants,
    meta: {
      title: i18n.t('feed.topic.participants'),
    },
  },
]
