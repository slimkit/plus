<template>
  <div class="message-item">
    <div v-if="user.id === 0" class="room-tips">
      <span>{{ body }}</span>
    </div>
    <div
      v-else
      :class="{selef: bySelef}"
      class="m-box m-aln-st msg-bubble"
    >
      <Avatar :user="user" />
      <section class="m-box-model m-aln-st msg-bubble-main">
        <h2 class="msg-bubble-user-name m-text-cut">{{ user.name }}</h2>
        <p class="msg-bubble-body">{{ body }}</p>
      </section>
    </div>
  </div>
</template>
<script>
import { mapState } from 'vuex'

export default {
  name: 'MessageBubble',
  props: {
    msg: { type: [Object, String], default: '' },
  },
  data () {
    return {}
  },
  computed: {
    ...mapState(['CURRENTUSER']),
    type () {
      return this.msg.type
    },
    bySelef () {
      return this.msg.bySelf
    },
    source () {
      return this.msg.source
    },
    body () {
      return this.source.data
    },
    user () {
      return this.msg.user
    },
  },
}
</script>

<style lang="less" scoped>
.msg-bubble {
  &-main {
    margin: 0 20px;
    max-width: 520px;
  }
  &-user-name {
    font-size: 26px;
    color: @text-color3;
    line-height: 1;
    margin-bottom: 15px;
    margin-top: 15px;
  }
  &-body {
    padding: 20px;
    background-color: #f7f7f7;
    border: 1px solid #dee1e2; /* no */
    border-radius: 0 20px 20px;
  }
  &.selef {
    flex-direction: row-reverse;
    .msg-bubble-main {
      align-items: flex-end;
    }
    .msg-bubble-body {
      border-radius: 20px 0 20px 20px;
      border-color: #87c6dd;
      background-color: #b3e1f2;
    }
  }
  .m-avatar-box {
    margin: inherit;
  }
}
.room-tips {
  margin-left: 96px;
  margin-right: 96px;
  text-align: center;
  span {
    display: inline-block;
    padding: 5px 15px;
    color: #fff;
    font-size: 20px;
    line-height: 1.5;
    border-radius: 20px;
    background-color: #d9d9d9;
  }
}
</style>
