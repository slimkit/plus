<template>
  <section class="c-form-item c-form-location-item">
    <label>{{ label }}</label>
    <div
      class="input-wrap"
      @click="switchPosition"
    >
      <span :class="{placeholder: !value.label}">{{ value.label || "位置" }}</span>
      <svg
        v-if="!readonly"
        class="m-style-svg m-svg-def m-entry-append"
      >
        <use xlink:href="#icon-arrow-right" />
      </svg>
    </div>

    <Location
      :show="showPosition"
      :is-component="true"
      @close="close"
    />
  </section>
</template>

<script>
import Location from '@/page/Location.vue'

export default {
  name: 'FormLocationItem',
  components: { Location },
  props: {
    value: { type: null, default: () => {} },
    label: { type: String, default: '位置' },
    readonly: { type: Boolean, default: false },
  },
  data () {
    return {
      showPosition: false,
    }
  },
  methods: {
    switchPosition () {
      if (this.readonly) return
      this.showPosition = true
    },
    close (val) {
      this.showPosition = false
      this.$emit('input', val)
    },
  },
}
</script>

<style lang="less" scoped>
@import url("./formItem.less");
</style>
