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
        <h5>{{ notification.data | getDisplayText }}</h5>
        <p>{{ notification.created_at | time2tips }}</p>
      </section>
    </JoLoadMore>
  </div>
</template>

<script>
import * as api from '@/api/message'

export default {
  name: 'MessageSystem',
  filters: {
    getDisplayText (data) {
      switch (data.type) {
        case 'user':
          return `${data.user.name}打赏了你`
        case 'reward:feeds':
          return `${data.sender.name}打赏了你的动态`
        case 'reward:news':
          return `你的资讯《${data.news.title}》被${data.sender.name}打赏了${data.amount}${data.unit}`
        case 'group:join':
          return data.state === 'rejected'
            ? `拒绝用户加入圈子「${data.group.name}」`
            : `${data.user ? data.user.name : ''}请求加入圈子「${data.group.name}」`
        case 'user-certification':
          return data.state === 'rejected'
            ? `你申请的身份认证已被驳回，驳回理由：${data.contents}`
            : `你申请的身份认证已通过`
        case 'qa:answer-adoption':
        case 'question:answer':
          return `你提交的问题回答被采纳`
        case 'qa:reward':
          return `${data.user.name}打赏了你的回答`
        case 'pinned:feed/comment':
          return data.state === 'rejected'
            ? `拒绝用户动态评论「${data.comment.contents}」的置顶请求`
            : `同意用户动态评论「${data.comment.contents}」的置顶请求`
        case 'pinned:news/comment':
          return data.state === 'rejected'
            ? `拒绝用户关于资讯《${data.news.title}》评论「${data.comment.contents}」的置顶请求`
            : `同意用户关于资讯《${data.news.title}》评论「${data.comment.contents}」的置顶请求`
        case 'group:comment-pinned':
        case 'group:send-comment-pinned':
          return data.state === 'rejected'
            ? `拒绝帖子「${data.post.title}」的评论置顶请求`
            : `同意帖子「${data.post.title}」的评论置顶请求`
        case 'group:post-pinned':
          return data.state === 'rejected'
            ? `拒绝用于帖子「${data.post.title}」的置顶请求`
            : `同意用于帖子「${data.post.title}」的置顶请求`
      }
    },
  },
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
        case 'user':
          url = `/user/${data.user.id}`; break
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
        case 'qa:reward':
          url = `/question/${data.question_id}/answer/${data.answer_id}`; break
        case 'pinned:feed/comment':
          url = `/feeds/${data.feed_id}`; break
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
