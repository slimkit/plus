<template>
  <div class="p-profile-news">

    <common-header>我的投稿</common-header>

    <main style="padding-top: .9rem">
      <div class="m-pos-f m-box m-aln-center m-justify-bet m-sub-nav m-bb1 m-main">
        <router-link
          replace
          exact
          tag="div"
          exact-active-class="active"
          to="/profile/news/released"
          class="m-sub-nav-item">
          <a>已发布</a>
        </router-link>
        <router-link
          replace
          exact
          tag="div"
          exact-active-class="active"
          to="/profile/news/auditing"
          class="m-sub-nav-item">
          <a>投稿中</a>
        </router-link>
        <router-link
          replace
          exact
          tag="div"
          exact-active-class="active"
          to="/profile/news/rejected"
          class="m-sub-nav-item">
          <a>被驳回</a>
        </router-link>
      </div>
      <p class="info-tips">开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：18108035545。</p>
    </main>
  </div>
</template>

<script>
export default {
  name: "ProfileNews"
};
</script>

<style lang="less">
.p-profile-news {
  .m-sub-nav {
    top: 90px;
    z-index: 2;

    .m-sub-nav-item {
      height: 100%;
      line-height: 90px;
      text-align: center;

      &.router-link-active {
        color: #333;
        border-bottom: 4px solid @primary;
      }
    }
  }

  .info-tips {
    padding: 30px;
    background-color: #fff;
  }
}
</style>
