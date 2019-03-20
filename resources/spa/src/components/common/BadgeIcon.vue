<template>
  <span
    v-if="dot"
    ref="badge"
    :class="classes"
  >
    <slot />
    <sup :class="dotClasses" />
  </span>

  <span
    v-else
    ref="badge"
    :class="classes"
  >
    <slot />
    <sup
      v-if="count"
      v-show="badge"
      :class="countClasses"
    >
      {{ finalCount }}
    </sup>
  </span>
</template>

<script>
const prefixCls = 'v-badge'

export default {
  name: 'BadgeIcon',
  props: {
    count: { type: [Number, String], default: 0 },
    dot: { type: [Boolean, Number], default: false },
    overflowCount: { type: [Number, String], default: 99 },
    className: { type: String, default: '' },
  },
  computed: {
    classes () {
      return `${prefixCls}`
    },
    dotClasses () {
      return `${prefixCls}-dot`
    },
    countClasses () {
      return [
        `${prefixCls}-count`,
        {
          [`${this.className}`]: !!this.className,
          [`${prefixCls}-count-alone`]: this.alone,
        },
      ]
    },
    finalCount () {
      return parseInt(this.count) >= parseInt(this.overflowCount)
        ? `${this.overflowCount}+`
        : this.count
    },
    badge () {
      let status = false
      if (this.count) {
        status = !(parseInt(this.count) === 0)
      }
      if (this.dot) {
        status = true
        if (this.count !== null) {
          if (parseInt(this.count) === 0) {
            status = false
          }
        }
      }
      return status
    },
    alone () {
      return this.$slots.default === undefined
    },
  },
}
</script>

<style lang='less'>
.v-badge {
  position: relative;
  display: inline-block;
  line-height: 1;
  vertical-align: middle;
  color: inherit;

  &-count {
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    right: 0;
    height: 32px;
    width: 32px;
    border-radius: 32px;
    background: @error;
    border: 1px solid transparent; /*no*/
    color: #fff;
    font-size: 20px;
    white-space: nowrap;
    // transform-origin: -10% center;
    z-index: 10;
    box-shadow: 0 0 0 1px @error; /*no*/

    a,
    a:hover {
      color: inherit;
    }

    &-alone {
      top: auto;
      position: relative;
      transform: scale(0.9);
    }
  }

  &-dot {
    position: absolute;
    transform: translateX(-50%);
    transform-origin: 0 center;
    top: 2px; /* no */
    right: -6px; /* no */
    height: 6px; /* no */
    width: 6px; /* no */
    border-radius: 100%;
    background: @error;
    z-index: 10;
    box-shadow: 0 0 0 1px @error; /* no */
  }
}
</style>
