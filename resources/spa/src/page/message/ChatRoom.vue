<template>
  <div class="p-chat-room m-box-model">
    <CommonHeader>
      <span class="m-text-cut">{{ name }}</span>
      <span>{{ count }}</span>
    </CommonHeader>

    <main ref="main" class="m-box-model p-chat-room-main m-flex-grow1 m-flex-shrink1 m-main">
      <MessageBubble
        v-for="msg in messages"
        :key="msg.id"
        :msg="msg"
      />
    </main>

    <footer ref="footer" class="m-box m-aln-end m-main p-chat-room-foot m-flex-grow0 m-flex-shrink0 m-bt1">
      <form action="#" class="m-box-model m-aln-center m-justify-center m-flex-grow1 m-flex-shrink1 m-main p-chat-input">
        <textarea
          ref="textarea"
          v-model.trim="body"
          :style="{ height: `${scrollHeight}px` }"
          placeholder="随便说说~"
          @focus="onFocus"
          @keydown.enter.prevent="sendMessage"
        />
        <textarea
          ref="shadow"
          v-model.trim="shadowText"
          rows="1"
          class="shadow-input"
        />
      </form>
      <button
        :disabled="disabled || sending"
        class="m-flex-grow0 m-flex-shrink0 p-chat-button"
        @click="sendMessage"
      >
        <CircleLoading v-if="sending" />
        <span v-else>发送</span>
      </button>
    </footer>
  </div>
</template>

<script>
import WebIM, { sendTextMessage, clearUnread } from '@/vendor/easemob'
import MessageBubble from './components/MessageBubble'

export default {
  name: 'ChatRoom',
  components: {
    MessageBubble,
  },
  data () {
    return {
      body: '',
      room: {},
      messages: [],
      scrollHeight: 0,
      sending: false,
    }
  },
  computed: {
    CURRENTUSER () {
      return this.$store.state.CURRENTUSER
    },
    roomId () {
      return this.$route.params.chatId
    },
    name () {
      return this.room.name
    },
    count () {
      const {
        info: { affiliations_count: count } = {
          info: { affiliations_count: 0 },
        },
      } = this.room
      return count > 0 ? `(${count})` : ''
    },
    shadowText () {
      return '圈' + this.body
    },
    disabled () {
      return !this.body.length > 0
    },
  },
  watch: {
    body (val, old) {
      if (val !== old) {
        this.$lstore.setData(`H5_CHAT_INPUT_${this.roomId}`, val)
        this.$nextTick(() => {
          this.$refs.shadow &&
            (this.scrollHeight = this.$refs.shadow.scrollHeight)
        })
      }
    },
    messages () {
      this.$nextTick(() => {
        const scrollTop =
          this.$refs.main.scrollHeight - this.$refs.main.offsetHeight
        this.$refs.main.scrollTop = scrollTop
      })
    },
  },
  mounted () {
    this.init()
    this.$nextTick(() => {
      const room = this.$store.getters.getRoomById(this.roomId)[0]
      if (room) {
        this.room = room
        this.$bus.$on('UpdateRoomMessages', () => {
          room.messages().then(msgs => {
            this.messages = msgs
          })
        })
        room.messages().then(msgs => {
          this.messages = msgs
        })
      } else {
        this.$Message.error('错误的会话列表')
      }
    })
  },
  methods: {
    init () {
      this.$nextTick(() => {
        /**
         * 提取临时输入
         */
        this.$lstore.hasData(`H5_CHAT_INPUT_${this.roomId}`) &&
          (this.body = this.$lstore.getData(`H5_CHAT_INPUT_${this.roomId}`))

        /**
         * 设置输入框的高度
         */
        this.$refs.shadow &&
          (this.scrollHeight = this.$refs.shadow.scrollHeight)
        /**
         * 设置页面高度
         * @type {[type]}
         */
        this.$el.style.height = window.innerHeight + 'px'
        /**
         * 清空已读信息
         */
        clearUnread(this.roomId)
      })
    },
    sendMessage () {
      if (WebIM.conn.isOpened()) {
        if (this.body.length > 0 && !this.sending) {
          this.sending = true
          sendTextMessage({
            to: this.roomId,
            from: this.CURRENTUSER.id,
            body: this.body,
            type: this.room.type,
            bySelf: 1,
            user: this.CURRENTUSER,
            info: this.CURRENTUSER,
          }).then(() => {
            this.body = ''
            this.sending = false
          })
        }
      } else {
        this.$Message.error('与服务器断开连接, 请刷新重连')
        this.sending = false
      }
    },
    onFocus () {
      /**
       * 兼容 IOS 键盘弹起
       * @author jsonleex <jsonlseex@163.com>
       */
      setTimeout(() => {
        const wH2 = window.innerHeight
        window.scrollTo(0, wH2 - 70)
      }, 350)
    },
  },
}
</script>
<style lang="less">
.p-chat-room {
  .p-chat-button {
    width: 100px;
    height: 26 * 1.5 + 20px;
    border-radius: 10px;
    line-height: normal;
    color: #fff;
    font-size: 24px;
    background-color: @primary;

    &[disabled] {
      background-color: #ccc;
    }
  }
}
.p-chat-room-main {
  padding: 20px;
  overflow: auto;
  -webkit-overflow-scrolling: touch;
  .message-item {
    + .message-item {
      margin-top: 30px;
    }
  }
}
.p-chat-room-foot {
  line-height: 1;
  overflow: hidden;
  padding: 10px;
}
.p-chat-input {
  position: relative;
  overflow: hidden;
  padding: 10px;
  margin-right: 30px;
  border-radius: 10px;
  border: 1px solid @border-color; /* no */
  textarea {
    font-size: 26px;
    line-height: 1.5;
    width: 100%;
    resize: none;
    max-height: 26 * 1.5 * 4 + 20px;
    -webkit-overflow-scrolling: touch;
  }
  .shadow-input {
    border-radius: 0;
    padding: 0;
    position: absolute;
    width: 100%;
    z-index: -9999;
    visibility: hidden;
  }
}
</style>
