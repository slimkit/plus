<template>
  <span
    v-if="dot"
    ref="badge"
    :class="classes">
    <slot/>
    <sup
      v-show="badge"
      :class="dotClasses"/>
  </span>
  <span
    v-else
    ref="badge"
    :class="classes">
    <slot/>
    <sup
      v-if="count"
      v-show="badge"
      :class="countClasses">{{ finalCount }}</sup>
  </span>
</template>

<script>
const prefixCls = "v-badge";

export default {
  name: "VBadge",
  props: {
    count: { type: [Number, String], default: 0 },
    dot: { type: Boolean, default: false },
    overflowCount: { type: [Number, String], default: 99 },
    className: { type: String, default: "" }
  },
  computed: {
    classes() {
      return `${prefixCls}`;
    },
    dotClasses() {
      return `${prefixCls}-dot`;
    },
    countClasses() {
      return [
        `${prefixCls}-count`,
        {
          [`${this.className}`]: !!this.className,
          [`${prefixCls}-count-alone`]: this.alone
        }
      ];
    },
    finalCount() {
      return parseInt(this.count) >= parseInt(this.overflowCount)
        ? `${this.overflowCount}+`
        : this.count;
    },
    badge() {
      let status = false;
      if (this.count) {
        status = !(parseInt(this.count) === 0);
      }
      if (this.dot) {
        status = true;
        if (this.count !== null) {
          if (parseInt(this.count) === 0) {
            status = false;
          }
        }
      }
      return status;
    },
    alone() {
      return this.$slots.default === undefined;
    }
  }
};
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
    transform: translateX(50%);
    top: -10px;
    right: 0;
    height: 20px;
    min-width: 20px;
    border-radius: 10px;
    background: @error;
    border: 1px solid transparent; /*no*/
    color: #fff;
    line-height: 20px;
    text-align: center;
    padding: 0 6px;
    font-size: 16px;
    white-space: nowrap;
    transform-origin: -10% center;
    z-index: 10;
    box-shadow: 0 0 0 1px @error; /*no*/

    a,
    a:hover {
      color: inherit;
    }

    &-alone {
      top: auto;
      display: block;
      position: relative;
      transform: translateX(0);
    }
  }

  &-dot {
    position: absolute;
    transform: translateX(-50%);
    transform-origin: 0 center;
    top: -3px;
    /* no */
    right: -6px;
    /* no */
    height: 6px;
    /* no */
    width: 6px;
    /* no */
    border-radius: 100%;
    background: @error;
    z-index: 10;
    box-shadow: 0 0 0 1px @error;
    /* no */
  }
}
</style>
