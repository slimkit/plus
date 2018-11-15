<template>
  <div :class="`${prefixCls}`">

    <common-header>收到的赞</common-header>

    <div :class="`${prefixCls}-container`">
      <jo-load-more
        ref="loadmore"
        :class="`${prefixCls}-loadmore`"
        @onRefresh="onRefresh"
        @onLoadMore="onLoadMore" >
        <div
          v-for="like in likes"
          v-if="like.id"
          :class="`${prefixCls}-item`"
          :key="like.id">
          <component :is="items[like.likeable_type]" :like="like"/>
        </div>
      </jo-load-more>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import { resetUserCount } from "@/api/message.js";
import feedItem from "../children/likes/feedItem";
import newsItem from "../children/likes/newsItem";
import productItem from "../children/likes/productItem";
import groupPostItem from "../children/likes/groupPostItem";
import questionAnswerItem from "../children/likes/questionAnswerItem";

const prefixCls = "msgList";
const items = {
  news: newsItem,
  feeds: feedItem,
  product: productItem,
  "group-posts": groupPostItem,
  "question-answers": questionAnswerItem
};

export default {
  name: "MyLikes",
  data: () => ({
    prefixCls,
    refreshData: [],
    items
  }),
  computed: {
    ...mapState({
      likes: state => state.MESSAGE.MY_LIKED || []
    })
  },
  watch: {
    refreshData(val) {
      if (val.length > 0) {
        this.$store.commit("SAVE_MY_LIKED", { type: "new", data: val });
      }
    }
  },
  mounted() {
    this.$refs.loadmore.beforeRefresh();
  },
  methods: {
    // 刷新服务
    onRefresh() {
      this.refreshData = [];
      this.$http
        .get("/user/likes", {
          validateStatus: s => s === 200
        })
        .then(({ data }) => {
          // 判断与现有数据的id的对比,加入新数据
          if (data.length > 0) {
            this.refreshData = data;
          }
          resetUserCount("liked");
          this.$refs.loadmore.afterRefresh(data.length < 15);
        });
    },

    // loadmore
    onLoadMore() {
      const { id = 0 } = this.likes.slice(-1)[0] || {};
      this.$http
        .get(
          "/user/likes",
          { params: { after: id } },
          { validateStatus: s => s === 200 }
        )
        .then(({ data }) => {
          if (data.length === 0) {
            this.$refs.loadmore.afterLoadMore(true);
            return false;
          }
          this.$store.commit("SAVE_MY_LIKED", { type: "more", data });
          this.$refs.loadmore.afterLoadMore(data.length < 15);
        });
    }
  }
};
</script>

<style lang="less">
@import url("../style.less");
</style>
