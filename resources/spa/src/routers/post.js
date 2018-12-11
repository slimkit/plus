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
      title: '编辑文章',
      requiresAuth: true,
    },
  },
  {
    path: '/post/text',
    name: 'PostText',
    component: PostText,
    meta: {
      title: '发布动态',
      requiresAuth: true,
    },
  },
  {
    path: '/post/pic',
    name: 'PostImage',
    component: PostImage,
    meta: {
      title: '发布图片',
      requiresAuth: true,
    },
  },
]
