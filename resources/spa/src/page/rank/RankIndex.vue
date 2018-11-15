<template>
  <div>

    <common-header class="common-header">排行榜</common-header>

    <div class="rank-nav">
      <router-link
        tag="div"
        class="rank-nav-item"
        replace
        to="/rank/users">用户</router-link>
      <router-link
        tag="div"
        class="rank-nav-item"
        replace
        to="/rank/feeds">动态</router-link>

      <div class="rank-nav-item" @click.capture.stop.prevent="popupBuyTS">问答</div>
      <div class="rank-nav-item" @click.capture.stop.prevent="popupBuyTS">资讯</div>
    </div>
    <div class="rank-content">
      <keep-alive>
        <router-view v-if="$route.meta.keepAlive"/>
      </keep-alive>
      <router-view v-if="!$route.meta.keepAlive"/>
    </div>
  </div>
</template>

<script>
import HeadTop from "../../components/HeadTop";
export default {
  name: "RankIndex",
  components: {
    HeadTop
  },
  methods: {
    cancel() {
      this.to("/discover");
    },
    to(path) {
      path = typeof path === "string" ? { path } : path;
      if (path) {
        this.$router.push(path);
      }
    }
  }
};
</script>

<style lang='less' scoped>
.common-header {
  position: fixed;
  top: 0;
}
.rank-nav {
  position: fixed;
  z-index: 100;
  top: 90px;
  padding-top: 0 !important;
  display: flex;
  align-items: center;
  height: 90px;
  width: 100%;
  line-height: 89px;
  border-bottom: 1px solid #ededed;
  /*no*/
  background-color: #fff;
  justify-content: center;
  &-item {
    padding: 0 10px;
    font-size: 32px;
    color: #999;
    border-bottom: 3px solid transparent;
    & + & {
      margin-left: 90px;
    }

    &.router-link-active {
      border-color: @primary;
      color: #333;
    }
  }
}
.rank-content {
  padding-top: 180px;
  min-height: 100vh;
  position: relative;
  background-color: #f4f5f5;
}
</style>
