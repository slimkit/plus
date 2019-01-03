<template>
  <div class="c-topic-selector">
    <FormSelectItem
      v-if="!topics.length"
      :label="$t('release.add_topic')"
      @click="$refs.topicPanel.open()"
    />
    <ul v-else class="topic-select">
      <li
        v-for="topic in topics"
        :key="`selected-topic-${topic.id}`"
        class="topic-item"
        @click="deleteTopic(topic)"
      >
        <svg v-if="!topic.readonly" class="m-style-svg m-svg-small"><use xlink:href="#icon-delete" /></svg>
        {{ topic.name }}
      </li>
      <li
        v-if="topics.length < 5"
        class="topic-add"
        @click="$refs.topicPanel.open()"
      >
        <svg class="m-style-svg m-svg-small"><use xlink:href="#icon-plus" /></svg>
      </li>
    </ul>

    <TopicSearchPanel ref="topicPanel" @select="addTopic" />
  </div>
</template>

<script>
import _ from 'lodash'
import TopicSearchPanel from '@/page/topic/components/TopicSearchPanel.vue'

export default {
  name: 'TopicSelector',
  components: {
    TopicSearchPanel,
  },
  props: {
    value: { type: Array, default: () => [] },
  },
  computed: {
    topics: {
      get () {
        return this.value
      },
      set (val) {
        this.$emit('input', val)
      },
    },
  },
  methods: {
    addTopic (topic) {
      this.topics = _.unionBy(this.topics, [topic], 'id')
    },
    deleteTopic (topic) {
      if (topic.readonly) return
      this.topics = this.topics.filter(item => topic.id !== item.id)
    },
  },
}
</script>

<style lang="less" scoped>
.c-topic-selector {
  .c-form-item {
    color: inherit;
  }

  .topic-select {
    display: flex;
    flex-wrap: wrap;
    padding: 30px 20px;
    border-bottom: 1px solid @border-color;

    > li {
      display: inline-flex;
      align-items: center;
      height: 50px;
      margin-left: 30px;
      margin-top: 20px;
      border-radius: 8px;
    }

    .topic-item {
      position: relative;
      padding: 0 16px;
      background-color: #cdcdcd;
      font-size: 24px;
      color: #fff;

      .m-svg-small {
        position: absolute;
        top: -16px;
        left: -16px;
        width: 32px;
        height: 32px;
        padding: 6px;
        background-color: #999;
        border-radius: 20px;
      }
    }

    .topic-add {
      padding: 0 30px;
      border: 1px solid @border-color;

      .m-svg-small {
        width: 30px;
        height: 30px;
        color: @text-color3;
      }
    }
  }
}
</style>
