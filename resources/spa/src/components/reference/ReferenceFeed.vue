<template>
  <div class="c-reference-feed">
    <h5 v-if="noContent">该动态不存在或已被删除</h5>
    <RouterLink v-else :to="`/feeds/${id}`">
      <AsyncFile
        v-if="image"
        class="image"
        :file="image.file"
        :w="80"
        :h="80"
      >
        <div slot-scope="props" :style="{backgroundImage: `url(${props.src})`}" />
      </AsyncFile>
      <div>{{ feed.feed_content }}</div>
    </RouterLink>
  </div>
</template>

<script>
import * as api from '@/api/feeds'

export default {
  name: 'ReferenceFeed',
  props: {
    id: { type: Number, required: true },
  },
  data () {
    return {
      feed: {},
      noContent: false,
    }
  },
  computed: {
    image () {
      const images = this.feed.images || []
      return images[0]
    },
  },
  mounted () {
    this.fetchFeed()
  },
  methods: {
    async fetchFeed () {
      const { data: feed, status } = await api.getFeed(this.id, { allow404: true })
      if (status === 404) {
        this.noContent = true
      } else {
        this.feed = feed
      }
    },
  },
}
</script>

<style lang="less" scoped>
.c-reference-feed {
  padding: 15px 20px;
  background-color: #f4f5f5;
  color: #999;
  font-size: 26px;

  > a {
    display: flex;
    align-items: center;
    color: #999;
  }

  .image {
    width: 80px;
    height: 80px;
    margin-right: 15px;

    > div {
      width: 100%;
      height: 100%;
      background: no-repeat center / cover;
    }
  }

}
</style>
