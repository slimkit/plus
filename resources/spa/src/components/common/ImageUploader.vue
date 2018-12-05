<template>
  <div class="c-image-uploader">
    <input
      ref="imagefile"
      type="file"
      class="hidden"
      accept="image/jpeg,image/webp,image/jpg,image/png,image/bmp"
      @change="selectPhoto"
    >
  </div>
</template>

<script>
import { hashFile } from '@/util/SendImage.js'
import { baseURL } from '@/api'
import * as uploadApi from '@/api/upload.js'
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
  name: 'ImageUploader',
  props: {
    /**
     * 文件类型
     * @param {string} type enum{id: FileID, blob: Blob}
     */
    type: {
      type: String,
      default: 'id',
      validator (type) {
        return ['blob', 'id', 'url', 'storage'].includes(type)
      },
    },
    value: { type: null, default: null },
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
  watch: {
    avatar (src) {
      this.$emit('update:src', src)
    },
  },
  methods: {
    select () {
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
        const file = new File([blob], this.filename, {
          type: blob.type,
          lastModified: new Date(),
        })
        const hash = await hashFile(file)
        const params = {
          filename: this.filename,
          hash,
          size: blob.size,
          mime_type: blob.type || 'image/png',
          storage: { channel: 'public' },
        }
        const result = await uploadApi.createUploadTask(params)
        uploadApi
          .uploadImage({
            method: result.method,
            url: result.uri,
            headers: result.headers,
            blob,
          })
          .then(data => {
            this.$emit('input', data.node)
          })
          .catch(() => {
            this.$Message.error('文件上传失败，请检查文件系统配置')
          })
      }
    },
  },
}
</script>

<style lang="less" scoped>
.c-image-uploader {
  .hidden {
    display: none;
  }
}
</style>
