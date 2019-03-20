<template>
  <div class="p-topic-search">
    <TopicSearchPanel
      ref="search"
      :show="true"
      @cancel="goBack"
      @select="viewTopicDetail"
    />
  </div>
</template>

<script>
import TopicSearchPanel from './components/TopicSearchPanel'

export default {
  name: 'TopicSearch',
  components: {
    TopicSearchPanel,
  },
  watch: {
    $route (to, from) {
      // 从详情页以外的页面进入搜索页面时清空关键字
      if (to.name === 'TopicSearch' && from.name !== 'TopicDetail') this.$refs.search.open()
    },
  },
  methods: {
    viewTopicDetail (topic) {
      this.$router.push({ name: 'TopicDetail', params: { topicId: topic.id } })
    },
  },
}
</script>

<style lang="less" scoped>
.p-topic-search {
  content: '';
}
</style>
