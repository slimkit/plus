<template>
  <div
    class="feed-video"
    @click.stop
  >
    <video
      v-if="videoFile"
      :ref="`video_${id}`"
      :poster="coverFile"
      controls
      x-webkit-airplay="true"
      webkit-playsinline="true"
      playsinline="true"
      preload="none"
    >
      <source
        :src="videoFile"
        type="video/mp4"
      >
      Your browser does not support the video tag.
    </video>
  </div>
</template>
<script>
export default {
  name: 'FeedVideo',
  props: {
    video: {
      type: Object,
      required: true,
    },
    id: {
      type: Number,
      required: true,
    },
  },
  data: () => ({
    videoFile: '',
    coverFile: '',
    play: false,
    ratio: 1,
  }),
  computed: {
    height () {
      return this.video.width < this.video.height
        ? 5.18
        : parseInt(this.video.height * this.ratio) / 100
    },
  },
  created () {
    this.ratio = 518 / this.video.width
    this.getVideoUrl()
    this.getCoverUrl()
  },
  methods: {
    getVideoUrl () {
      this.$http
        .get(`/files/${this.video.video_id}?json=1`, {
          validateStatus: s => s === 200,
        })
        .then(({ data: { url = '' } = {} } = {}) => {
          this.videoFile = url
        })
    },
    getCoverUrl () {
      this.$http
        .get(`/files/${this.video.cover_id}?json=1`, {
          validateStatus: s => s === 200,
        })
        .then(({ data: { url = '' } = {} } = {}) => {
          this.coverFile = url
        })
    },
  },
}
</script>
<style lang="less">
.feed-video {
  overflow: hidden;
  width: 518px;
  height: 518px;
  video {
    background-color: #000;
    width: 100%;
    height: 100%;
    z-index: 10;
    display: block;
    margin: auto;
  }
}
</style>
