<template>
  <div class="msgList">

    <common-header>收到的评论</common-header>

    <div class="msgList-container">
      <jo-load-more
        ref="loadmore"
        class="msgList-loadmore"
        @onRefresh="onRefresh"
        @onLoadMore="onLoadMore">
        <div
          v-for="comment in comments"
          :key="`comment-key-${comment.id}`"
          class="msgList-item">
          <component :is="items[comment.commentable_type]" :comment="comment"/>
        </div>
      </jo-load-more>
    </div>
  </div>
</template>

<script>
/**
 * 消息-评论列表
 */
import { mapState } from "vuex";
import { resetUserCount } from "@/api/message.js";
import feedItem from "../children/comments/feedItem";
import newsItem from "../children/comments/newsItem";
import productItem from "../children/comments/productItem";
import questionItem from "../children/comments/questionItem";
import groupPostItem from "../children/comments/groupPostItem";
import questionAnswerItem from "../children/comments/questionAnswerItem";

const prefixCls = "msgList";
const items = {
  news: newsItem,
  feeds: feedItem,
  product: productItem,
  questions: questionItem,
  "group-posts": groupPostItem,
  "question-answers": questionAnswerItem
};
const commentType = {
  feeds: {
    title: "动态",
    url: "/feed/"
  },
  "question-answers": {
    title: "回答",
    url: "/question-answers/"
  },
  news: {
    title: "头条",
    url: "/news/"
  },
  questions: {
    title: "问题",
    url: "/questions/"
  }
};
export default {
  name: "MyComments",
  data: () => ({
    prefixCls,
    commentType,
    items,
    refreshData: []
  }),
  computed: {
    ...mapState({
      comments: state => state.MESSAGE.MY_COMMENTED
    })
  },
  watch: {
    refreshData(data) {
      if (data.length > 0) {
        this.$store.commit("SAVE_MY_COMMENTED", {
          type: "new",
          data
        });
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
        .get("/user/comments", {
          validateStatus: s => s === 200
        })
        .then(({ data }) => {
          resetUserCount("commented");
          if (data.length > 0) {
            this.refreshData = data;
          }
          this.$nextTick(() => {
            this.$refs.loadmore.afterRefresh(data.length < 15);
          });
        });
    },

    // loadmore
    onLoadMore() {
      const { id = 0 } = this.comments.slice(-1)[0] || {};
      this.$http
        .get(
          "/user/comments",
          { params: { after: id } },
          { validateStatus: s => s === 200 }
        )
        .then(({ data }) => {
          this.$store.commit("SAVE_MY_COMMENTED", {
            type: "more",
            data
          });
          this.$nextTick(() => {
            this.$refs.loadmore.afterLoadMore(data.length < 15);
          });
        });
    }
  }
};
</script>

<style lang="less" >
@import url("../style.less");
</style>
