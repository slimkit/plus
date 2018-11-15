<template>
  <div class="c-loading">
    <div :class="css" class="circle">
      <span/>
      <span/>
      <span/>
      <span/>
      <span/>
      <span/>
      <span/>
      <span/>
      <span/>
      <span/>
      <span/>
      <span/>
    </div>
  </div>
</template>

<script>
export default {
  name: "CircleLoading",
  props: {
    size: { type: String, default: "" },
    color: { type: String, default: "dark" }
  },
  computed: {
    css() {
      return [this.size, this.color];
    }
  }
};
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
      margin-left: -1px;
      width: 2px;
      height: 8px;
      border-radius: 2px; /* no */
      transform-origin: center -10px;

      &:nth-child(1) {
        animation-delay: 0s;
        transform: rotate(0);
      }
      &:nth-child(2) {
        animation-delay: 0.1s;
        transform: rotate(30deg);
      }
      &:nth-child(3) {
        animation-delay: 0.2s;
        transform: rotate(60deg);
      }
      &:nth-child(4) {
        animation-delay: 0.3s;
        transform: rotate(90deg);
      }
      &:nth-child(5) {
        animation-delay: 0.4s;
        transform: rotate(120deg);
      }
      &:nth-child(6) {
        animation-delay: 0.5s;
        transform: rotate(150deg);
      }
      &:nth-child(7) {
        animation-delay: 0.6s;
        transform: rotate(180deg);
      }
      &:nth-child(8) {
        animation-delay: 0.7s;
        transform: rotate(210deg);
      }
      &:nth-child(9) {
        animation-delay: 0.8s;
        transform: rotate(240deg);
      }
      &:nth-child(10) {
        animation-delay: 0.9s;
        transform: rotate(270deg);
      }
      &:nth-child(11) {
        animation-delay: 1s;
        transform: rotate(300deg);
      }
      &:nth-child(12) {
        animation-delay: 1.1s;
        transform: rotate(330deg);
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
