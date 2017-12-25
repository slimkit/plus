<template>
  <nav class="navbar navbar-default">
    <div class="container">
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="question-navbar-collapse">
        
        <ul class="nav navbar-nav">
          <router-link to="/topics" tag="li" active-class="active" exact>
            <a href="#">话题列表</a>
          </router-link>

          <router-link to="/topics/applications" tag="li" active-class="active">
            <a href="#">话题申请列表</a>
          </router-link>
        </ul>

      </div>

    </div>
  </nav>
</template>
<script>
export default { name: 'ui-topic-nav-bar' };
</script>