<template>
  <Transition name="el-message-fade">
    <div
      v-show="visible"
      :class="[ 'v-message', `v-message-${type}`]"
      role="alert"
      @transitionend="transitionend"
    >
      <svg class="m-style-svg m-svg-small">
        <use :xlink:href="`#icon-message-${type}`" />
      </svg>
      <slot>
        <p
          v-if="!dangerouslyUseHTMLString"
          class="v-message-content"
        >
          {{ message }}
        </p>
        <p
          v-else
          class="v-message-content"
          v-html="message"
        />
      </slot>
    </div>
  </Transition>
</template>

<script>
export default {
  data () {
    return {
      visible: false,
      message: '',
      duration: 3000,
      type: 'info',
      closed: false,
      timer: null,
      dangerouslyUseHTMLString: false,
    }
  },
  watch: {
    closed (newVal) {
      if (newVal) {
        this.visible = false
      }
    },
  },
  mounted () {
    this.startTimer()
  },
  methods: {
    transitionend () {
      this.destroyElement()
    },
    destroyElement () {
      if (!this.closed) return
      this.$destroy(true)
      this.$el.parentNode.removeChild(this.$el)
    },
    close () {
      this.closed = true
      if (typeof this.onClose === 'function') {
        this.onClose(this)
      }
    },
    clearTimer () {
      clearTimeout(this.timer)
    },
    startTimer () {
      if (this.duration > 0) {
        this.timer = setTimeout(() => {
          if (!this.closed) {
            this.close()
          }
        }, this.duration)
      }
    },
  },
}
</script>

<style lang="less" scoped>
.v-message {
  overflow: hidden;
  position: fixed;
  top: 0;
  z-index: 999;
  display: flex;
  align-items: center;

  padding: 0 30px;
  min-height: 90px;
  width: 100%;

  background: #fff;

  pointer-events: none;
  transition: opacity 0.3s, transform 0.4s;
  box-shadow: 0px 2px 9px 0px rgba(22, 23, 23, 0.12);
  &-content {
    flex: 1 1 auto;
    margin-left: 15px;
    font-size: 30px;
    line-height: 1.4;
    word-wrap: break-word;
    word-break: break-all;
  }
  .m-svg-def {
    flex: 0 0 auto;
    margin-right: 10px;
    width: 42px;
    height: 42px;
  }
}
.el-message-fade-enter,
.el-message-fade-leave-active {
  opacity: 0;
  transform: translate(0, -100%);
}
</style>
