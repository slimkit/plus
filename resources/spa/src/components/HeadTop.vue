<template>
  <div :class="['head-top', { transparent }]">
    <slot>
      <div class="head-top-goback">
        <slot name="prepend">
          <section
            v-if="goBack"
            @click="goBackFn"
          >
            <template v-if="typeof goBack === &quot;string&quot;">
              {{ goBack }}
            </template>
            <template v-else>
              <svg class="m-style-svg m-svg-def">
                <use xlink:href="#icon-back" />
              </svg>
            </template>
          </section>
        </slot>
      </div>
      <div
        v-if="title"
        class="head-top-title"
      >
        <slot name="title">
          <span class="ellipsis">
            {{ title || $route.meta.title }}
          </span>
        </slot>
      </div>
      <slot name="nav" />
      <div
        v-if="append"
        class="head-top-append"
      >
        <slot name="append">
          <section @click="to('/signup')">注册</section>
        </slot>
      </div>
    </slot>
  </div>
</template>

<script>
export default {
  name: 'HeadTop',
  props: {
    title: { type: String, default: '' },
    goBack: { type: [Boolean, Function], default: false },
    append: { type: [Boolean, String], default: false },
    transparent: { type: Boolean, default: false },
  },
  computed: {},
  methods: {
    goBackFn () {
      return typeof this.goBack === 'function'
        ? this.goBack()
        : this.$router.go(-1)
    },
    to (path) {
      if (path) {
        this.$router.push({ path })
      }
    },
  },
}
</script>

<style lang='less'>
@head-top-prefix: head-top;
.@{head-top-prefix} {
  position: fixed;
  z-index: 100;
  left: 0;
  top: 0;
  height: 90px;
  width: 100%;
  line-height: 90px;
  color: #333;
  background: #fff;
  border-bottom: 1px solid #ededed; /* no */
  /* no */
  transition: all 0.3s;
  &.transparent {
    background-color: transparent;
    border-bottom-color: transparent;
    color: #fff;
  }

  & + * {
    padding-top: 90px;
  }

  .diy-select {
    font-size: 36px;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    margin: auto;
    text-align: center;
    height: 100%;
    &--label {
      display: inline-block;
      &:after {
        font-size: 16px;
        right: 0;
        color: #ccc;
      }
    }
    &--option {
      background-color: #fff;
    }
    &--options {
      position: fixed;
      top: 90px;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: rgba(0, 0, 0, 0.2);
    }
  }

  &-title {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60%;
    text-align: center;
    font-size: 36px;
  }
  &-nav {
    display: flex;
    -ms-align-items: center;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 80%;
    height: 100%;
    &-item {
      box-sizing: border-box;
      color: #999;
      text-align: center;
      font-size: 32px;
      width: 90px;
      border-bottom: 2px solid transparent; /* no */
      & + & {
        margin-left: 50px;
      }
    }
    .router-link-active {
      color: #333;
      border-bottom-color: @primary;
    }
  }

  &-tabs-nav {
    display: flex;
    -ms-align-items: center;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 80%;
    height: 100%;
    &-item {
      padding: 0 30px;
      font-size: 32px;
      color: @primary;
      height: 60px;
      line-height: 60px;
      border-width: 1px 0; /*no*/
      border-style: solid;
      border-color: @primary;

      &:first-child {
        border-left-width: 1px; /*no*/
        border-top-left-radius: 4px; /*no*/
        border-bottom-left-radius: 4px; /*no*/
      }
      &:last-child {
        border-right-width: 1px; /*no*/
        border-top-right-radius: 4px; /*no*/
        border-bottom-right-radius: 4px; /*no*/
      }

      &.router-link-active {
        background-color: @primary;
        color: #fff;
      }
    }
  }
  &-goback {
    left: 30px;
    // width: 100px;
    height: 100%;
    margin-left: 30px;
    font-size: 32px;
  }
  &-append {
    position: absolute;
    right: 30px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 32px;
    svg {
      width: 40px;
      height: 40px;
      margin-right: 4px;
      margin-left: -4px;
      + & {
        margin-left: 10px;
      }
    }
  }
  &-cancel {
    color: @primary;
  }

  &-search {
    display: flex;
    padding: 0 10px;
    align-items: center;
    justify-content: flex-start;
    width: 600px;
    height: 55px;
    background-color: #f4f5f5;
    border-radius: 10px;

    &-input {
      margin-left: 15px;
      flex: 1 1 auto;
      height: 100%;
      background: none;
    }
  }
}
</style>
