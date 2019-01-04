<template>
  <ul class="c-topic-list">
    <TopicCard
      v-for="topic in topics"
      :key="topic.id"
      class="topic-item"
      :topic="topic"
    />
  </ul>
</template>

<script>
import TopicCard from './TopicCard'

export default {
  name: 'TopicList',
  components: { TopicCard },
  props: {
    topics: { type: Array, default: () => [] },
  },
}
</script>

<style lang="less" scoped>
.c-topic-list {
  display: flex;
  flex-direction: column;
  padding: 20px;

  .topic-item {
    width: 100%;
    height: 300px;
    margin-bottom: 30px;
  }
}
</style>
