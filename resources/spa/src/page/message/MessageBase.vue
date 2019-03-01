<template>
  <div class="p-message-base">
    <CommonHeader :pinned="true" class="header">
      <span slot="left" />
      <nav class="type-switch-bar">
        <span :class="{active: currentType === 'list'}" @click="currentType = 'list'">
          <VBadge :dot="hasUnreadMessage">消息</VBadge>
        </span>
        <span :class="{active: currentType === 'chats'}" @click="currentType = 'chats'">
          <VBadge :dot="hasUnreadChat">聊天</VBadge>
        </span>
      </nav>
    </CommonHeader>

    <main>
      <RouterView />
    </main>
    <FootGuide />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'MessageBase',
  computed: {
    ...mapGetters(['hasUnreadChat', 'hasUnreadMessage']),
    currentType: {
      get () {
        const { path } = this.$route
        return path.match(/^\/message\/(\S+)$/)[1]
      },
      set (val) {
        const routeName = val === 'list' ? 'MessageHome' : 'ChatList'
        this.$router.replace({ name: routeName })
      },
    },
  },
  created () {
    this.$store.dispatch('GET_UNREAD_COUNT')
    this.$store.dispatch('GET_NEW_UNREAD_COUNT')
  },
}
</script>

<style lang="less">
.p-message-base {
  .header {
    .v-badge-dot {
      right: -22px;
      top: -6px;
    }
  }
}
</style>
