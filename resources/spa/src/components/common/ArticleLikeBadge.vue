<template>
  <RouterLink
    tag="div"
    class="c-article-like-badge"
    to="likers"
    append
  >
    <ul class="avatar-list">
      <li
        v-for="({user = {}, id}, index) in likers.slice(0, 5)"
        :key="id"
        :style="{ zIndex: 5-index, backgroundImage: user.avatar && `url(${getAvatar(user)})`}"
        :class="`m-avatar-box-${user.sex}`"
        class="m-avatar-box tiny"
      />
    </ul>
    <span class="total">{{ total | formatNum }}人点赞</span>
  </RouterLink>
</template>

<script>
export default {
  name: 'ArticleLikeBadge',
  props: {
    likers: { type: Array, default: () => [] },
    total: { type: Number, default: 0 },
  },
  methods: {
    getAvatar (user = {}) {
      const avatar = user.avatar
      if (!avatar) return ''
      return avatar.url || ''
    },
  },
}
</script>

<style lang="less" scoped>
.c-article-like-badge {
  display: flex;
  align-items: center;

  .avatar-list {
    flex: none;
  }

  .total {
    margin-left: 0.5em;
    color: @primary;
  }
}
</style>
