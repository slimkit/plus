<template>
  <div class="p-news">
    <CommonHeader class="common-header">
      {{ $t('news.name') }}
      <template slot="right">
        <svg class="m-style-svg m-svg-def" @click="$router.push({path: '/news/search'})">
          <use xlink:href="#icon-search" />
        </svg>
        <svg class="m-style-svg m-svg-def" @click="beforeCreatePost">
          <use xlink:href="#icon-news-draft" />
        </svg>
      </template>
    </CommonHeader>

    <NewsFilter v-if="isLogin" @change="onCateChange" />

    <JoLoadMore
      ref="loadmore"
      :class="{guest: !isLogin}"
      class="loadmore"
      @onRefresh="onRefresh"
      @onLoadMore="onLoadMore"
    >
      <template v-for="card in newsList">
        <NewsCard
          v-if="card.author"
          :key="`news${card.id}`"
          :current-cate="currentCate"
          :news="card"
        />
      </template>
    </JoLoadMore>
  </div>
</template>

<script>
import _ from 'lodash'
import { mapState } from 'vuex'
import NewsCard from './components/NewsCard.vue'
import NewsFilter from './components/NewsFilter.vue'

export default {
  name: 'NewsList',
  components: {
    NewsCard,
    NewsFilter,
  },
  data () {
    return {
      currentCate: 0,
      newsList: [], // 资讯列表
    }
  },
  computed: {
    ...mapState({
      newsVerified: state => state.CONFIG.news.contribute.verified,
      userVerify: state => state.USER_VERIFY || {},
    }),
    after () {
      const len = this.newsList.length
      return len > 0 ? this.newsList[len - 1].id : 0
    },
    isLogin () {
      const user = this.$store.state.CURRENTUSER
      return Object.keys(user).length
    },
  },
  mounted () {
    if (!this.newsList.length) this.$refs.loadmore.beforeRefresh()
    if (this.newsVerified) this.$store.dispatch('FETCH_USER_VERIFY')
  },
  methods: {
    onCateChange ({ id = 0 } = {}) {
      this.newsList = []
      this.currentCate = id
      this.$refs.loadmore.beforeRefresh()
    },
    async onRefresh () {
      // GET /news
      const params =
        this.currentCate === 0
          ? { recommend: 1 }
          : { cate_id: this.currentCate }
      const data = await this.$store.dispatch('news/getNewsList', params)
      this.newsList = data
      this.$refs.loadmore.afterRefresh(data.length < 10)
    },
    async onLoadMore () {
      const params =
        this.currentCate === 0
          ? { recommend: 1 }
          : { cate_id: this.currentCate }
      Object.assign(params, { after: this.after })
      const data = await this.$store.dispatch('news/getNewsList', params)
      this.$refs.loadmore.afterLoadMore(data.length < 10)

      this.newsList = [...this.newsList, ...data]
    },
    /**
     * 投稿前进行认证确认
     */
    beforeCreatePost () {
      // 如果后台设置了不需要验证 或 用户已经认证就直接跳转
      const noNeedVerify =
        !this.$store.state.CONFIG.news.contribute.verified ||
        !_.isEmpty(this.$store.state.CURRENTUSER.verified)
      if (noNeedVerify) return this.$router.push({ path: '/post/release' })
      else if (this.userVerify.status === 0) {
        this.$Message.error(this.$t('certificate.under_review', { name: this.$t('news.name') }))
      } else {
        const actions = [
          {
            text: this.$t('certificate.user.name'),
            method: () =>
              this.$router.push({
                path: '/profile/certificate',
                query: { type: 'user' },
              }),
          },
          {
            text: this.$t('certificate.org.name'),
            method: () =>
              this.$router.push({
                path: '/profile/certificate',
                query: { type: 'org' },
              }),
          },
        ]
        this.$bus.$emit(
          'actionSheet',
          actions,
          this.$t('cancel'),
          this.$t('news.need_certificate')
        )
      }
    },
  },
}
</script>

<style lang="less" scoped>
.p-news {
  .common-header {
    position: fixed;
  }

  .loadmore {
    padding-top: 90+80px;

    &.guest {
      padding-top: 90px;
    }
  }
}
</style>
