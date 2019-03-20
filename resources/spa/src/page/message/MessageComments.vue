<template>
  <div class="msgList">
    <CommonHeader :pinned="true">{{ $t('message.comment.name') }}</CommonHeader>

    <div class="msgList-container">
      <JoLoadMore
        ref="loadmore"
        class="msgList-loadmore"
        @onRefresh="onRefresh"
        @onLoadMore="onLoadMore"
      >
        <div
          v-for="noti in comments"
          :key="noti.id"
          class="msgList-item"
        >
          <MessageCommentsItem :comment="noti.data">
            {{ noti.created_at | time2tips }}
          </MessageCommentsItem>
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
import MessageCommentsItem from './MessageCommentsItem.vue'

export default {
  name: 'MessageComments',
  components: {
    MessageCommentsItem,
  },
  data: () => ({
    comments: [],
  }),
  mounted () {
    this.$refs.loadmore.beforeRefresh()
  },
  methods: {
    onRefresh () {
      const type = 'comment'
      api.getNotification({ type, page: this.page })
        .then(({ data }) => {
          const noMore = data.meta.last_page <= data.meta.current_page
          this.comments = data.data
          api.resetNotificationCount(type)
          this.$refs.loadmore.afterRefresh(noMore)
          if (!noMore) this.page++
        })
    },
    onLoadMore () {
      const type = 'comment'
      api.getNotification({ type, page: this.page })
        .then(({ data }) => {
          const noMore = data.meta.last_page <= data.meta.current_page
          this.comments.push(...data.data)
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
