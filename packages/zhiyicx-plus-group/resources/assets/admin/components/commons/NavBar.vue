<template>
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">

      <div class="navbar-header">
        <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" aria-expanded="false" data-target="#question-navbar-collapse">
          <span class="sr-only">切换导航</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <span class="navbar-brand">圈子</span>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="question-navbar-collapse">
        
        <ul class="nav navbar-nav">

          <router-link to="/" tag="li" active-class="active" exact>
            <a href="#">基本信息</a>
          </router-link>
          
          <router-link to="/protocol" tag="li" active-class="active">
            <a href="#">圈子协议</a>
          </router-link>

          <router-link to="/categories" tag="li" active-class="active">
            <a href="#">圈子分类</a>
          </router-link>

          <router-link to="/groups" tag="li" active-class="active">
            <a href="#">圈子管理</a>
          </router-link>

          <router-link to="/posts" tag="li" active-class="active">
            <a href="#">帖子管理</a>
          </router-link>

          <router-link to="/comments" tag="li" active-class="active">
            <a href="#">评论管理</a>
          </router-link>

          <router-link to="/recommends" tag="li" active-class="active">
            <a href="#">圈子推荐</a>
          </router-link>
        </ul>

      </div>

    </div>
  </nav>
</template>

<script>
export default { name: 'ui-nav-bar' };
</script>
