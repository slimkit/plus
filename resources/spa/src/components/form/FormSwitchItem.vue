<template>
  <section class="c-form-item c-form-switch-item">
    <label @click="onClick"> {{ label }} </label>
    <div class="checkbox">
      <label
        ref="label"
        class="label"
      >
        <input
          v-model="currentValue"
          :value="value"
          type="checkbox"
        >
        <div class="box" />
      </label>
    </div>
  </section>
</template>

<script>
export default {
  name: 'FormSwitchItem',
  props: {
    value: { type: Boolean, default: false },
    label: { type: String, default: '' },
  },
  data () {
    return {
      currentValue: this.value,
    }
  },
  watch: {
    currentValue (val) {
      this.$emit('input', val)
    },
  },
  methods: {
    onClick () {
      this.$refs.label.click()
    },
  },
}
</script>

<style lang="less" scoped>
@import url("./formItem.less");

.c-form-switch-item {
  justify-content: space-between;
  width: 100%;
  padding-right: 20px;

  > label {
    flex: auto;
    color: #333;
  }

  .checkbox {
    flex: none;
  }

  input {
    opacity: 0;
    position: absolute;
    left: -9999px;

    &:checked ~ .box {
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

  .box {
    flex: 0 0 auto;
    position: relative;
    width: 60px;
    height: 34px;
    border: 1px solid #ededed; /* no */
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
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4); /* no */
      /*no*/
      transition: transform 0.35s cubic-bezier(0.4, 0.4, 0.25, 1.35);
    }
  }
}
</style>
