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
    padding-top: 70px;
}
</style>
<template>
  <div id="app">
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" aria-expanded="false" data-target="#news-navbar-collapse">
                <span class="sr-only">切换导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="news-navbar-collapse">
          <ul class="nav navbar-nav">
              <router-link to="/setting" tag="li" active-class="active">
                <a href="#">基础配置</a>
              </router-link>
              <router-link to="/loginmanage" tag="li" active-class="active">
                <a href="#">三方配置</a>
              </router-link>
              <li role="presentation" class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                  导航配置 <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <router-link tag="li" active-class="active" to="/navmenu">
                    <a href="#">头部导航</a>
                  </router-link>
                  <router-link tag="li" active-class="active" to="/footnav">
                    <a href="#">底部导航</a>
                  </router-link>
                  <router-link tag="li" active-class="active" to="/navmanage">
                    <a href="#">添加导航</a>
                  </router-link>
                </ul>
              </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
      <router-view></router-view>
    </div>
  </div>
</template>
<script>
export default {
    name: 'Pc'
}
</script>