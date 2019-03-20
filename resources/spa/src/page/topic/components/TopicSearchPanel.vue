<template>
  <Transition name="pop">
    <div v-if="show || visible" class="c-topic-search-panel">
      <SearchBar
        v-model="keyword"
        :back="onCancel"
        :placeholder="$t('feed.topic.search')"
      />

      <main>
        <JoLoadMore
          ref="loadmore"
          :auto-load="false"
          :show-bottom="Boolean(keyword && list.length)"
          @onRefresh="onRefresh"
          @onLoadMore="onLoadMore"
        >
          <template v-if="!keyword || list.length">
            <div v-show="!list.length" class="title-bar">{{ $t('feed.topic.hot') }}</div>
            <ul class="search-list">
              <li
                v-for="topic in topics"
                :key="topic.id"
                class="item"
                @click="onSelect(topic)"
                v-html="highlight(topic.name)"
              />
            </ul>
          </template>
          <div v-else class="m-no-find" />
        </JoLoadMore>
      </main>
    </div>
  </Transition>
</template>

<script>
import { mapState } from 'vuex'
import { limit } from '@/api'
import * as api from '@/api/topic'
import SearchBar from '@/components/common/SearchBar.vue'

export default {
  name: 'TopicSearchPanel',
  components: {
    SearchBar,
  },
  props: {
    show: { type: Boolean, default: false },
  },
  data () {
    return {
      visible: false,
      keyword: '',
      list: [],
    }
  },
  computed: {
    ...mapState('topic', {
      hotTopics: 'hotList',
    }),
    topics () {
      return this.list.length ? this.list : this.hotTopics
    },
  },
  watch: {
    keyword (val) {
      if (val) {
        this.$refs.loadmore.beforeRefresh()
        this.onRefresh()
      } else {
        this.$refs.loadmore.afterRefresh(true)
        this.list = []
      }
    },
  },
  mounted () {
    if (!this.hotTopics.length) this.$store.dispatch('topic/fetchTopicList', { type: 'hot' })
  },
  methods: {
    async onRefresh () {
      const { data } = await api.getTopicList({ q: this.keyword })
      this.list = data
      this.$refs.loadmore.afterRefresh(data.length < limit)
    },
    async onLoadMore () {
      const lastTopic = [...this.list].pop() || {}
      const { data } = await api.getTopicList({ q: this.keyword, index: lastTopic.id })
      this.list.push(...data)
      this.$refs.loadmore.afterRefresh(data.length < limit)
    },
    highlight (name) {
      const regex = new RegExp(`(${this.keyword})`, 'ig')
      return name.replace(regex, '<span class="primary">$1</span>')
    },
    open () {
      this.visible = true
      this.keyword = ''
    },
    close () {
      this.visible = false
    },
    onCancel () {
      this.$emit('cancel')
      this.close()
    },
    onSelect (topic) {
      this.$emit('select', topic)
      this.close()
    },
  },
}
</script>

<style lang="less" scoped>
.c-topic-search-panel {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background-color: #fff;
  z-index: 10;

  .title-bar {
    padding: 20px 30px;
    font-size: 26px;
    background-color: #ededed;
    color: @text-color3;
  }

  .search-list {
    border-top: 1px solid @border-color; /* no */
    margin-top: -1px; /* no */

    .item {
      padding: 30px;
      border-bottom: 1px solid @border-color; /* no */
    }
  }

  .m-no-find {
    height: 800px;
  }
}
</style>

<style lang="less">
.c-topic-search-panel {
  .primary {
    color: @primary;
  }
}
</style>
