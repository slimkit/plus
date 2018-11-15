<template>
  <div :class="prefixCls">

    <common-header>{{ title }}资讯排行榜</common-header>

    <load-more
      ref="loadmore"
      :on-refresh="onRefresh"
      :on-load-more="onLoadMore">
      <div :class="`${prefixCls}-list`">
        <rank-list-item
          v-for="(user, index) in users"
          :prefix-cls="prefixCls"
          :key="user.id"
          :user="user"
          :index="index">
          <p>阅读量：{{ user.extra.count || 0 }}</p>
        </rank-list-item>
      </div>
    </load-more>
  </div>
</template>

<script>
import HeadTop from "@/components/HeadTop";
import RankListItem from "../components/RankListItem.vue";
import { getRankUsers } from "@/api/ranks.js";
import { limit } from "@/api";

const prefixCls = "rankItem";
const api = "/news/ranks";
const config = {
  week: {
    vuex: "rankNewsWeek",
    title: "本周",
    query: "week"
  },
  today: {
    vuex: "rankNewsToday",
    title: "今日",
    query: "day"
  },
  month: {
    vuex: "rankNewsMonth",
    title: "本月",
    query: "month"
  }
};

export default {
  name: "NewsList",
  components: {
    HeadTop,
    RankListItem
  },
  data() {
    return {
      prefixCls,
      loading: false,
      title: "", // 标题
      vuex: "", // vuex主键
      query: "" // api查询query
    };
  },

  computed: {
    users() {
      return this.$store.getters.getUsersByType(this.vuex);
    }
  },

  created() {
    let time = this.$route.params.time || "today";
    this.title = config[time].title;
    this.vuex = config[time].vuex;
    this.query = config[time].query;
    if (this.users.length === 0) {
      this.onRefresh();
    }
  },

  methods: {
    cancel() {
      this.to("/rank/news");
    },
    to(path) {
      path = typeof path === "string" ? { path } : path;
      if (path) {
        this.$router.push(path);
      }
    },
    onRefresh() {
      getRankUsers(api, { type: this.query }).then(data => {
        this.$store.commit("SAVE_RANK_DATA", { name: this.vuex, data });
        this.$refs.loadmore.topEnd(false);
      });
    },
    onLoadMore() {
      getRankUsers(api, {
        type: this.query,
        offset: this.users.length || 0
      }).then((data = []) => {
        this.$store.commit("SAVE_RANK_DATA", {
          name: this.vuex,
          data: [...this.users, ...data]
        });
        this.$refs.loadmore.bottomEnd(data.length < limit);
      });
    }
  }
};
</script>

<style lang="less" src="../style.less">
</style>
