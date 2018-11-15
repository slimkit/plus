<template>
  <div :class="prefixCls">

    <common-header>问答达人排行榜</common-header>

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
          <p>问答点赞量：{{ user.extra.count || 0 }}</p>
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

const api = "/question-ranks/likes";
const prefixCls = "rankItem";

export default {
  name: "QuestionLikesList",
  components: {
    HeadTop,
    RankListItem
  },
  data() {
    return {
      prefixCls,
      loading: false,
      vuex: "rankQuestionLikes"
    };
  },

  computed: {
    users() {
      return this.$store.getters.getUsersByType(this.vuex);
    }
  },

  methods: {
    cancel() {
      this.to("/rank/users");
    },
    to(path) {
      path = typeof path === "string" ? { path } : path;
      if (path) {
        this.$router.push(path);
      }
    },
    onRefresh() {
      getRankUsers(api).then(data => {
        this.$store.commit("SAVE_RANK_DATA", { name: this.vuex, data });
        this.$refs.loadmore.topEnd(false);
      });
    },
    onLoadMore() {
      getRankUsers(api, {
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
