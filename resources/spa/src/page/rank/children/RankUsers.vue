<template>
  <div>
    <rank-users-component
      v-for="rank in ranks"
      :title="rank.title"
      :api="rank.api"
      :name="rank.name"
      :key="rank.name"
      :list-url="rank.listUrl" />
  </div>
</template>

<script>
import RankUsersComponent from "../components/RankUsersComponent";

const ranks = [
  {
    title: "全站粉丝排行榜",
    listUrl: "/rank/users/followers",
    api: "/ranks/followers?limit=15",
    name: "rankFollowers"
  },
  {
    title: "社区签到排行榜",
    listUrl: "/rank/users/checkin",
    api: "/checkin-ranks?limit=15",
    name: "rankCheckin"
  }
];

export default {
  name: "RankUsers",
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
