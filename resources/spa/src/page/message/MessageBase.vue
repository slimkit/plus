<template>
  <div class="p-message-base">
    <header class="m-box m-head-top m-lim-width m-pos-f m-main m-bb1">
      <ul class="m-box m-flex-grow1 m-aln-center m-justify-center m-flex-base0 m-head-nav">
        <RouterLink
          class="link-item"
          tag="li"
          :to="{name: 'MessageHome'}"
          replace
          active-class="active"
        >
          <VBadge :dot="has_msg">
            <a>消息</a>
          </VBadge>
        </RouterLink>
        <RouterLink
          class="link-item"
          tag="li"
          :to="{name: 'ChatList'}"
          replace
          active-class="active"
        >
          <VBadge :dot="hasUnreadChat > 0">
            <a>聊天</a>
          </VBadge>
        </RouterLink>
      </ul>
    </header>
    <main style="padding-top: 0.9rem">
      <RouterView />
    </main>
    <FootGuide />
  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'

export default {
  name: 'MessageBase',
  computed: {
    ...mapState({
      // 新消息提示
      has_msg: state =>
        state.MESSAGE.NEW_UNREAD_COUNT.commented +
          state.MESSAGE.NEW_UNREAD_COUNT['feed-comment-pinned'] +
          state.MESSAGE.NEW_UNREAD_COUNT['group-join-pinned'] +
          state.MESSAGE.NEW_UNREAD_COUNT.liked +
          state.MESSAGE.NEW_UNREAD_COUNT['news-comment-pinned'] +
          state.MESSAGE.NEW_UNREAD_COUNT['post-comment-pinned'] +
          state.MESSAGE.NEW_UNREAD_COUNT['post-pinned'] +
          state.MESSAGE.NEW_UNREAD_COUNT.system >
        0,
    }),
    ...mapGetters(['hasUnreadChat']),
  },
  created () {
    this.$store.dispatch('GET_UNREAD_COUNT')
    this.$store.dispatch('GET_NEW_UNREAD_COUNT')
  },
}
</script>

<style lang="less" scoped>
.p-message-base {
  .link-item {
    position: relative;

    a {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 2.3em;
      height: 1.5em;
    }
  }
}
</style>
