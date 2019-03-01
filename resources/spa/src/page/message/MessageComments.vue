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
          v-for="comment in comments"
          :key="`comment-key-${comment.id}`"
          class="msgList-item"
        >
          <Component
            :is="items[comment.commentable_type]"
            :comment="comment"
          />
        </div>
      </JoLoadMore>
    </div>
  </div>
</template>

<script>
/**
 * 消息-评论列表
 */
import { mapState } from 'vuex'
import { limit } from '@/api'
import { resetUserCount } from '@/api/message.js'
import MessageCommentFeedItem from './children/comments/MessageCommentFeedItem'
import MessageCommentNewsItem from './children/comments/MessageCommentNewsItem'
import MessageCommentQuestionItem from './children/comments/MessageCommentQuestionItem'
import MessageCommentPostItem from './children/comments/MessageCommentPostItem'
import MessageCommenAnswerItem from './children/comments/MessageCommenAnswerItem'

const prefixCls = 'msgList'
const items = {
  'feeds': MessageCommentFeedItem,
  'news': MessageCommentNewsItem,
  'group-posts': MessageCommentPostItem,
  'questions': MessageCommentQuestionItem,
  'question-answers': MessageCommenAnswerItem,
}
export default {
  name: 'MessageComments',
  data: () => ({
    prefixCls,
    items,
    refreshData: [],
  }),
  computed: {
    ...mapState({
      comments: state => state.MESSAGE.MY_COMMENTED,
    }),
  },
  watch: {
    refreshData (data) {
      if (data.length > 0) {
        this.$store.commit('SAVE_MY_COMMENTED', {
          type: 'new',
          data,
        })
      }
    },
  },
  mounted () {
    this.$refs.loadmore.beforeRefresh()
  },
  methods: {
    // 刷新服务
    onRefresh () {
      this.refreshData = []
      this.$http
        .get('/user/comments', {
          validateStatus: s => s === 200,
        })
        .then(({ data }) => {
          resetUserCount('commented')
          if (data.length > 0) {
            this.refreshData = data
          }
          this.$nextTick(() => {
            this.$refs.loadmore.afterRefresh(data.length < limit)
          })
        })
    },

    // loadmore
    onLoadMore () {
      const { id = 0 } = this.comments.slice(-1)[0] || {}
      this.$http
        .get(
          '/user/comments',
          { params: { after: id } },
          { validateStatus: s => s === 200 }
        )
        .then(({ data }) => {
          this.$store.commit('SAVE_MY_COMMENTED', {
            type: 'more',
            data,
          })
          this.$nextTick(() => {
            this.$refs.loadmore.afterLoadMore(data.length < limit)
          })
        })
    },
  },
}
</script>

<style lang="less">
@import url("./style.less");
</style>
