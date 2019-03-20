<template lang="html">
  <div
    :class="{open}"
    class="diy-select"
    @click="onClick"
  >
    <div class="diy-select--label">{{ curSelectValue }}</div>
    <div v-show="open" class="diy-select--options">
      <div
        v-for="option in options"
        :key="option.label"
        class="diy-select--option"
        @click="setCurVal(option)"
      >
        <template v-if="option.hasMsg">
          <BadgeIcon :dot="option.hasMsg">{{ option.label }}</BadgeIcon>
        </template>
        <span v-else>{{ option.label }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import i18n from '@/i18n'

export default {
  name: 'DiySelect',
  props: {
    value: { type: null, default: '' },
    options: { type: Array, default: () => [] },
    placeholder: { type: String, default: i18n.t('please_select') },
    readonly: { type: Boolean, default: false },
  },
  data () {
    return {
      curVal: '',
      open: false,
    }
  },
  computed: {
    curSelectValue: {
      set (val) {
        this.curVal = this.options.find(o => o.value === val)
      },
      get () {
        if (this.curVal && typeof this.curVal.label !== 'undefined') {
          return this.curVal.label
        } else {
          return this.placeholder
        }
      },
    },
  },
  watch: {
    value (val) {
      this.curSelectValue = val
    },
  },
  mounted () {
    this.curSelectValue = this.value
  },
  methods: {
    setCurVal (val) {
      this.curVal = val
      this.$emit('input', val.value)
    },
    onClick () {
      if (this.readonly) return this.$emit('click')
      this.open = !this.open
    },
  },
}
</script>

<style lang="less" scoped>
.diy-select {
  display: inline-block;
  width: 180px;
  height: calc(~"100% - 2px");
  position: relative;

  &--label {
    padding: 20px;
    height: 100%;
    line-height: 90-40px;
    position: relative;
    &:after {
      content: "▼";
      position: absolute;
      right: 20px;
      transition: all 0.3s ease;
      transform-origin: center;
      transform: scale(0.6);
    }
  }

  &--options {
    background-color: #fff;
    position: fixed;
    left: 0;
    right: 0;
    box-shadow: -1px 0 3px #ededed;/*no*/
  }

  &--option {
    padding: 0 20px;
    height: 90px;
    border-top: 1px solid #ededed; /*no*/
    line-height: 90px;
    text-align: center;
  }

  .open &--label {
    &::after {
      transform: scale(0.6) rotate(180deg);
    }
  }
}
</style>
