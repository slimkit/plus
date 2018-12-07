<template>
  <div class="p-topic-search">
    <SearchBar v-model="keyword" placeholder="搜索话题" />

    <main>
      <JoLoadMore
        ref="loadmore"
        :auto-load="false"
        :show-bottom="keyword != ''"
        @onRefresh="onRefresh"
        @onLoadMore="onLoadMore"
      >
        <TopicList :topics="list" />
      </JoLoadMore>
    </main>
  </div>
</template>

<script>
import _ from 'lodash'
import * as api from '@/api/topic'
import SearchBar from '@/components/common/SearchBar.vue'
import TopicList from './components/TopicList'

export default {
  name: 'TopicSearch',
  components: {
    SearchBar,
    TopicList,
  },
  data () {
    return {
      keyword: '',
      list: [],
    }
  },
  watch: {
    keyword (val) {
      if (val) {
        this.$refs.loadmore.beforeRefresh()
        this.onRefresh()
      } else {
        this.list = []
      }
    },
  },
  methods: {
    onRefresh: _.debounce(async function () {
      const { data } = await api.getTopicList({ q: this.keyword })
      this.list = data
      this.$refs.loadmore.afterRefresh(data.length < 15)
    }, 650),
    async onLoadMore () {
      const lastTopic = [...this.list].pop() || {}
      const { data } = await api.getTopicList({ q: this.keyword, index: lastTopic.id })
      this.list.push(...data)
      this.$refs.loadmore.afterRefresh(data.length < 15)
    },
  },
}
</script>
