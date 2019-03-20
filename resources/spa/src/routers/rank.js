/* 排行榜 */

import i18n from '@/i18n'

const RankIndex = () =>
  import(/* webpackChunkName: 'rank' */ '../page/rank/RankIndex.vue')
const RankUsers = () =>
  import(/* webpackChunkName: 'rank' */ '../page/rank/children/RankUsers.vue')
const RankFeeds = () =>
  import(/* webpackChunkName: 'rank' */ '../page/rank/children/RankFeeds.vue')
const RankFollowers = () =>
  import(/* webpackChunkName: 'rank' */ '../page/rank/lists/FansList.vue')
const RankCheckinLikes = () =>
  import(/* webpackChunkName: 'rank' */ '../page/rank/lists/CheckinList.vue')
const RankFeedList = () =>
  import(/* webpackChunkName: 'rank' */ '../page/rank/lists/RankFeedList.vue')

export default [
  {
    path: '/rank',
    component: RankIndex,
    meta: { title: i18n.t('rank.name') },
    redirect: '/rank/users',
    children: [
      {
        path: 'users',
        component: RankUsers,
        meta: {
          keepAlive: true,
        },
      },
      {
        path: 'feeds',
        component: RankFeeds,
        meta: {
          keepAlive: true,
        },
      },
    ],
  },
  {
    path: '/rank/users/followers',
    component: RankFollowers,
  },
  {
    path: '/rank/users/checkin',
    component: RankCheckinLikes,
  },
  {
    path: '/rank/f/:time',
    component: RankFeedList,
  },
]
