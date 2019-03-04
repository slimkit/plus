<template>
  <div class="p-message-base">
    <CommonHeader :pinned="true" class="header">
      <span slot="left" />
      <nav class="type-switch-bar">
        <span :class="{active: currentType === 'list'}" @click="currentType = 'list'">
          <BadgeIcon :dot="unreadMessage">消息</BadgeIcon>
        </span>
        <span :class="{active: currentType === 'chats'}" @click="currentType = 'chats'">
          <BadgeIcon :dot="unreadChat">聊天</BadgeIcon>
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
    ...mapGetters({
      unreadChat: 'unreadChat',
    }),
    ...mapGetters('message', {
      unreadMessage: 'unreadMessage',
    }),
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
