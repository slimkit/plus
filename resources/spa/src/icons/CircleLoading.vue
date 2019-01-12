<template>
  <div class="c-loading">
    <div
      :class="css"
      class="circle"
    >
      <span />
      <span />
      <span />
      <span />
      <span />
      <span />
      <span />
      <span />
      <span />
      <span />
      <span />
      <span />
    </div>
  </div>
</template>

<script>
export default {
  name: 'CircleLoading',
  props: {
    size: { type: String, default: '' },
    color: { type: String, default: 'dark', validator: val => ['light', 'dark'].includes(val) },
  },
  computed: {
    css () {
      return [this.size, this.color]
    },
  },
}
</script>

<style lang="less" scoped>
.c-loading {
  display: inline-block;
  height: 48px;
  width: 48px;
  vertical-align: middle;

  &.small {
    height: 32px;
    width: 32px;
  }

  &.big {
    height: 64px;
    width: 64px;
  }

  .circle {
    position: relative;
    display: block;
    width: 100%;
    height: 100%;
    overflow: hidden;

    &.dark span {
      animation: loading-fade-dark 1.1s infinite linear;
    }
    &.light span {
      animation: loading-fade-light 1.1s infinite linear;
    }

    span {
      position: absolute;
      left: 50%;
      bottom: calc(~"50% - 18px");
      margin-left: -1px; /* no */
      width: 2px; /* no */
      height: 8px;
      border-radius: 2px; /* no */
      transform-origin: center -10px;

      .generate-i(12);

      .generate-i(@n, @i: 1) when (@i =< @n) {
        &:nth-child(@{i}) {
          animation-delay: 0.1s * (@i - 1);
          transform: rotate(30deg * (@i - 1));
        }
        .generate-i(@n, (@i + 1))
      }
    }
  }
}

@keyframes loading-fade-dark {
  0% {
    background-color: #5c5c5c;
  }
  100% {
    background-color: rgba(255, 255, 255, 0);
  }
}

@keyframes loading-fade-light {
  0% {
    background-color: #cacaca;
  }
  100% {
    background-color: rgba(255, 255, 255, 0);
  }
}
</style>
