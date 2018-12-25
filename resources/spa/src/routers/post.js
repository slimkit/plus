import i18n from '@/i18n'

const PostNews = () =>
  import(/* webpackChunkName: 'post' */ '@/page/post/PostNews.vue')
const PostImage = () =>
  import(/* webpackChunkName: 'post' */ '@/page/post/PostImage.vue')
const PostText = () =>
  import(/* webpackChunkName: 'post' */ '@/page/post/PostText.vue')

export default [
  {
    path: '/post/release',
    name: 'PostNews',
    component: PostNews,
    meta: {
      title: i18n.t('release.post'),
      requiresAuth: true,
    },
  },
  {
    path: '/post/text',
    name: 'PostText',
    component: PostText,
    meta: {
      title: i18n.t('release.text'),
      requiresAuth: true,
    },
  },
  {
    path: '/post/pic',
    name: 'PostImage',
    component: PostImage,
    meta: {
      title: i18n.t('release.image'),
      requiresAuth: true,
    },
  },
]
