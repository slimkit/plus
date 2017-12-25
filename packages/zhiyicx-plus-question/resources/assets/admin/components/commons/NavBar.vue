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
        <span class="navbar-brand">问答</span>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="question-navbar-collapse">
        
        <ul class="nav navbar-nav">

          <router-link to="/" tag="li" active-class="active" exact>
            <a href="#">基本信息</a>
          </router-link>

          <router-link to="/questions" tag="li" active-class="active">
            <a href="#">问题管理</a>
          </router-link>

          <router-link to="/answers" tag="li" active-class="active">
            <a href="#">回答管理</a>
          </router-link>

          <router-link to="/comments" tag="li" active-class="active">
            <a href="#">评论管理</a>
          </router-link>

          <router-link to="/excellences" tag="li" active-class="active">
            <a href="#">精选管理</a>
          </router-link>

          <router-link to="/topics" tag="li" active-class="active">
            <a href="#">话题管理</a>
          </router-link>
        </ul>

      </div>

    </div>
  </nav>
</template>

<script>
export default { name: 'ui-nav-bar' };
</script>
