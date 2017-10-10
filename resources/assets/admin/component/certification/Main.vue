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
              <div class="navbar-brand">认证</div>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <!-- The settings nav. -->
              <ul class="nav navbar-nav">
                <router-link :to="{name: 'certification:users'}" tag="li" active-class="active" exact>
                    <a href="javascript:;">认证用户</a>
                </router-link>
                <router-link :to="{name: 'certification:categories'}" tag="li" active-class="active">
                    <a href="javascript:;">认证类型</a>
                </router-link>
              </ul>
            </div>    
          </div>
        </nav>
        <router-view />
    </div>
</template>
