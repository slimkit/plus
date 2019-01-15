<template>
  <header :class="{pinned}" class="c-common-header">
    <div class="left">
      <slot name="left">
        <svg class="m-style-svg m-svg-def" @click="onBackClick">
          <use xlink:href="#icon-back" />
        </svg>
      </slot>
    </div>
    <div class="title">
      <div class="title-wrap"><slot /></div>
    </div>
    <div class="right">
      <slot name="right" />
    </div>
  </header>
</template>

<script>
import { noop } from '@/util'

export default {
  name: 'CommonHeader',
  props: {
    back: { type: Function, default: noop },
    pinned: { type: Boolean, default: false },
  },
  methods: {
    onBackClick () {
      if (this.back === noop) this.goBack()
      else this.back()
    },
  },
}
</script>

<style lang="less" scoped>
@header-height: 90px;

.c-common-header {
  display: flex;
  flex: none;
  position: relative;
  bottom: initial;
  width: 100%;
  height: @header-height;
  max-width: 768px;
  margin: 0 auto;
  justify-content: space-between;
  overflow: hidden;
  background: #fff;
  border-bottom: 1px solid #ededed; /* no */
  font-size: 32px;
  color: inherit;
  z-index: 10;

  &.pinned {
    position: fixed;
  }

  .left,
  .right,
  .title {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .left,
  .right {
    flex: none;
    width: fit-content;
    min-width: @header-height;
    padding: 0 0.5em;
  }
  .title {
    position: absolute;
    left: 0;
    right: 0;
    height: 100%;
    width: 16em;
    margin: 0 auto;
    text-align: center;

    > .title-wrap {
      width: 100%;
      text-overflow: ellipsis;
      white-space: nowrap;
      overflow: hidden;
      word-wrap: break-word;
      word-break: break-all;
    }
  }
}
</style>

<style lang="less">
.c-common-header.pinned + * {
  padding-top: 90px;
}
</style>
