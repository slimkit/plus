<template>
  <RouterLink class="c-reference-feed" :to="`/feeds/${id}`">
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
    fetchFeed () {
      api.getFeed(this.id)
        .then(({ data: feed }) => {
          this.feed = feed
        })
    },
  },
}
</script>

<style lang="less" scoped>
.c-reference-feed {
  display: flex;
  align-items: center;
  padding: 15px 20px;
  background-color: #f4f5f5;
  color: #999;
  font-size: 26px;

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
