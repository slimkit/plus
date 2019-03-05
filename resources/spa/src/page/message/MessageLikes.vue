<template>
  <div class="msgList">
    <CommonHeader>{{ $t('message.like.name') }}</CommonHeader>

    <div class="msgList-container">
      <JoLoadMore
        ref="loadmore"
        class="msgList-loadmore"
        @onRefresh="onRefresh"
        @onLoadMore="onLoadMore"
      >
        <div
          v-for="noti in likes"
          :key="noti.id"
          class="msgList-item"
        >
          <MessageLikesItem :like="noti.data">
            {{ noti.created_at | time2tips }}
          </MessageLikesItem>
        </div>
      </JoLoadMore>
    </div>
  </div>
</template>

<script>
/**
 * 消息-评论列表
 */
import * as api from '@/api/message.js'
import MessageLikesItem from './MessageLikesItem.vue'

const likeType = {
  'feeds': {
    title: '动态',
    url: '/feed/',
  },
  'news': {
    title: '资讯',
    url: '/news/',
  },
  'questions': {
    title: '问题',
    url: '/questions/',
  },
  'question-answers': {
    title: '回答',
    url: '/question-answers/',
  },
}
export default {
  name: 'MessageLikes',
  components: {
    MessageLikesItem,
  },
  data: () => ({
    likeType,
    likes: [],
  }),
  mounted () {
    this.$refs.loadmore.beforeRefresh()
  },
  methods: {
    onRefresh () {
      const type = 'like'
      api.getNotification({ type, page: this.page })
        .then(({ data }) => {
          const noMore = data.meta.last_page <= data.meta.current_page
          this.likes = data.data
          api.resetNotificationCount(type)
          this.$refs.loadmore.afterRefresh(noMore)
          if (!noMore) this.page++
        })
    },
    onLoadMore () {
      const type = 'like'
      api.getNotification({ type, page: this.page })
        .then(({ data }) => {
          const noMore = data.meta.last_page <= data.meta.current_page
          this.likes.push(...data.data)
          this.$refs.loadmore.afterLoadMore(noMore)
          if (!noMore) this.page++
        })
    },
  },
}
</script>

<style lang="less">
@import url("./style.less");
</style>
