<template>
  <div class="p-profile-news">
    <CommonHeader>{{ $t('profile.news.name') }}</CommonHeader>

    <main>
      <div class="m-sub-nav m-main">
        <RouterLink
          replace
          exact
          tag="div"
          exact-active-class="active"
          to="/profile/news/released"
          class="m-sub-nav-item"
        >
          <a>{{ $t('profile.news.published') }}</a>
        </RouterLink>
        <RouterLink
          replace
          exact
          tag="div"
          exact-active-class="active"
          to="/profile/news/auditing"
          class="m-sub-nav-item"
        >
          <a>{{ $t('profile.news.review') }}</a>
        </RouterLink>
        <RouterLink
          replace
          exact
          tag="div"
          exact-active-class="active"
          to="/profile/news/rejected"
          class="m-sub-nav-item"
        >
          <a>{{ $t('profile.news.reject') }}</a>
        </RouterLink>
      </div>
      <p class="info-tips">开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：17311245680。</p>
    </main>
  </div>
</template>

<script>
export default {
  name: 'ProfileNews',
}
</script>

<style lang="less">
.p-profile-news {
  .m-sub-nav {
    display: flex;
    justify-content: space-around;
    top: 90px;
    z-index: 2;
    border-bottom: 1px solid @border-color;/*no*/

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
