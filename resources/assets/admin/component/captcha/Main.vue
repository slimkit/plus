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
          <div class="navbar-brand">网关</div>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <!-- The settings nav. -->
          <ul class="nav navbar-nav">
            <router-link to="/captcha" tag="li" active-class="active" exact>
              <a href="#">验证码记录</a>
            </router-link>
            <router-link to="/captcha/gateway" tag="li" active-class="active">
              <a href="#">网关配置</a>
            </router-link>
            <router-link to="/captcha/template" tag="li" active-class="active">
              <a href="#">模版配置</a>
            </router-link>
          </ul>
        </div>    
      </div>
    </nav>
    <router-view />
  </div>
</template>