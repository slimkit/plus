<template>
  <div
    :class="{ loading: posterInternal.loading, error: posterInternal.error }"
    class="m-box m-aln-center m-justify-center m-poster-box"
    @click="addPoster"
  >
    <img
      v-if="posterInternal.src"
      :src="posterInternal.src"
      class="m-poster"
      @load.stop="loadedPoster(posterInternal)"
      @error="posterError"
    >
    <div
      v-else
      class="m-box-model m-aln-center m-justify-center m-lim-width m-poster-placeholder"
    >
      <svg
        viewBox="0 0 24 24"
        class="m-style-svg m-svg-big"
      >
        <path d="M21.8,20.8H2.1c-0.5,0-1.1-0.4-1.1-1V6.3c0-0.5,0.4-1.1,1.1-1.1h4.3L8,3.6c0.1-0.3,0.5-0.4,0.8-0.4H15 c0.3,0,0.5,0.1,0.7,0.3l1.9,1.8h4.3c0.5,0,1.1,0.4,1.1,1.1V20C22.9,20.4,22.5,20.8,21.8,20.8L21.8,20.8z M12,6.6 c-3.4,0-6.1,2.7-6.1,6.1s2.7,6.1,6.1,6.1s6.1-2.7,6.1-6.1S15.4,6.6,12,6.6L12,6.6z M12,16.8c-2.3,0-4.1-1.8-4.1-4.1S9.7,8.6,12,8.6 s4.1,1.8,4.1,4.1S14.3,16.8,12,16.8L12,16.8z" />
      </svg>
      <slot />
    </div>
    <div
      v-if="!posterInternal.error && posterInternal.loading"
      class="fixed-loading"
    >
      <div
        class="u-loading"
        style="height: 58px;width: 58px"
      >
        <svg
          class="loading"
          width="100%"
          height="100%"
          viewBox="0 0 29 29"
        >
          <circle
            class="c1"
            cx="14.5"
            cy="14.5"
            r="12.5"
            fill="none"
            stroke-width="4"
            stroke="#b1b1b1"
          />
          <circle
            class="c2"
            cx="14.5"
            cy="14.5"
            r="12.5"
            fill="none"
            stroke-width="4"
            stroke="#c7c7c7"
          />
        </svg>
      </div>
    </div>

    <input
      id="selectimage"
      ref="imagefile"
      type="file"
      class="m-rfile"
      @change="selectPhoto"
    >
  </div>
</template>

<script>
/**
 * 图片上传组件
 * @typedef {Object} Poster
 * @property {number} id
 * @property {*} src
 * @property {*} type
 * @property {*} file
 * @property {boolean} [error=false]
 * @property {boolean} [loading=false]
 */

import sendImage from '@/util/SendImage.js'

/**
 * @type {Poster}
 */
const defaultPoster = {
  id: '',
  src: '',
  type: '',
  file: [],
  error: false,
  loading: false,
}

export default {
  name: 'ImagePoster',
  props: {
    /**
     * @type {Poster}
     */
    poster: { type: Object, default: () => defaultPoster },
  },
  data () {
    return {
      posterInternal: this.poster, // for one-way data binding
    }
  },
  methods: {
    addPoster () {
      if (this.posterInternal.loading) return
      this.$refs.imagefile.click()
    },
    selectPhoto () {
      const files = this.$refs.imagefile.files
      if (files && files.length > 0) {
        const posterObj = {
          loading: true,
          file: files[0],
          type: files[0].mimeType,
          src: window.URL.createObjectURL(files[0]),
        }
        this.posterInternal = Object.assign({}, defaultPoster, posterObj)
      }
    },
    /**
     * @param {Poster} poster
     */
    loadedPoster (poster) {
      const file = poster.file
      if (!file) return
      sendImage(file)
        .then(id => {
          Object.assign(poster, {
            id,
            file: null,
            loading: false,
            error: false,
          })
          this.$emit('uploaded', poster)
        })
        .catch(() => {
          poster.error = true
          poster.loading = false
        })
    },
    posterError () {
      this.$Message.error('图片上传失败, 请重试')
      this.$emit('error')
    },
  },
}
</script>

<style lang="less" scoped>
.m-poster-box {
  position: relative;
  background: #f4f5f5;
  max-width: 100%;
  max-height: 40vh;
  width: 85px * 10;
  height: 54px * 10;
  border: 1px solid @border-color; /* no */
  margin: 20px auto;

  &.loading,
  &.error {
    img {
      opacity: 0.3;
    }
  }

  &.error:after {
    content: "上传失败, 请重试";
    color: @error;
    z-index: 9;
  }
}

.m-poster {
  position: absolute;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
  -o-object-fit: cover;
  object-fit: cover;
  -webkit-transition: all 0.4s ease;
  transition: all 0.4s ease;
}

.m-poster-placeholder {
  line-height: 1.5;
  color: #ccc;
  font-size: 90%;
}
</style>
