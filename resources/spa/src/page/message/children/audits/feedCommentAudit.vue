<template>
  <div :class="`${prefixCls}`">
    <div :class="`${prefixCls}-container`">
      <JoLoadMore
        ref="loadmore"
        :class="`${prefixCls}-loadmore`"
        @onRefresh="onRefresh"
        @onLoadMore="onLoadMore"
      >
        <div
          v-for="audit in audits"
          :key="`feed-comment-${audit.id}`"
          :class="`${prefixCls}-item`"
        >
          <div :class="`${prefixCls}-item-top`">
            <Avatar :user="audit.user" />
            <section class="userInfo">
              <RouterLink :class="`${prefixCls}-item-top-link`" :to="`/users/${audit.user_id}`">
                {{ audit.user.name }}
              </RouterLink>
              <p>{{ audit.created_at | time2tips }}</p>
            </section>
            <AuditStatusFeedComment :audit="audit" />
          </div>
          <AuditContent :audit="getAuditContent(audit)" />
        </div>
      </JoLoadMore>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'
import { mapState } from 'vuex'
import AuditStatusFeedComment from '../../components/AuditStatusFeedComment.vue'
import { getFeedCommentPinneds } from '@/api/feeds.js'
import { limit } from '@/api'
import AuditContent from '../../components/AuditContent'

const prefixCls = 'msgList'

export default {
  name: 'FeedCommentAudit',
  components: {
    AuditStatusFeedComment,
    AuditContent,
  },
  data: () => ({
    prefixCls,
    currentItem: {},
  }),
  computed: {
    ...mapState({
      audits: state => state.MESSAGE.MY_COMMENT_AUDIT,
    }),
    lastId () {
      return this.audits[this.audits.length - 1].id || 0
    },
  },
  methods: {
    onRefresh () {
      getFeedCommentPinneds().then(({ data }) => {
        if (data.length > 0) {
          this.$store.commit('SAVE_FEED_COMMENT_AUDITS', {
            type: 'new',
            data,
          })
        }
        this.$refs.loadmore.afterRefresh(data.length < limit)
      })
    },
    onLoadMore () {
      const { id = 0 } = _.head(this.audits) || {}
      if (id === 0) {
        this.$refs.loadmore.afterLoadMore(true)
        return false
      }
      getFeedCommentPinneds(this.lastId).then(({ data }) => {
        this.$refs.loadmore.afterLoadMore(data.length < limit)
        if (data.length > 0) {
          this.$store.commit('SAVE_FEED_COMMENT_AUDITS', {
            type: 'more',
            data,
          })
        }
      })
    },
    getAuditContent (audit) {
      const { feed = {}, comment = {} } = audit || {}
      return {
        image: this.getFirstImage(feed),
        commentBody: this.getCommentBody(comment),
        video: this.getVideo(feed),
        content: this.getFeedContent(feed),
        commentableDel: audit.feed === null,
        commentDel: audit.comment === null,
        type: 'feed',
        contentId: audit.feed ? feed.id : 0,
      }
    },
    // 获取评论内容
    getCommentBody (comment) {
      const { body } = comment || {}
      return body
    },
    // 获取动态内容
    getFeedContent (feed) {
      const { feed_content: content } = feed || {}
      return content
    },
    // 获取动态第一个图片
    getFirstImage (feed) {
      const { images } = feed || {}
      const { length } = images || []
      if (length > 0) {
        const [img] = images

        return img
      }

      return false
    },
    // 获取动态视频封面
    getVideo (feed) {
      const { video } = feed || {}
      if (video != null) {
        return video.cover_id
      } else {
        return false
      }
    },
  },
}
</script>
<style lang="less" src="../../style.less">
</style>
