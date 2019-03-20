<template>
  <div :class="[`${prefixCls}-wrap`]">
    <span :class="[`${prefixCls}-text`]" @click="onClick">
      <slot />
    </span>
    <div :class="[`${prefixCls}-append`]">
      <slot name="append" />
    </div>
    <div :class="[`${prefixCls}`]">
      <label
        ref="label"
        :for="id"
        :class="[`${prefixCls}-label`]"
      >
        <input
          v-if="type===&quot;radio&quot;"
          :id="id"
          v-model="cur_value"
          :value="dataValue"
          :class="[`${prefixCls}-input`]"
          type="radio"
          @change="setValue"
        >
        <input
          v-else
          :id="id"
          v-model="cur_value"
          :class="[`${prefixCls}-input`]"
          :type="type"
          @change="setValue"
        >
        <div :class="[`${prefixCls}-box`]" />
      </label>
    </div>
  </div>
</template>

<script>
const prefixCls = 'v-switch'

export default {
  name: 'VSwitch',
  props: {
    type: { type: String, default: 'checkbox', validator: val => ['checkbox', 'radio'].includes(val) },
    value: { type: [Boolean, Number], default: false },
    dataValue: { type: Object, default: () => {} },
    id: { type: String, default () { return 'v-switch-' + this._uid } },
  },
  data () {
    return {
      prefixCls: `${prefixCls}-${this.type}`,
      cur_value: this.value,
    }
  },
  watch: {
    value (val) {
      this.cur_value = val
    },
  },
  methods: {
    setValue () {
      this.$emit('input', this.cur_value)
    },
    onClick () {
      this.$refs.label.click()
    },
  },
}
</script>

<style lang="less" scoped>
.v-switch-radio,
.v-switch-checkbox {
  &-append {
    order: 2;
    font-size: 30px;
    text-align: right;
    input {
      font-size: 30px;
      text-align: right;
      padding: 0 20px;
    }
  }
  &-wrap {
    display: flex;
    background: #fff;
    padding: 0 20px;
    height: 100px;
    line-height: 98px;
    align-items: center;
    justify-content: space-between;
    & + & {
      border-top: 1px solid #ededed; /*no*/
    }
  }
  &-text {
    font-size: 30px;
    color: #333;
    display: block;
    flex: 1 1 auto;
  }

  &-box {
    flex: 0 0 auto;
  }

  &-input {
    /* display: none; // 部分浏览器无效 */
    position: absolute;
    left: -9999px;
  }
}

.v-switch-checkbox {
  &-box {
    position: relative;
    width: 60px;
    height: 34px;
    border: 1px solid #ededed; /*no*/
    outline: 0;
    border-radius: 34/2px;
    box-sizing: border-box;
    background-color: #fff;
    transition: background-color 0.1s, border 0.1s;

    &:before,
    &:after {
      content: " ";
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      margin: auto;
      background-color: #fff;
    }

    &:before {
      width: 100%;
      height: 100%;
      border-radius: inherit;
      transition: transform 0.35s cubic-bezier(0.45, 1, 0.4, 1);
    }

    &:after {
      right: initial;
      width: 30px;
      height: 30px;
      border-radius: 15px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4); /*no*/
      transition: transform 0.35s cubic-bezier(0.4, 0.4, 0.25, 1.35);
    }
  }
  &-input {
    &:checked ~ .v-switch-checkbox-box {
      border-color: #44db5e;
      background-color: #44db5e;
      &:after {
        transform: translateX(30px);
      }

      &:before {
        transform: scale(0);
      }
    }
  }
}

.v-switch-radio {
  &-wrap {
    justify-content: flex-start;
  }
  &-text {
    order: 1;
    margin-left: 20px;
  }

  &-box {
    /*overflow: hidden;*/
    width: 30px;
    height: 30px;
    border: 1px solid #ccc; /*no*/
    background-color: #fff;
    position: relative;
  }

  &-input {
    &:checked ~ .v-switch-radio-box {
      &:after {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
        content: "";
        display: block;
        height: 100%;
        width: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAMAAAAM7l6QAAAANlBMVEUAAABw0Ktw0Ktw0Ktw0Ktw0Ktw0Ktw0Ktw0Ktw0Ktw0Ktw0Ktw0Ktw0Ktw0Ktw0Ktw0Ktw0KuRtsGLAAAAEnRSTlMA/sLpMhfZQgyypJiGd2pbTyQ7f6AvAAAAfUlEQVQoz92RSw7DIAwFPZjwSwhw/8tWqqqKqDH75u3QINuaJw/M5vwKO9yC7uhm0xMWsyM0mw7lsGkKZLGTCcmmBzrmO8KYaYN4FaDT28N5vSRD/bpU9t9tlM/XW5cd3NthIdy59AH1IhW80R+0Dt3uCKjLloosEnOSv8kLrsUCT1d4nxMAAAAASUVORK5CYII=");
      }
    }
  }
}
</style>
