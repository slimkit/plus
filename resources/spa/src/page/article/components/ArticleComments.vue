<template>
  <div class="c-article-comments">
    <div v-if="!pinneds.length && !comments.length" class="m-no-content" />
    <div
      v-else
      id="comment-head"
      class="m-box-model m-art-comments"
    >
      <ul class="m-box m-aln-center m-art-comments-tabs">
        <li>{{ total | formatNum | t('article.comment_count') }}</li>
      </ul>
      <ArticleCommentItem
        v-for="(comment) in pinneds"
        :key="`pinned-${comment.id}`"
        :comment="comment"
        :pinned="true"
        @click="$emit('reply', comment)"
      />
      <ArticleCommentItem
        v-for="(comment) in comments"
        :id="`comment-${comment.id}`"
        :key="`comment-${comment.id}`"
        :comment="comment"
        @click="$emit('reply', comment)"
      />
      <div class="m-box m-aln-center m-justify-center load-more-box">
        <span v-if="noMore" class="load-more-ph">{{ $t('loadmore.bottom[1]') }}</span>
        <span
          v-else
          class="load-more-btn"
          @click.stop="fetch(maxCommentId)"
        >
          {{ fetching ? $t('loadmore.bottom[0]') : $t('loadmore.bottom[2]') }}
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import { limit } from '@/api'
import * as feedApi from '@/api/feeds'
import * as newsApi from '@/api/news'
import ArticleCommentItem from './ArticleCommentItem'
import i18n from '@/i18n'

const typeMap = {
  feed: {
    title: i18n.t('feed.name'),
    getComments: feedApi.getFeedComments,
    postComment: feedApi.postFeedComment,
    deleteComment: feedApi.deleteFeedComment,
  },
  news: {
    title: i18n.t('news.name'),
    getComments: newsApi.getNewsComments,
    postComment: newsApi.postNewsComment,
    deleteComment: newsApi.deleteNewsComment,
  },
}

export default {
  name: 'ArticleComments',
  components: {
    ArticleCommentItem,
  },
  props: {
    type: { type: String, required: true, validator: type => Object.keys(typeMap).includes(type) },
    article: { type: Number, required: true },
    total: { type: Number, default: 0 },
    fetching: { type: Boolean, default: false },
  },
  data () {
    return {
      comments: [],
      pinneds: [],
      noMore: false,
    }
  },
  computed: {
    maxCommentId () {
      const lastComment = [...this.comments].pop() || {}
      return lastComment.id
    },
    factory () {
      return typeMap[this.type]
    },
  },
  methods: {
    fetch (after) {
      this.factory.getComments(this.article, { after })
        .then(({ data: { pinneds = [], comments = [] } }) => {
          if (!after) {
            this.pinneds = pinneds
            // 过滤第一页中的置顶评论
            const pinnedIds = pinneds.map(p => p.id)
            this.comments = comments.filter(c => !pinnedIds.includes(c.id))
          } else {
            this.comments = [...this.comments, ...comments]
          }
          this.noMore = comments.length < limit
        })
        .finally(() => {
          this.fetching = false
        })
    },
    open (replyUser = {}) {
      let placeholder
      if (replyUser.name) placeholder = `${this.$t('reply.name')} ${replyUser.name}：`
      this.$bus.$emit('commentInput', {
        placeholder,
        onOk: body => void this.sendComment(body, replyUser.id),
      })
    },
    sendComment (body, replyUser) {
      if (!body) return this.$Message.error(this.$t('comment.empty'))
      const params = { body }
      if (replyUser) params['reply_user'] = replyUser
      this.factory.postComment(this.article, { body, reply_user: replyUser })
        .then(async comment => {
          this.$Message.success(this.$t('comment.success'))
          this.$bus.$emit('commentInput:close', true)
          this.fetch()
          this.goAnchor('#comment-head')
          this.$emit('update:total', 1)
        })
        .catch(() => {
          this.$Message.error(this.$t('delete.failed'))
          this.$bus.$emit('commentInput:close', true)
        })
    },

    delete (commentId) {
      const actions = [
        { text: this.$t('delete.name'), method: () => void this.deleteComment(commentId) },
      ]
      setTimeout(() => {
        this.$bus.$emit('actionSheet', actions, this.$t('cancel'), this.$t('delete.confirm'))
      }, 200)
    },
    deleteComment (commentId) {
      this.factory.deleteComment(this.article, commentId)
        .then(() => {
          this.$emit('deleteComment', commentId)
          this.comments = this.comments.filter(c => c.id !== commentId)
          this.pinneds = this.pinneds.filter(c => c.id !== commentId)
          this.$emit('update:total', -1)
          this.$Message.success(this.$t('comment.delete.success'))
        })
    },
  },
}
</script>
