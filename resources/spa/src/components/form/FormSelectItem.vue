<template>
  <section class="c-form-item c-form-select-item">
    <label>{{ label }}</label>
    <div class="input-wrap" @click="onClick">
      <span :class="{placeholder: value.length === 0}">{{ value || placeholder }}</span>
      <svg v-if="!readonly" class="m-style-svg m-svg-def m-entry-append">
        <use xlink:href="#icon-arrow-right" />
      </svg>
    </div>
  </section>
</template>

<script>
export default {
  name: 'FormSelectItem',
  props: {
    value: { type: [String, Number], default: '' },
    label: { type: String, default: '' },
    placeholder: { type: String, default: '' },
    readonly: { type: Boolean, default: false },
  },
  methods: {
    onClick () {
      if (this.readonly) return
      this.$emit('click')
    },
  },
}
</script>

<style lang="less" scoped>
@import url("./formItem.less");

.c-form-select-item {
  .input-wrap {
    justify-content: space-between;

    > span {
      margin-right: 10px;
    }
  }
}
</style>
