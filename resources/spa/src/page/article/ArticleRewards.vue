<template>
  <div class="m-art-rewards">

    <common-header>打赏列表</common-header>

    <main>
      <jo-load-more
        ref="loadmore"
        :auto-load="false"
        @onRefresh="onRefresh"
        @onLoadMore="onLoadMore">
        <section
          v-for="({ user, id, created_at }, index) in rewards"
          :key="`reward-${id}-${index}`"
          class="m-box m-aln-center m-justify-bet m-art-reward-item m-bb1 m-main">

          <avatar :user="user" size="tiny" />

          <h2 class="m-box m-flex-grow1 m-flex-shrink1 m-text-cut"><b>{{ user.name }}</b>打赏了{{ typeMap[type] }}</h2>

          <time :datetime="created_at" class="m-flex-grow0 m-flex-shrink0">{{ created_at | time2tips }}</time>
        </section>
      </jo-load-more>
    </main>

  </div>
</template>

<script>
export default {
  name: "ArticleRewards",
  data() {
    return {
      rewards: [],
      typeMap: {
        feed: "动态",
        news: "资讯",
        post: "帖子",
        answer: "回答"
      }
    };
  },
  computed: {
    type() {
      return this.$route.meta.type;
    },
    article() {
      return this.$route.params.article;
    },
    url() {
      // 动态 GET /feeds/{feed}/rewards
      // 资讯 GET /news/{news}/rewards
      // 帖子 GET /plus-group/group-posts/:post/rewards

      switch (this.type) {
        case "feed":
          return `/feeds/${this.article}/rewards`;
        case "news":
          return `/news/${this.article}/rewards`;
        case "post":
          return `/plus-group/group-posts/${this.article}/rewards`;
        case "answer":
          return `/question-answers/${this.article}/rewarders`;
      }
    }
  },

  mounted() {
    this.$refs.loadmore.beforeRefresh();
  },
  methods: {
    onRefresh(callback) {
      /**
       * 刷新列表
       *
       *    名称          类型         说明
            limit         integer     默认 15 ，数据返回条数 默认为15
            offset        integer     默认 0 ，翻页标识。
            order         string      默认 desc, asc 升序 desc 降序
            order_type    string      默认 date, amount 打赏金额 date 打赏时间
       */
      this.$http
        .get(this.url, {
          params: {
            limit: 15
          }
        })
        .then(({ data = [] }) => {
          this.rewards = data;
          callback(data.length < 15);
        })
        .catch(() => {
          callback(true);
        });
    },
    onLoadMore(callback) {
      this.$http
        .get(this.url, {
          params: {
            limit: 15,
            offset: this.rewards.length
          }
        })
        .then(({ data = [] }) => {
          this.rewards = [...this.rewards, ...data];
          callback(data.length < 15);
        })
        .catch(() => {
          callback(true);
        });
    }
  }
};
</script>

<style lang="less" scoped>
.m-art-reward-item {
  padding: 30px 20px;
  color: #999;

  time {
    font-size: 24px;
    color: #ccc;
  }

  h2 {
    font-size: 30px;
    margin-left: 30px;
    margin-right: 30px;

    b {
      color: #000;
      margin-right: 10px;
    }
  }
}
</style>
