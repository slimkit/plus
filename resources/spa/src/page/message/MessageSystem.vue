<template>
  <div class="p-message-system">
    <CommonHeader>{{ $t('message.system.name') }}</CommonHeader>

    <JoLoadMore
      ref="loadmore"
      @onRefresh="onRefresh"
      @onLoadMore="onLoadMore"
    >
      <section
        v-for="(notification, index) in notifications"
        :key="index"
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
  activated () {
    this.$refs.loadmore.beforeRefresh()
  },
  methods: {
    onRefresh () {
      const type = 'system'
      this.page = 1
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
        case 'user-certification':
          url = data.state === 'rejected' ? '/profile/certificate' : '/profile/certification'; break
        case 'user-currency:cash':
          url = '/currency/journal-detail'; break
        case 'user-cash':
          url = '/wallet/detail'; break

        case 'reward:feeds':
        case 'pinned:feed':
          url = `/feeds/${data.feed_id}`; break
        case 'pinned:feed/comment':
          url = `/feeds/${data.feed.id}`; break

        case 'reward:news':
        case 'pinned:news/comment':
          url = `/news/${data.news.id}`; break

        case 'qa:answer-adoption':
        case 'question:answer':
          url = `/questions/${data.question.id}/answers/${data.answer.id}`; break
        case 'qa:reward':
          url = `/questions/${data.answer.question_id}/answers/${data.answer.id}`; break
        case 'qa:invitation':
          url = `/questions/${data.question.id}`; break
        case 'qa:question-topic:accept':
          url = `/question-topics/${data.topic.id}`; break
        case 'qa:question-excellent:reject':
        case 'qa:question-excellent:accept':
          url = `/questions/${data.application.question_id}`; break

        case 'group:join':
        case 'group:transform':
          url = `/groups/${data.group.id}`; break
        case 'group:post-reward':
        case 'group:comment-pinned':
        case 'group:send-comment-pinned':
        case 'group:post-pinned':
          url = `/groups/${data.group_id}/posts/${data.post.id}`; break
        case 'group:report-comment':
        case 'group:report-post':
          url = `/groups/${data.group.id}/posts/${data.post.id}`; break
        case 'group:audit':
          url = `/groups/${data.group.id}`; break
        case 'feed:topic:create:passed':
          url = `/topic/${data.topic.id}`; break
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
