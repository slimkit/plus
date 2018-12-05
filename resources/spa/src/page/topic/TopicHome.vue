<template>
  <div class="p-topic-home">
    <CommonHeader class="header">
      <nav class="type-switch-bar">
        <span :class="{active: currentType === 'hot'}" @click="currentType = 'hot'">
          热门
        </span>
        <span :class="{active: currentType === 'new'}" @click="currentType = 'new'">
          最新
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
        <ul class="topic-list">
          <TopicCard
            v-for="topic in list"
            :key="topic.id"
            class="topic-item"
            :topic="topic"
          />
        </ul>
      </JoLoadMore>
    </main>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import TopicCard from './components/TopicCard'

export default {
  name: 'TopicHome',
  components: {
    TopicCard,
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
        return this.$route.query.type || 'hot'
      },
      set (type) {
        this.$router.replace({
          path: this.$route.path,
          query: { type: type },
        })
        if (!this[type].length) this.onRefresh()
      },
    },
  },
  created () {
    if (!this.$route.query.type) this.currentType = 'hot'
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

  .type-switch-bar {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 90px;

    > span {
      display: inline-block;
      height: 100%;
      margin: 0 20px;
      padding: 22px 12px;
      color: #999;
      transition: 0.3s;

      &.active {
        color: #333;
        border-bottom: 2px solid @primary; /* no */
      }
    }
  }

  > main {
    padding: 30px;
  }

  .topic-list {
    display: flex;
    flex-direction: column;

    .topic-item {
      width: 100%;
      height: 300px;
      margin-bottom: 30px;
    }
  }
}
</style>
