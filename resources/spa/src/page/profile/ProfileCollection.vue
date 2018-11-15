<template>
  <div class="p-profile-collection">

    <common-header>我的收藏</common-header>

    <main>
      <div class="m-pos-f m-box m-aln-center m-justify-bet m-sub-nav m-bb1 m-main">
        <router-link
          replace
          exact
          tag="div"
          exact-active-class="active"
          to="/profile/collection/feeds"
          class="m-sub-nav-item">
          <a>动态</a>
        </router-link>
        <router-link
          replace
          exact
          tag="div"
          exact-active-class="active"
          to="/profile/collection/news"
          class="m-sub-nav-item">
          <a>资讯</a>
        </router-link>
        <router-link
          replace
          exact
          tag="div"
          exact-active-class="active"
          to="/profile/collection/answers"
          class="m-sub-nav-item">
          <a>回答</a>
        </router-link>
        <router-link
          replace
          exact
          tag="div"
          exact-active-class="active"
          to="/profile/collection/posts"
          class="m-sub-nav-item">
          <a>帖子</a>
        </router-link>
      </div>
      <keep-alive>
        <router-view/>
      </keep-alive>
    </main>
  </div>
</template>

<script>
export default {
  name: "ProfileCollection",
  data() {
    return {};
  }
};
</script>

<style lang="less" scoped>
.p-profile-collection {
  .m-sub-nav {
    top: 90px;
    z-index: 2;

    .m-sub-nav-item {
      height: 100%;
      line-height: 90px;
      text-align: center;

      > a {
        display: inline-block;
        width: 100%;
      }

      &.router-link-active {
        color: #333;
        border-bottom: 4px solid @primary;
      }
    }
  }
}
</style>
