<template>
  <section class="c-chat-item" @click="handelView">
    <div :class="avatarStyle" class="m-avatar-box m-avatar-box-def">
      <img v-if="avatar" :src="avatar">
    </div>
    <div class="main">
      <h2 class="m-text-cut">
        <span class="title m-text-cut">{{ item.name }}</span>
        <span>{{ userCount }}</span>
      </h2>
      <p class="m-text-cut">{{ latest.data }}</p>
    </div>
    <div class="ext">
      <span>{{ item.time | time2tips }}</span>
      <span v-show="count > 0" class="count">{{ count }}</span>
    </div>
  </section>
</template>

<script>

export default {
  name: 'ChatItem',
  props: {
    item: { type: Object, required: true },
  },
  computed: {
    type () {
      return this.item.type
    },
    info () {
      return this.item.info
    },
    avatar () {
      const avatar = this.item.avatar || {}
      return avatar.url || null
    },
    latest () {
      return this.item.latest || { data: '' }
    },
    count () {
      return this.item.unreadCount || 0
    },
    avatarStyle () {
      return this.avatar
        ? ''
        : this.type === 'chat'
          ? `m-avatar-box-${this.info.sex}`
          : `m-avatar-box-group`
    },
    userCount () {
      const { affiliations_count: count } = this.info
      return count > 0 ? `(${count})` : ''
    },
  },
  methods: {
    handelView () {
      this.$router.push({ name: 'ChatRoom', params: { chatId: this.item.id } })
    },
  },
}
</script>

<style lang="less" scoped>
.c-chat-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 135px;
  padding: 30px 20px;
  border-bottom: 1px solid @border-color; /* no */
  background-color: #fff;

  .main {
    flex: auto;
    display: flex;
    flex-direction: column;
    min-width: 0;
    margin: 0 30px;
    font-size: 28px;
    color: @text-color3;
    overflow: hidden;

    h2 {
      font-size: 32px;
      color: @text-color1;

      .title {
        display: inline-block;
        max-width: 70%;
        vertical-align: middle;
      }
    }
  }
  .ext {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    font-size: 24px;
    color: #ccc;

    .count {
      align-self: flex-end;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      height: 32px;
      width: 32px;
      color: #fff;
      border-radius: 16px;
      background-color: @error;
    }
  }
}
</style>
