<template>
  <div class="full-spin">
    <span />
    <span />
  </div>
</template>
<script>
export default {
  name: 'FullSpin',
}
</script>
<style lang='less'>
@prefixCls: full-spin;

.@{prefixCls} {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  pointer-events: none;
  &-wrap {
    transform: translate3d(0, 0, 0);
    background-color: #fff;
  }
  span {
    font-size: 0;
    line-height: 0;
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    margin: auto;
    width: 70px;
    height: 70px;
    opacity: 0.5;
    background-color: @primary;
    border-radius: 50%;
    animation: scale1 2s infinite ease-in-out;
    &:nth-child(2) {
      animation-delay: -1s;
    }
  }
}

@keyframes scale1 {
  0%,
  100% {
    transform: scale(1/7);
  }

  50% {
    transform: scale(1);
  }
}
</style>
