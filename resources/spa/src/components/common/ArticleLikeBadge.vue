<template>
  <RouterLink
    tag="div"
    class="c-article-like-badge"
    to="likers"
    append
  >
    <div class="avatar-list">
      <Avatar
        v-for="({user = {}, id}, index) in likers.slice(0, 5)"
        :key="id"
        :user="user"
        size="tiny"
        :style="{ zIndex: 5-index}"
      />
    </div>
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
    position: relative;
    flex: none;

    &::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      display: block;
      background: transparent;
      z-index: 7;
    }
  }

  .total {
    margin-left: 0.5em;
    color: @primary;
  }
}
</style>
