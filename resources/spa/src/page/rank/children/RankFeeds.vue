<template>
  <div>
    <rank-users-component
      v-for="rank in ranks"
      v-bind="rank"
      :key="rank.api" />
  </div>
</template>

<script>
import RankUsersComponent from "../components/RankUsersComponent";

const ranks = [
  {
    title: "今日动态排行榜",
    listUrl: "/rank/f/today",
    api: "/feeds/ranks?type=day&limit=15",
    name: "rankFeedsToday"
  },
  {
    title: "一周动态排行榜",
    listUrl: "/rank/f/week",
    api: "/feeds/ranks?type=week&limit=15",
    name: "rankFeedsWeek"
  },
  {
    title: "本月动态排行榜",
    listUrl: "/rank/f/month",
    api: "/feeds/ranks?type=month&limit=15",
    name: "rankFeedsMonth"
  }
];

export default {
  name: "RankFeeds",
  components: {
    RankUsersComponent
  },
  data() {
    return {
      ranks
    };
  }
};
</script>
