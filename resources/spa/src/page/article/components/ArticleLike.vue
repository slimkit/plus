<template>
  <div class="c-article-like">
    <RouterLink
      class="like-list"
      tag="div"
      to="likers"
      append
    >
      <template v-if="list.length && likeCount">
        <div class="avatar-list">
          <Avatar
            v-for="({user = {}, id}, index) in list"
            :key="id"
            :user="user"
            :style="{ zIndex: 5 - index}"
            :readonly="true"
            class="liker-avatar"
            size="tiny"
          />
        </div>
        <span class="like-count">{{ likeCount | formatNum | t('article.like_count') }}</span>
      </template>
    </RouterLink>

    <div class="article-info">
      <span v-if="time">{{ time | time2tips | t('article.posted_at') }}</span>
      <span>{{ viewCount | formatNum | t('article.views_count') }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ArticleLike',
  filters: {
    getAvatar (user = {}) {
      const avatar = user.avatar
      if (!avatar) return ''
      return avatar.url || ''
    },
  },
  props: {
    likers: { type: Array, default: () => [] },
    likeCount: { type: Number, default: 0 },
    time: { type: [Date, String, Number], required: true },
    viewCount: { type: Number, default: 0 },
  },
  computed: {
    list () {
      return this.likers.slice(0, 5)
    },
  },
}
</script>

<style lang="less" scoped>
.c-article-like {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 50px 20px;
  font-size: 24px;
  color: #ccc;

  .like-list {
    flex: auto;
    display: flex;
    align-items: center;
    color: @primary;

    .avatar-list {
      position: relative;
      flex: none;

      .liker-avatar {
        width: 50px;
        height: 50px;
      }
    }
  }

  .like-count {
    margin-left: 0.5em;
    color: @primary;
  }

  .article-info {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    line-height: 30px;
  }
}
</style>
