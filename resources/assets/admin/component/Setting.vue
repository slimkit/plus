<template>
  <div>
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
              <span class="sr-only">切换导航条</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <div class="navbar-brand">系统</div>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <!-- The settings nav. -->
            <ul class="nav navbar-nav">
              <router-link to="/setting" tag="li" active-class="active" exact>
                <a href="#">基本信息</a>
              </router-link>
              <router-link to="/setting/site" tag="li" active-class="active">
                <a href="#">站点设置</a>
              </router-link>
              <router-link to="/setting/security" tag="li" active-class="active">
                <a href="#">安全</a>
              </router-link>
              <router-link to="/setting/area" tag="li" active-class="active">
                <a href="#">地区库</a>
              </router-link>
              <router-link to="/setting/mail" tag="li" active-class="active">
                <a href="#">邮件</a>
              </router-link>
              <li role="presentation" class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                  标签设置 <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <router-link tag="li" active-class="active" to="/setting/tags">
                    <a href="#">标签列表</a>
                  </router-link>
                  <router-link tag="li" active-class="active" to="/setting/tag-categories">
                    <a href="#">标签分类</a>
                  </router-link>
                  <router-link tag="li" active-class="active" to="/setting/addtag">
                    <a href="#">添加标签</a>
                  </router-link>
                </ul>
              </li>

              <!-- 敏感词 -->
              <router-link to="/setting/sensitives" tag="li" active-class="active">
                <a href="#">敏感词</a>
              </router-link>
              
              <!-- IOS IAP 设置 -->
              <router-link to="/setting/ios-iap" tag="li" active-class="active">
                <a href="#">IOS IAP</a>
              </router-link>

              <router-link to="/setting/upload-setting" tag="li" active-class="active">
                <a href="#">上传设置</a>
              </router-link>

              <router-link to="/setting/server" tag="li" active-class="active">
                <a href="#">服务器信息</a> 
              </router-link>
              <router-link to="/setting/about" tag="li" active-class="active">
                <a href="#">关于我们</a>
              </router-link>
            </ul>
            <!-- End settings nav. -->
          </div>
        </div>
      </nav>
    <!-- The setting container view -->
    <router-view></router-view>
    <!-- End -->
  </div>
</template>
