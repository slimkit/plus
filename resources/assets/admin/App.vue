<style lang="scss">
@import "./style/app";
// 旋转动画
@keyframes TurnAround {
  from {
    transform: rotate(1deg);
  }
  to {
    transform: rotate(360deg);
  }
}

// 设置滚动条样式
::-webkit-scrollbar {
  width: 2px;
  height: 2px;
}
::-webkit-scrollbar-thumb {
  background-color: #33BBBA !important;
}
::-webkit-scrollbar-track {
  background-color: transparent !important
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
