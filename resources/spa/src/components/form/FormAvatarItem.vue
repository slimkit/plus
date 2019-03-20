<template>
  <section class="c-form-item c-form-avatar-item" @click="beforeSelectFile">
    <div :class="shape" class="avatar-wrap">
      <img :src="avatar" class="m-avatar-img">
    </div>
    <span class="avatar-label">{{ label }}</span>
    <svg v-if="!readonly" class="m-style-svg m-svg-def m-entry-append">
      <use xlink:href="#icon-arrow-right" />
    </svg>

    <ImageUploader
      ref="uploader"
      :value="value"
      type="storage"
      @input="$emit('input', $event)"
      @update:src="avatar = $event"
    />
  </section>
</template>

<script>
import ImageUploader from '@/components/common/ImageUploader'

export default {
  name: 'FormAvatarItem',
  components: { ImageUploader },
  props: {
    value: { type: null, default: () => ({}) },
    label: { type: String, default: '上传头像' },
    readonly: { type: Boolean, default: false },
    /**
     * 文件类型
     */
    type: { type: String, default: 'storage', validator (type) { return ['blob', 'id', 'url', 'storage'].includes(type) } },
    /**
     * 头像形状 square: 方形 circle: 圆形
     */
    shape: { type: String, default: 'circle' },
  },
  data () {
    return {
      avatar: (this.$props.value || {}).url,
    }
  },
  methods: {
    beforeSelectFile () {
      if (this.readonly) return
      this.$refs.uploader.select()
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
