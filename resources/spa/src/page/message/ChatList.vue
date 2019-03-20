<template>
  <div class="p-chat-list">
    <JoLoadMore
      ref="loadmore"
      :show-bottom="false"
      @onRefresh="onRefresh"
    >
      <ChatItem
        v-for="(room, index) in chatRooms"
        :key="`${index}-${room.id}`"
        :item="room"
      />
    </JoLoadMore>
  </div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
import { startSingleChat } from '@/vendor/easemob/'
import ChatItem from './components/ChatItem'

export default {
  name: 'ChatList',
  components: {
    ChatItem,
  },
  data () {
    return {}
  },
  computed: {
    ...mapState({
      chatRooms: state => state.EASEMOB.chatRooms,
    }),
  },
  mounted () {
    this.initChatRooms()
  },
  methods: {
    startSingleChat,
    ...mapActions(['initChatRooms']),
    onRefresh () {
      this.initChatRooms()
        .then(() => {
          setTimeout(() => {
            this.$refs.loadmore.afterRefresh(false)
          }, 450)
        })
    },
  },
}
</script>

<style lang="less" scoped>
.p-chat-list {
  height: 100%;
}
</style>
