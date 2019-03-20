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
          <div class="navbar-brand">用户</div>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <!-- The settings nav. -->
          <ul class="nav navbar-nav">
            <router-link to="/users" tag="li" active-class="active" exact>
              <a href="#">用户管理</a>
            </router-link>
            <router-link to="/users/register" tag="li" active-class="active" exact>
              <a href="#">注册设置</a>
            </router-link>
            <router-link to="/users/setting" tag="li" active-class="active">
              <a href="#">基础设置</a>
            </router-link>
            <router-link to="/users/roles" tag="li" active-class="active">
              <a href="#">角色管理</a>
            </router-link>
            <router-link to="/users/abilities" tag="li" active-class="active">
              <a href="#">权限管理</a>
            </router-link>
            <router-link to="/users/recommends" tag="li" active-class="active">
              <a href="#">推荐用户</a>
            </router-link>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                第三方 <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <router-link tag="li" active-class="active" :to="{ name: 'user:vendor/qq' }">
                  <a href="#">QQ&nbsp;登录</a>
                </router-link>
                <router-link tag="li" active-class="active" :to="{ name: 'user:vendor/wechat' }">
                  <a href="#">微信登录</a>
                </router-link>
                <router-link tag="li" active-class="active" :to="{ name: 'user:vendor/weibo' }">
                  <a href="#">微博登录</a>
                </router-link>
                <router-link tag="li" active-class="active" :to="{ name: 'user:vendor/wechat-mp' }">
                  <a href="#">微信公众平台</a>
                </router-link>
                <li class="divider"></li>
                <router-link tag="li" active-class="active" :to="{ name: 'user:vendor/easemob' }">
                  <a href="#">环信即时聊天</a>
                </router-link>
              </ul>
            </li>
          </ul>
        </div> 
      </div>   
    </nav>
    <router-view></router-view>
  </div>
</template>
