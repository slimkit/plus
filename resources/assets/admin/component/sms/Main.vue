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
          <div class="navbar-brand">短信</div>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <!-- The settings nav. -->
          <ul class="nav navbar-nav">
            <router-link to="/sms" tag="li" active-class="active" exact>
              <a href="#">网关设置</a>
            </router-link>
            <router-link to="/sms/alidayu" tag="li" active-class="active">
              <a href="#">阿里大于</a>
            </router-link>
            <router-link to="/sms/aliyun" tag="li" active-class="active">
              <a href="#">阿里云</a>
            </router-link>
            <router-link to="/sms/yunpian" tag="li" active-class="active">
              <a href="#">云片</a>
            </router-link>
            <router-link to="/sms/huyi" tag="li" active-class="active">
              <a href="#">互亿无线</a>
            </router-link>
          </ul>
        </div>    
      </div>
    </nav>
    <router-view />
  </div>
</template>
