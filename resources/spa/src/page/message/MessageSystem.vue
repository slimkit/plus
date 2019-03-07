<template>
  <div class="p-message-system">
    <CommonHeader>{{ $t('message.system.name') }}</CommonHeader>

    <JoLoadMore
      ref="loadmore"
      @onRefresh="onRefresh"
      @onLoadMore="onLoadMore"
    >
      <section
        v-for="notification in notifications"
        :key="notification.id"
        class="notification-item"
        @click="jump(notification.data)"
      >
        <h5>{{ notification.data | getNotificationDisplay }}</h5>
        <p>{{ notification.created_at | time2tips }}</p>
      </section>
    </JoLoadMore>
  </div>
</template>

<script>
import * as api from '@/api/message'

export default {
  name: 'MessageSystem',
  data () {
    return {
      notifications: [],
      page: 1,
    }
  },
  methods: {
    onRefresh () {
      const type = 'system'
      api.getNotification({ type, page: this.page })
        .then(({ data }) => {
          const noMore = data.meta.last_page <= data.meta.current_page
          this.notifications = data.data
          api.resetNotificationCount(type)
          this.$refs.loadmore.afterRefresh(noMore)
          if (!noMore) this.page++
        })
    },
    onLoadMore () {
      const type = 'system'
      api.getNotification({ type, page: this.page })
        .then(({ data }) => {
          const noMore = data.meta.last_page <= data.meta.current_page
          this.notifications.push(...data.data)
          this.$refs.loadmore.afterLoadMore(noMore)
          if (!noMore) this.page++
        })
    },
    jump (data) {
      let url
      switch (data.type) {
        case 'reward':
          url = `/users/${data.sender.id}`; break
        case 'reward:feeds':
          url = `/feeds/${data.feed_id}`; break
        case 'reward:news':
          url = `/news/${data.news.id}`; break
        case 'group:join':
          url = `/groups/${data.group.id}`; break
        case 'user-certification':
          url = data.state === 'rejected' ? '/profile/certificate' : '/profile/certification'; break
        case 'qa:answer-adoption':
        case 'question:answer':
          url = `/question/${data.question.id}/answer/${data.answer.id}`; break
        case 'qa:reward':
          url = `/question/${data.answer.question_id}/answer/${data.answer.id}`; break
        case 'pinned:feed/comment':
          url = `/feeds/${data.feed.id}`; break
        case 'pinned:news/comment':
          url = `/news/${data.news.id}`; break
        case 'group:comment-pinned':
        case 'group:send-comment-pinned':
          url = `/groups/${data.group_id}/posts/${data.post.id}`; break
        case 'group:post-pinned':
          url = `/groups/${data.group_id}/posts/${data.post.id}`; break
      }
      this.$router.push(url)
    },
  },
}
</script>

<style lang="less" scoped>
.p-message-system {
  .notification-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 30px;
    border-bottom: 1px solid #ededed;
    background-color: #fff;

    h5 {
      color: #333;
      font-size: 28px;
      font-weight: 400;
    }

    p {
      flex: none;
      margin-left: 30px;
      color: #999;
      font-size: 24px;
    }
  }
}
</style>
