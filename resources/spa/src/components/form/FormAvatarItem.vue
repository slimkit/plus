<template>
  <section class="c-form-item c-form-avatar-item" @click="beforeSelectFile">
    <div :class="shape" class="avatar-wrap">
      <img :src="avatar" class="m-avatar-img">
    </div>
    <span class="avatar-label">{{ label }}</span>
    <svg v-if="!readonly" class="m-style-svg m-svg-def m-entry-append">
      <use xlink:href="#icon-arrow-right" />
    </svg>
    <input
      ref="imagefile"
      type="file"
      class="m-rfile"
      accept="image/jpeg,image/webp,image/jpg,image/png,image/bmp"
      @change="selectPhoto"
    >
  </section>
</template>

<script>
import { baseURL } from '@/api'
import uploadApi from '@/api/upload.js'
import { getFileUrl } from '@/util'
import getFirstFrameOfGif from '@/util/getFirstFrameOfGif.js'

/**
 * Canvas toBlob
 */
if (!HTMLCanvasElement.prototype.toBlob) {
  Object.defineProperty(HTMLCanvasElement.prototype, 'toBlob', {
    value: function (callback, type, quality) {
      const binStr = atob(this.toDataURL(type, quality).split(',')[1])
      const len = binStr.length
      const arr = new Uint8Array(len)

      for (var i = 0; i < len; i++) {
        arr[i] = binStr.charCodeAt(i)
      }

      callback(new Blob([arr], { type: type || 'image/png' }))
    },
  })
}

export default {
  name: 'FormAvatarItem',
  props: {
    value: { type: null, default: null },
    label: { type: String, default: '上传头像' },
    readonly: { type: Boolean, default: false },

    /**
     * 文件类型
     * @param {string} type enum{id: FileId, blob: Blob}
     */
    type: {
      type: String,
      default: 'id',
      validator (type) {
        return ['blob', 'id', 'url', 'storage'].includes(type)
      },
    },

    /**
     * 头像形状 square: 方形 circle: 圆形
     */
    shape: { type: String, default: 'circle' },
  },
  data () {
    return {
      avatarBlob: null,
    }
  },
  computed: {
    avatar () {
      if (!this.value) return null
      if (typeof this.value === 'string' && this.value.match(/^https?:/)) { return this.value }
      switch (this.type) {
        case 'id':
          return `${baseURL}/files/${this.value}`

        case 'blob':
          return getFileUrl(this.value)

        case 'storage':
          // 获取初始头像资源
          if (typeof this.value !== 'string') return this.value.url || null
          // 否则获取修改过后的 blob 对象资源
          return getFileUrl(this.avatarBlob)

        default:
          return null
      }
    },
    filename () {
      return this.$refs.imagefile.files[0].name
    },
  },
  methods: {
    beforeSelectFile () {
      if (this.readonly) return
      this.$refs.imagefile.click()
    },
    async selectPhoto (e) {
      let files = e.target.files || e.dataTransfer.files
      if (!files.length) return
      const cropperURL = await getFirstFrameOfGif(files[0])
      this.$ImgCropper.show({
        url: cropperURL,
        round: false,
        onCancel: () => {
          this.$refs.imagefile.value = null
        },
        onOk: screenCanvas => {
          screenCanvas.toBlob(async blob => {
            this.uploadBlob(blob)
            this.$refs.imagefile.value = null
          }, 'image/png')
        },
      })
    },

    async uploadBlob (blob) {
      if (this.type === 'id') {
        // 如果需要得到服务器文件接口返回的 ID
        const formData = new FormData()
        formData.append('file', blob)
        const id = await this.$store.dispatch('uploadFile', formData)
        this.$Message.success('头像上传成功')
        this.$emit('input', id)
      } else if (this.type === 'blob') {
        // 如果需要 Blob 对象
        this.$emit('input', blob)
      } else if (this.type === 'storage') {
        // 如果需要新文件存储方式上传
        this.avatarBlob = blob
        const file = new File([blob], this.filename, { type: blob.type, lastModified: new Date() })
        const node = await uploadApi(file)
        this.$emit('input', node)
      }
    },
  },
}
</script>

<style lang="less" scoped>
@import url("./formItem.less");

form .c-form-avatar-item {
  height: 160px;
  border-bottom: 1px solid @border-color !important; /* no */
  padding-right: 20px;

  .avatar-wrap {
    flex: none;
    width: 90px;
    height: 90px;
    background-color: #ededed;
    overflow: hidden;

    &.circle {
      border-radius: 100%;
    }

    > img {
      width: 100%;
    }
  }

  .avatar-label {
    flex: auto;
    margin-left: 30px;
    color: #333;
  }
}
</style>
