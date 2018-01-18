<template>
  <div>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">切换导航条</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div class="navbar-brand">打赏</div>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <!-- The settings nav. -->
          <ul class="nav navbar-nav">
            <!-- 广告 -->
            <router-link to="/reward" tag="li" active-class="active" exact>
              <a href="#">打赏统计</a>
            </router-link>
            <router-link to="/reward/list" tag="li" active-class="active">
              <a href="#">打赏清单</a>
            </router-link>
          </ul>
        </div>    
      </div>
    </nav>
    <router-view />
  </div>
</template>