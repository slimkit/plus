<template>
  <div class="m-art-rewards">
    <CommonHeader>打赏列表</CommonHeader>

    <main>
      <JoLoadMore
        ref="loadmore"
        :auto-load="false"
        @onRefresh="onRefresh"
        @onLoadMore="onLoadMore"
      >
        <section
          v-for="({ user, id, created_at }, index) in rewards"
          :key="`reward-${id}-${index}`"
          class="m-box m-aln-center m-justify-bet m-art-reward-item m-bb1 m-main"
        >
          <Avatar :user="user" size="tiny" />

          <h2 class="m-box m-flex-grow1 m-flex-shrink1 m-text-cut"><b>{{ user.name }}</b>打赏了{{ typeMap[type] }}</h2>

          <time :datetime="created_at" class="m-flex-grow0 m-flex-shrink0">{{ created_at | time2tips }}</time>
        </section>
      </JoLoadMore>
    </main>
  </div>
</template>

<script>
import { limit } from '@/api'
import i18n from '@/i18n'

const typeMap = {
  feed: i18n.t('feed.name'),
  news: i18n.t('news.name'),
  post: i18n.t('group.post.name'),
  answer: i18n.t('question.answer.name'),
}

export default {
  name: 'ArticleRewardList',
  data () {
    return {
      rewards: this.$store.state.article.rewarders || [],
      typeMap,
    }
  },
  computed: {
    type () {
      return this.$route.meta.type
    },
    article () {
      return this.$route.params.article
    },
    url () {
      // 动态 GET /feeds/{feed}/rewards
      // 资讯 GET /news/{news}/rewards
      // 帖子 GET /plus-group/group-posts/:post/rewards
      let result
      switch (this.type) {
        case 'feed':
          result = `/feeds/${this.article}/rewards`
          break
        case 'news':
          result = `/news/${this.article}/rewards`
          break
        case 'post':
          result = `/plus-group/group-posts/${this.article}/rewards`
          break
        case 'answer':
          result = `/question-answers/${this.article}/rewarders`
          break
      }
      return result
    },
  },

  mounted () {
    this.$refs.loadmore.beforeRefresh()
  },
  methods: {
    onRefresh () {
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
        .get(this.url, { params: { limit } })
        .then(({ data = [] }) => {
          this.rewards = data
          this.$refs.loadmore.afterRefresh(data.length < limit)
        })
        .catch(() => {
          this.$refs.loadmore.afterRefresh(true)
        })
    },
    onLoadMore () {
      this.$http
        .get(this.url, { params: { limit, offset: this.rewards.length } })
        .then(({ data = [] }) => {
          this.rewards = [...this.rewards, ...data]
          this.$refs.loadmore.afterLoadmore(data.length < limit)
        })
        .catch(() => {
          this.$refs.loadmore.afterLoadmore(true)
        })
    },
  },
}
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
