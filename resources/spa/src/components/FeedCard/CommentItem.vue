<template>
  <p class="m-text-box">
    <RouterLink
      :to="`/users/${user.id}`"
      tag="span"
      exact
      class="m-comment-usr"
    >
      <a>{{ user.name }}</a>
    </RouterLink>
    <span
      v-if="replyUser"
      class="m-comment-usr"
    >
      回复<RouterLink :to="`/users/${replyUser.id}`">{{ replyUser.name }}</RouterLink>
    </span>
    <span
      class="m-comment-body"
      @click="handelClick"
    >
      {{ body }}
    </span>
    <span
      v-if="pinned"
      class="m-art-comment-icon-top"
      style="margin-left: 5px; height: auto"
    >
      置顶
    </span>
  </p>
</template>
<script>
export default {
  name: 'CommentItem',
  props: {
    comment: { type: Object, required: true },
  },
  computed: {
    isMine () {
      return this.$store.state.CURRENTUSER.id === this.user.id
    },
    user () {
      return this.comment.user || {}
    },
    replyUser () {
      const { reply } = this.comment
      return reply && reply.id ? reply : null
    },
    pinned () {
      return this.comment.pinned
    },
    body () {
      return this.comment.body || ''
    },
  },
  mounted () {
    this.user && this.$store.commit('SAVE_USER', this.user)
    this.replyUser && this.$store.commit('SAVE_USER', this.replyUser)
  },
  methods: {
    handelClick () {
      const p = this.isMine
        ? {
          isMine: true,
        }
        : {
          isMine: false,
          placeholder: `回复${this.user.name}`,
          reply_user: this.user.id,
        }
      this.$emit('click', Object.assign({ comment: this.comment }, p))
    },
  },
}
</script>

<style lang="less" scoped>
.m-comment-usr a {
  margin: 0 5px;
  color: @text-color1;
}
.m-comment-body:before {
  margin-left: -5px;
  content: "：";
  color: @text-color3;
}
</style>
