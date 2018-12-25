<template>
  <Transition name="toast">
    <div
      v-if="show"
      class="c-popup-dialog"
    >
      <div class="panel">
        <header
          v-if="title"
          v-text="title"
        />
        <main v-html="content" />
        <footer
          @click="onConfirm"
          v-text="confirmText"
        />
      </div>
    </div>
  </Transition>
</template>

<script>
import { noop } from '@/util'

export default {
  name: 'PopupDialog',
  data () {
    return {
      show: false,

      title: '',
      content: '',
      confirmText: this.$t('got_it'),
      onClose: noop,
    }
  },
  created () {
    this.$bus.$on('popupDialog', (content, options = {}) => {
      if (typeof content === 'object') options = content
      else options.content = content
      Object.assign(this.$data, options)

      this.show = true
    })
  },
  methods: {
    onConfirm () {
      this.show = false
      this.onClose()
    },
  },
}
</script>

<style lang="less" scoped>
.c-popup-dialog {
  display: flex;
  align-items: center;
  position: fixed;
  top: -100px;
  height: calc(~"100% + 200px");
  left: -100px;
  right: -100px;
  padding: 100px;
  background-color: rgba(0, 0, 0, 0.4);
  z-index: 200;

  .panel {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #fff;
    width: 80%;
    margin: 0 auto;
    border-radius: 10px;
    padding: 0 38px;
    max-height: 70%;
  }

  header {
    flex: none;
    padding: 38px 0;
  }

  main {
    flex: auto;
    overflow: auto;
    border: 1px solid #ededed; /* no */
    border-width: 1px 0; /* no */
    padding: 20px 0;
  }

  footer {
    flex: none;
    color: @primary;
    text-align: center;
    width: 100%;
    padding: 38px 0;
  }
}
</style>
