<template>
  <div class="p-feed">
    <CommonHeader :pinned="true" class="header">
      <span slot="left" />
      <nav class="type-switch-bar">
        <span :class="{active: feedType === 'new'}" @click="feedType = 'new'"> {{ $t('newest') }} </span>
        <span :class="{active: feedType === 'hot'}" @click="feedType = 'hot'"> {{ $t('hot') }} </span>
        <span :class="{active: feedType === 'follow'}" @click="feedType = 'follow'"> {{ $t('follow.name') }} </span>
      </nav>
    </CommonHeader>

    <main class="p-feed-main">
      <JoLoadMore
        ref="loadmore"
        @onRefresh="onRefresh"
        @onLoadMore="onLoadMore"
      >
        <ul v-gif-play class="feed-list">
          <li
            v-for="(feed, index) in pinned"
            v-if="feed.id"
            :key="`pinned-feed-${feedType}-${feed.id}-${index}`"
            :data-feed-id="feed.id"
          >
            <FeedCard :feed="feed" :pinned="true" />
          </li>
          <li
            v-for="(card, index) in feeds"
            :key="`feed-${feedType}-${card.id}-${index}`"
            :data-feed-id="card.id"
          >
            <FeedCard v-if="card.user_id" :feed="card" />
            <FeedAdCard v-if="card.space_id" :ad="card" />
          </li>
        </ul>
      </JoLoadMore>
    </main>

    <FootGuide />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { limit } from '@/api'
import FeedCard from '@/components/FeedCard/FeedCard.vue'

const feedTypesMap = ['new', 'hot', 'follow']

export default {
  name: 'FeedList',
  components: { FeedCard },
  computed: {
    ...mapGetters('feed', ['hot', 'new', 'follow', 'pinned']),
    feeds () {
      return this[this.feedType]
    },
    feedType: {
      get () {
        return this.$route.query.type || 'hot'
      },
      set (val) {
        const { query } = this.$route
        this.$router.replace({ query: { ...query, type: val } })
      },
    },
    after () {
      const len = this.feeds.length
      if (!len) return 0
      if (this.feedType !== 'hot') return this.feeds[len - 1].id // after
      return this.feeds[len - 1].hot // offset
    },
  },
  watch: {
    feedType (val) {
      if (feedTypesMap.includes(val)) {
        this.$refs.loadmore.beforeRefresh()
      }
    },
  },
  activated () {
    if (this.$route.query.refresh) {
      this.$refs.loadmore.beforeRefresh()
    }
  },
  methods: {
    async onRefresh () {
      const type = this.feedType.replace(/^\S/, s => s.toUpperCase())
      const action = `feed/get${type}Feeds`
      let data
      try {
        data = await this.$store.dispatch(action, { refresh: true })
        this.$refs.loadmore.afterRefresh(data.length < limit)
      } catch (error) {
        this.$refs.loadmore.afterRefresh()
      }
    },
    async onLoadMore () {
      const type = this.feedType.replace(/^\S/, s => s.toUpperCase())
      const action = `feed/get${type}Feeds`
      const data = await this.$store.dispatch(action, { after: this.after })
      this.$refs.loadmore.afterLoadMore(data.length < limit)
    },
  },
}
</script>

<style lang="less" scoped>
.p-feed {
  .feed-list > li + li {
    margin-top: 20px;
  }
}
</style>
