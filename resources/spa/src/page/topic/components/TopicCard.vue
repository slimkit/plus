<template>
  <li
    class="c-topic-card"
    :style="{'background-image': `url(${logo})`}"
    @click="gotoDetail"
  >
    <h2 class="title">{{ topic.name }}</h2>
  </li>
</template>

<script>
export default {
  name: 'TopicCard',
  props: {
    topic: { type: Object, required: true },
  },
  computed: {
    logo () {
      const { logo = {} } = this.topic
      return logo.url || require('@/images/default_topic.jpg')
    },
  },
  methods: {
    gotoDetail () {
      this.$router.push({
        name: 'TopicDetail',
        params: { topicId: this.topic.id },
      })
    },
  },
}
</script>

<style lang="less" scoped>
.c-topic-card {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  background: no-repeat center;
  background-size: cover;
  border-radius: 10px;
  overflow: hidden;

  &::before {
    content: '';
    position: absolute;
    display: block;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background-color: rgba(0,0,0, .2);
    z-index: 0;
  }

  .title {
    position: relative;
    color: #fff;
    font-size: 42px;
    letter-spacing: 8px;
    z-index: 1;
  }
}
</style>
