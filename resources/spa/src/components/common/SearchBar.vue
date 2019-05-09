<template>
  <header class="c-search-bar">
    <form class="input-wrap" onsubmit="return false">
      <svg class="m-icon-svg m-svg-small"><use xlink:href="#icon-search" /></svg>
      <input
        :value="value"
        :placeholder="placeholder"
        type="search"
        @input="onInput"
      >
    </form>

    <a class="btn-cancel" @click.prevent.stop="onBackClick">
      {{ $t('cancel') }}
    </a>
  </header>
</template>

<script>
import _ from 'lodash'
import { noop } from '@/util'
import i18n from '@/i18n'

export default {
  name: 'SearchBar',
  props: {
    value: { type: String, default: '' },
    placeholder: { type: String, default: i18n.t('search') },
    back: { type: Function, default: noop },
  },
  methods: {
    onBackClick () {
      if (this.back === noop) this.goBack()
      else this.back()
    },
    onInput: _.debounce(function (event) {
      this.$emit('input', event.target.value.trim())
    }, 600),
  },
}
</script>

<style lang="less" scoped>
.c-search-bar {
  flex: none;
  position: relative;
  display: flex;
  align-items: center;
  background-color: #fff;
  border-bottom: 1px solid @border-color;/*no*/
  width: 100%;
  height: 90px;
  padding: 0 20px;
  font-size: 32px;
  z-index: 1;

  .input-wrap {
    flex: auto;
    display: flex;
    align-items: center;
    background-color: #ebebeb;
    padding: 10px;
    border-radius: 8px;

    .m-icon-svg {
      flex: none;
      fill: #7c7c7c;
      vertical-align: middle;
      margin: 0 10px;
    }

    input {
      color: #7c7c7c;
      flex: auto;
      background-color: transparent;
      font-size: 30px;
    }
  }

  .btn-cancel {
    min-width: 2em;
    margin-left: 20px;
    text-align: right;
  }
}
</style>
