<template>
  <div class="c-reference-feed">
    <h5 v-if="noContent">{{ $t('message.not_exist') }}</h5>
    <RouterLink v-else :to="`/feeds/${id}`">
      <AsyncFile
        v-if="cover"
        class="image"
        :file="cover"
        :w="80"
        :h="80"
      >
        <div
          slot-scope="{ src }"
          :class="{video: isVideo}"
          :style="{backgroundImage: `url(${src})`}"
        />
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
    isVideo () {
      return !!this.feed.video
    },
    cover () {
      if (this.isVideo) {
        return this.feed.video.cover_id
      } else if (this.image) {
        return this.image.file
      }
      return null
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

  .video {
    position: relative;

    &::after,
    &::before {
      content: '';
      position: absolute;
      display: block;
    }

    &::after {
      left: 10px;
      top: 10px;
      width: 60px;
      height: 60px;
      border: 1px solid #fff;
      border-radius: 30px;
    }

    &::before {
      left: 35px;
      top: 25px;
      border: 30px solid transparent;
      border-width: 15px 25px;
      border-left-color: #fff;
    }
  }

}
</style>
