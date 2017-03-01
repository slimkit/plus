<style lang="scss">
@import "./style/app";
@keyframes TurnAround {
  from {
    transform: rotate(1deg);
  }
  to {
    transform: rotate(360deg);
  }
}

#app {
  padding: 0;
  margin: 0;
}
</style>

<template>
  <div class="container-fluid" id="app">
    <router-view></router-view>
  </div>
</template>
