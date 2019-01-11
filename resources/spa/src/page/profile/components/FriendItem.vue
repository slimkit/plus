<template>
  <li class="c-friend-item">
    <Avatar :user="user" />
    <div class="user-info">
      <h3>{{ user.name }}</h3>
      <p>{{ user.bio || $t('profile.default_bio') }}</p>
    </div>
    <svg class="m-style-svg m-svg-def" @click="startSingleChat"><use xlink:href="#icon-chat" /></svg>
  </li>
</template>

<script>
import { startSingleChat } from '@/vendor/easemob'

export default {
  props: {
    user: { type: Object, required: true },
  },
  methods: {
    startSingleChat () {
      startSingleChat(this.user).then(chatId => {
        this.$nextTick(() => {
          this.$router.push({ name: 'ChatRoom', params: { chatId } })
        })
      })
    },
  },
}
</script>

<style lang="less" scoped>
.c-friend-item {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  padding: 30px;
  border-bottom: 1px solid @border-color;

  .c-avatar {
    flex: none;
  }

  .user-info {
    flex: auto;
    margin: 0 30px;

    > p {
      color: #999;
      font-size: 28px;
    }
  }

  .m-style-svg {
    flex: none;
    margin-left: auto;
  }
}
</style>
