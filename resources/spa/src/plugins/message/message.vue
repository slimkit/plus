<template>
  <Transition
    :name="transitionName"
    @enter="handleEnter"
    @leave="handleLeave"
  >
    <div :class="classes">
      <svg :class="[`${prefixCls}-icon`]">
        <use :xlink:href="`#icon-message-${icon}`" />
      </svg>
      <span class="ellipsis">{{ content | plusMessageAnalyze(defaultMessage) }}</span>
    </div>
  </Transition>
</template>
<script>
const prefixCls = 'v-msg'
export default {
  props: {
    type: { type: String, default: 'message' },
    duration: { type: Number, default: 3 },
    closable: { type: Boolean, default: false },
    transitionName: { type: String, default: '' },
    icon: { type: String, default: 'message-success' },
    content: { type: [Object, Array], required: true },
    onClose: { type: Function, default: () => {} },
    name: { type: String, required: true },
  },
  data () {
    return {
      prefixCls,
    }
  },
  computed: {
    classes () {
      return [prefixCls, `${prefixCls}__${this.type}`]
    },
    /**
     *  Default message.
     *
     * @return {string}
     * @author Seven Du <shiweidu@outlook.com>
     */
    defaultMessage () {
      if (this.type === 'success') {
        return '成功！'
      }

      return '发生错误了，请刷新重试！'
    },
  },
  mounted () {
    this.clearCloseTimer()
    if (this.duration !== 0) {
      this.closeTimer = setTimeout(() => {
        this.close()
      }, this.duration * 1000)
    }
  },
  beforeDestroy () {
    this.clearCloseTimer()
  },
  methods: {
    clearCloseTimer () {
      if (this.closeTimer) {
        clearTimeout(this.closeTimer)
        this.closeTimer = null
      }
    },
    close () {
      this.clearCloseTimer()
      this.onClose()
      this.$parent.close(this.name)
    },
    handleEnter () {},
    handleLeave () {},
  },
}
</script>
<style lang='less' src='./style/message.less'>
</style>
