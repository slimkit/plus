import NotFound from '../page/notFound'
import baseRoutes from './base.js'
import signRoutes from './sign.js'
import feedRoutes from './feed.js'
import newsRoutes from './news.js'
import rankRoutes from './rank.js'
import postRoutes from './post.js'
import groupRoutes from './group.js'
import messageRoutes from './message.js'
import questionRoutes from './question.js'
import profileRoutes from './profile.js'
import topicRoutes from './topic.js'

const router = [
  /* 入口重定向 */
  { path: '/', redirect: '/feeds' },

  ...baseRoutes,
  ...signRoutes,
  ...feedRoutes,
  ...postRoutes,
  ...newsRoutes,
  ...rankRoutes,
  ...groupRoutes,
  ...messageRoutes,
  ...questionRoutes,
  ...profileRoutes,
  ...topicRoutes,

  { path: '*', component: NotFound }, /* 404 页面 */
]

export default router
