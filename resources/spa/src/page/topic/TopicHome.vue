<template>
  <div class="p-topic-home">
    <CommonHeader :pinned="true" class="header">
      <nav class="type-switch-bar">
        <span :class="{active: currentType === 'hot'}" @click="currentType = 'hot'">
          {{ $t('hot') }}
        </span>
        <span :class="{active: currentType === 'new'}" @click="currentType = 'new'">
          {{ $t('newest') }}
        </span>
      </nav>
      <div slot="right" class="buttons">
        <RouterLink
          tag="svg"
          :to="{name:'TopicSearch'}"
          class="m-style-svg m-svg-def"
        >
          <use xlink:href="#icon-search" />
        </RouterLink>
        <RouterLink
          tag="svg"
          :to="{name:'TopicCreate'}"
          class="m-style-svg m-svg-def"
        >
          <use xlink:href="#icon-topic-create" />
        </RouterLink>
      </div>
    </CommonHeader>

    <main>
      <JoLoadMore
        ref="loadmore"
        :show-bottom="currentType !== 'hot'"
        @onRefresh="onRefresh"
        @onLoadMore="onLoadMore"
      >
        <TopicList :topics="list" />
      </JoLoadMore>
    </main>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import TopicList from './components/TopicList'

export default {
  name: 'TopicHome',
  components: {
    TopicList,
  },
  computed: {
    ...mapState('topic', {
      hot: 'hotList',
      new: 'newList',
    }),
    list () {
      let type = this.currentType
      return this[type]
    },
    currentType: {
      get () {
        return this.$route.query.type
      },
      set (type) {
        this.$router.replace({
          path: this.$route.path,
          query: { type: type },
        })
      },
    },
  },
  watch: {
    currentType () {
      this.$refs.loadmore.beforeRefresh()
    },
  },
  created () {
    if (!this.$route.query.type) this.currentType = 'hot'
  },
  mounted () {
    this.$refs.loadmore.beforeRefresh()
  },
  methods: {
    ...mapActions('topic', [
      'fetchTopicList',
    ]),
    onRefresh () {
      this.fetchTopicList({ type: this.currentType, reset: true })
        .then(more => void this.$refs.loadmore.afterRefresh(more))
    },
    onLoadMore () {
      const lastTopic = [...this.list].pop() || {}
      this.fetchTopicList({ type: this.currentType, params: { index: lastTopic.id } })
        .then(more => void this.$refs.loadmore.afterLoadMore(more))
    },
  },
}
</script>

<style lang="less" scoped>
.p-topic-home {
  .header {
    overflow: initial;
  }
}
</style>
