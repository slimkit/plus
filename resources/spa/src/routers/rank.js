/* 排行榜 */
const RankIndex = () =>
  import(/* webpackChunkName: 'rank' */ "../page/rank/RankIndex.vue");
const RankUsers = () =>
  import(/* webpackChunkName: 'rank' */ "../page/rank/children/RankUsers.vue");
const RankFeeds = () =>
  import(/* webpackChunkName: 'rank' */ "../page/rank/children/RankFeeds.vue");
const RankFollowers = () =>
  import(/* webpackChunkName: 'rank' */ "../page/rank/lists/FansList.vue");
const RankCheckinLikes = () =>
  import(/* webpackChunkName: 'rank' */ "../page/rank/lists/CheckinList.vue");
const RankFeedList = () =>
  import(/* webpackChunkName: 'rank' */ "../page/rank/lists/RankFeedList.vue");

export default [
  {
    path: "/rank",
    component: RankIndex,
    meta: { title: "排行" },
    redirect: "/rank/users",
    children: [
      {
        path: "users",
        component: RankUsers,
        meta: {
          keepAlive: true
        }
      },
      {
        path: "feeds",
        component: RankFeeds,
        meta: {
          keepAlive: true
        }
      }
    ]
  } /* 排行 */,
  {
    path: "/rank/users/followers",
    component: RankFollowers,
    meta: {
      title: "全站粉丝排行榜",
      keepAlive: true
    }
  },
  {
    path: "/rank/users/checkin",
    component: RankCheckinLikes,
    meta: {
      title: "社区签到排行榜",
      keepAlive: true
    }
  },
  {
    path: "/rank/f/:time",
    component: RankFeedList,
    meta: {
      // keepAlive: true,
      title: "动态排行榜"
    }
  }
];
