import i18n from '@/i18n'

const NewsList = () =>
  import(/* webpackChunkName: 'news' */ '@/page/news/NewsList.vue')
const NewsSearch = () =>
  import(/* webpackChunkName: 'news' */ '@/page/news/NewsSearch.vue')
const NewsDetail = () =>
  import(/* webpackChunkName: 'news' */ '@/page/news/NewsDetail.vue')
const ArticleLikeList = () =>
  import(/* webpackChunkName: 'news' */ '@/page/article/ArticleLikeList.vue')
const ArticleRewardList = () =>
  import(/* webpackChunkName: 'news' */ '@/page/article/ArticleRewardList.vue')

export default [
  {
    path: '/news',
    component: NewsList,
    meta: {
      title: i18n.t('news.name'),
      keepAlive: true,
    },
  },
  {
    path: '/news/:newsId(\\d+)',
    component: NewsDetail,
    meta: {
      title: i18n.t('news.detail'),
      keepAlive: true,
    },
  },
  {
    path: '/news/search',
    component: NewsSearch,
    meta: {
      title: i18n.t('search'),
      keepAlive: true,
    },
  },
  /**
   * 点赞列表 && 打赏列表 路由格式固定
   * 帖子/资讯/问答 相关路由 统一使用 article 代替 id
   * 通过传递 不同的 meta[type] 实现组件复用
   * copy by @/routers/feed.js
   */
  {
    path: '/news/:article(\\d+)/likers',
    component: ArticleLikeList,
    meta: {
      title: i18n.t('article.list.like'),
      type: 'news',
    },
  },
  {
    path: '/news/:article(\\d+)/rewarders',
    component: ArticleRewardList,
    meta: {
      title: i18n.t('article.list.reward'),
      type: 'news',
    },
  },
]
