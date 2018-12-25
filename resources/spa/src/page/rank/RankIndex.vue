<template>
  <div>
    <CommonHeader class="common-header">{{ $t('rank.name') }}</CommonHeader>

    <div class="rank-nav">
      <RouterLink
        tag="div"
        class="rank-nav-item"
        replace
        to="/rank/users"
      >
        {{ $t('rank.user') }}
      </RouterLink>
      <RouterLink
        tag="div"
        class="rank-nav-item"
        replace
        to="/rank/feeds"
      >
        {{ $t('feed.name') }}
      </RouterLink>

      <div class="rank-nav-item" @click.capture.stop.prevent="popupBuyTS"> {{ $t('question.q_a') }} </div>
      <div class="rank-nav-item" @click.capture.stop.prevent="popupBuyTS"> {{ $t('news.name') }} </div>
    </div>
    <div class="rank-content">
      <KeepAlive>
        <RouterView v-if="$route.meta.keepAlive" />
      </KeepAlive>
      <RouterView v-if="!$route.meta.keepAlive" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'RankIndex',
  methods: {
    cancel () {
      this.to('/discover')
    },
    to (path) {
      path = typeof path === 'string' ? { path } : path
      if (path) {
        this.$router.push(path)
      }
    },
  },
}
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
  max-width: 768px;
  line-height: 89px;
  border-bottom: 1px solid #ededed; /*no*/
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
