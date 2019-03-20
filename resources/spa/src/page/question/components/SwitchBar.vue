<template>
  <CommonHeader
    :pinned="true"
    class="c-switch-bar"
  >
    <div
      class="tab"
      @click.capture.stop.prevent="popupBuyTS"
    >
      <!-- Queation type link. -->
      <RouterLink
        :replace="true"
        :exact="true"
        :class="{active: $route.path === '/question'}"
        class="item"
        to="/question"
      >
        {{ $t('question.q_a') }}
      </RouterLink>

      <!-- Tags type link. -->
      <RouterLink
        :replace="true"
        :exact="true"
        :class="{active: $route.path === '/question/topics'}"
        class="item"
        to="/question/topics"
      >
        {{ $t('question.topic.name') }}
      </RouterLink>
    </div>

    <div
      slot="right"
      @click.capture.stop.prevent="popupBuyTS"
    >
      <RouterLink to="/question/search">
        <svg class="m-style-svg m-svg-def search-btn">
          <use xlink:href="#icon-search" />
        </svg>
      </RouterLink>
    </div>
  </CommonHeader>
</template>

<script>
export default {
  name: 'QuestionSwitchBar',
}
</script>

<style lang="less" scoped>
.c-switch-bar {
  position: fixed;
  border-color: #dedede;
  z-index: 10;

  .search-btn {
    color: #333;
  }

  .tab {
    display: inline-flex;

    .item {
      width: 125px;
      height: 60px;
      line-height: 60px;
      text-align: center;
      box-sizing: content-box;
      border: solid 1px @primary; /* no */
      color: @primary;
      font-size: 32px;

      &.active {
        background: @primary;
        color: #fff;
      }

      &:last-child {
        border-top-right-radius: 6px;
        border-bottom-right-radius: 6px;
      }

      &:first-child {
        border-top-left-radius: 6px;
        border-bottom-left-radius: 6px;
      }
    }
  }
}
</style>
