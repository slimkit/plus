<template>
  <span class="glyphicon glyphicon-refresh loading-icon" aria-hidden="true" v-bind="$props"></span>
</template>

<script>
export default { name: 'ui-loading' };
</script>

<style>
/* 旋转动画 */
@keyframes turn-around {
  from {
    transform: rotate(1deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.loading-icon {
  animation-name: turn-around;
  animation-duration: 1.4s;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
}
</style>
