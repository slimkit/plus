<style lang="scss">
.setting-container {
  // padding-top: 70px;
  .setting-nav {
    padding: 12px 12px 0;
  }
}
</style>

<template>
  <div class="setting-container">
    <!-- The settings nav. -->
    <ul class="nav nav-tabs setting-nav">
      <router-link to="/setting" tag="li" active-class="active" exact>
        <a href="#">基本信息</a>
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
      <li role="presentation" class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
          过滤配置 <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <router-link tag="li" active-class="active" to="/setting/filter-word-categories">
            <a href="#">过滤词语分类</a>
          </router-link>
          <router-link tag="li" active-class="active" to="/setting/filter-word-types">
            <a href="#">过滤词语类型</a>
          </router-link>
          <router-link tag="li" active-class="active" to="/setting/sensitive-words">
            <a href="#">敏感词管理</a>
          </router-link>
        </ul>
      </li>
      </router-link>
      <router-link to="/setting/server" tag="li" active-class="active">
        <a href="#">服务器信息</a> 
      </router-link>
    </ul>
    <!-- End settings nav. -->

    <!-- The setting container view -->
    <router-view></router-view>
    <!-- End -->
  </div>
</template>
