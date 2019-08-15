<template>
  <div class="m-box-model m-card c-feed-card" @click="handleView('')">
    <div class="m-box main">
      <template v-if="timeLine">
        <div v-if="isToday">{{ $t('date.today') }}</div>
        <div v-else class="timeline-text">
          <span>{{ time.getDate() }}</span>
          <span class="month">{{ $t(`date.months[${time.getMonth()}]`) }}</span>
        </div>
      </template>
      <Avatar v-else :user="user" />
      <section class="m-box-model m-card-main">
        <header v-if="!timeLine" class="m-box m-aln-center m-justify-bet m-card-usr">
          <h4 class="m-flex-grow1 m-flex-shrink1">{{ user.name }}</h4>
          <div class="m-box m-aln-center">
            <span v-if="pinned" class="m-art-comment-icon-top">{{ $t('pinned') }}</span>
            <span>{{ time | time2tips }}</span>
          </div>
        </header>
        <article class="m-card-body" @click="handleView('')">
          <h2 v-if="title">{{ title }}</h2>
          <div v-if="body.length > 0" class="m-card-con">
            <p
              :class="{needPay}"
              class="m-text-box m-text-cut-3 feed-body m-text-pre"
              v-html="replaceURI(body)"
            />
          </div>
          <FeedImage
            v-if="images.length > 0"
            :id="feedId"
            :pics="images"
          />
          <FeedVideo
            v-if="video"
            :id="feedId"
            :video="video"
          />
          <ul v-if="topics.length" class="topics">
            <li
              v-for="topic in topics"
              v-if="topic.id !== currentTopic"
              :key="topic.id"
              class="topic-item"
              @click.capture.stop="viewTopic(topic.id)"
              v-text="topic.name"
            />
          </ul>
        </article>
      </section>
    </div>
    <footer
      v-if="showFooter"
      class="m-box-model m-card-foot m-bt1"
      @click.stop
    >
      <div class="m-box m-aln-center m-card-tools m-lim-width">
        <a class="m-box m-aln-center" @click.prevent="handleLike">
          <svg class="m-style-svg m-svg-def">
            <use :xlink:href="liked ? '#icon-like' :'#icon-unlike'" />
          </svg>
          <span :class="{liked}">{{ likeCount | formatNum }}</span>
        </a>
        <a class="m-box m-aln-center" @click.prevent="handleComment">
          <svg class="m-style-svg m-svg-def">
            <use xlink:href="#icon-comment" />
          </svg>
          <span>{{ commentCount | formatNum }}</span>
        </a>
        <a class="m-box m-aln-center" @click.prevent="handleView('')">
          <svg class="m-style-svg m-svg-def">
            <use xlink:href="#icon-eye" />
          </svg>
          <span>{{ viewCount | formatNum }}</span>
        </a>
        <div class="m-box m-justify-end m-flex-grow1 m-flex-shrink1">
          <a class="m-box m-aln-center" @click.prevent="handleMore">
            <svg class="m-style-svg m-svg-def">
              <use xlink:href="#icon-more" />
            </svg>
          </a>
        </div>
      </div>
      <ul v-if="commentCount > 0" class="m-card-comments">
        <li
          v-for="com in comments"
          v-if="com.id"
          :key="com.id"
        >
          <CommentItem :comment="com" @click="commentAction" />
        </li>
      </ul>
      <div
        v-if="commentCount > 5"
        class="m-router-link"
        @click="handleView('comment_list')"
      >
        <a>{{ $t('article.view_all_comments') }}</a>
      </div>
    </footer>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { escapeHTML } from '@/filters.js'
import { transTime } from '@/util'
import * as api from '@/api/feeds.js'
import FeedImage from './FeedImage.vue'
import FeedVideo from './FeedVideo.vue'
import CommentItem from './CommentItem.vue'

export default {
  name: 'FeedCard',
  components: {
    FeedImage,
    CommentItem,
    FeedVideo,
  },
  props: {
    timeLine: { type: Boolean, default: false },
    pinned: { type: Boolean, default: false },
    feed: { type: Object, required: true },
    showFooter: { type: Boolean, default: true },
    currentTopic: { type: Number, default: 0 },
  },
  computed: {
    ...mapState(['CURRENTUSER']),
    isMine () {
      return this.feed.user_id === this.CURRENTUSER.id
    },
    feedId () {
      return this.feed.id
    },
    comments: {
      get () {
        return this.feed.comments.slice(0, 5)
      },
      set (val) {
        this.feed.comments = val
      },
    },
    liked: {
      get () {
        return !!this.feed.has_like
      },
      set (val) {
        this.feed.has_like = val
      },
    },
    likeCount: {
      get () {
        return this.feed.like_count || 0
      },
      set (val) {
        this.feed.like_count = ~~val
      },
    },
    commentCount: {
      get () {
        return this.feed.feed_comment_count || 0
      },
      set (val) {
        this.feed.feed_comment_count = val
      },
    },
    viewCount () {
      return this.feed.feed_view_count || 0
    },
    time () {
      let str = this.feed.created_at
      return transTime(str)
    },
    isToday () {
      // 时间差 = 当前时间 - date (单位: 秒)
      let offset = (new Date() - this.time) / 1000
      if (offset / 3600 < 24) return true
      return false
    },
    user () {
      const user = this.feed.user
      return user && user.id ? user : {}
    },
    needPay () {
      const { paid_node: node } = this.feed
      return node && !node.paid
    },
    images () {
      return this.feed.images || []
    },
    video () {
      return this.feed.video || false
    },
    topics () {
      return this.feed.topics || []
    },
    body () {
      return this.feed.feed_content || ''
    },
    has_collect: {
      get () {
        return this.feed.has_collect
      },
      set (val) {
        this.feed.has_collect = val
      },
    },
    title () {
      return this.feed.title || ''
    },
  },
  mounted () {
    this.user && this.$store.commit('SAVE_USER', this.user)
  },
  methods: {
    viewTopic (topicId) {
      this.$router.push({ name: 'TopicDetail', params: { topicId } })
    },
    replaceURI (str) {
      // XSS filter
      str = escapeHTML(str)

      const reg = /(https?|http|ftp|file):\/\/[-A-Za-z0-9+&@#/%?=~_|!:,.;]+[-A-Za-z0-9+&@#/%=~_|]/g
      const linkText = this.$t('article.link_text')
      return str
        ? str.replace(
          reg,
          link =>
            `<a class="m-art-links" href="${link}" onclick='event.stopPropagation()' target="__blank">#${linkText}#</a>`
        )
        : ''
    },
    handleView (hash) {
      const path = hash
        ? `/feeds/${this.feedId}#${hash}`
        : `/feeds/${this.feedId}`
      const { paid_node: node } = this.feed
      node && !node.paid
        ? this.$lstore.hasData('H5_ACCESS_TOKEN')
          ? this.$bus.$emit('payfor', {
            onCancel: () => {},
            onSuccess: data => {
              this.$Message.success(data)
              this.$router.push(path)
            },
            nodeType: this.$t('article.content'),
            node: node.node,
            amount: node.amount,
          })
          : this.$nextTick(() => {
            const path = this.$route.fullPath
            this.$router.push({
              path: '/signin',
              query: { redirect: path },
            })
          })
        : this.$router.push(path)
    },
    handleLike () {
      const method = this.liked ? 'delete' : 'post'
      const url = this.liked
        ? `/feeds/${this.feedId}/unlike`
        : `/feeds/${this.feedId}/like`
      if (this.fetching) return
      this.fetching = true
      this.$http({
        method,
        url,
        validateStatus: s => s === 201 || s === 204,
      })
        .then(() => {
          if (method === 'post') {
            this.liked = true
            this.likeCount += 1
          } else {
            this.liked = false
            this.likeCount -= 1
          }
        })
        .finally(() => {
          this.fetching = false
        })
    },
    handleComment ({ placeholder, reply_user: user }) {
      this.$bus.$emit('commentInput', {
        placeholder,
        onOk: text => {
          this.sendComment({ body: text, reply_user: user })
        },
      })
    },
    handleMore () {
      const actions = []
      if (this.has_collect) {
        actions.push({
          text: this.$t('collect.cancel'),
          method: () => {
            api.uncollectFeed(this.feedId).then(() => {
              this.$Message.success(this.$t('collect.cancel'))
              this.has_collect = false
            })
          },
        })
      } else {
        actions.push({
          text: this.$t('collect.name'),
          method: () => {
            api.collectionFeed(this.feedId).then(() => {
              this.$Message.success(this.$t('collect.success'))
              this.has_collect = true
            })
          },
        })
      }
      if (this.isMine) {
        // 是否是自己文章的评论
        actions.push({
          text: this.$t('feed.apply_top'),
          method: () => {
            this.popupBuyTS()
          },
        })
        actions.push({
          text: this.$t('feed.delete'),
          method: () => {
            setTimeout(() => {
              const actionSheet = [
                {
                  text: this.$t('delete.name'),
                  style: { color: '#f4504d' },
                  method: () => {
                    api.deleteFeed(this.feedId).then(() => {
                      this.$Message.success(this.$t('delete.success'))
                      this.$nextTick(() => {
                        this.$el.remove()
                        this.$emit('afterDelete')
                      })
                    })
                  },
                },
              ]
              this.$bus.$emit('actionSheet', actionSheet, this.$t('cancel'), this.$t('delete.confirm'))
            }, 200)
          },
        })
      } else {
        actions.push({
          text: this.$t('report.name'),
          method: () => {
            this.$bus.$emit('report', {
              type: 'feed',
              payload: this.feedId,
              username: this.user.name,
              reference: this.body,
            })
          },
        })
      }

      this.$bus.$emit('actionSheet', actions)
    },
    commentAction ({ isMine = false, placeholder, reply_user: user, comment }) {
      const actions = []
      if (isMine) {
        const isOwner = this.feed.user.id === this.CURRENTUSER.id
        actions.push({
          text: this.$t(isOwner ? 'comment.top.name' : 'comment.top.apply'),
          method: () => {
            this.$bus.$emit('applyTop', {
              isOwner,
              type: 'feedComment',
              api: api.applyTopFeedComment,
              payload: { feedId: this.feedId, commentId: comment.id },
            })
          },
        })
        actions.push({
          text: this.$t('comment.delete.name'),
          method: () => this.deleteComment(comment.id),
        })
      } else {
        actions.push({
          text: this.$t('reply.name'),
          method: () => {
            this.handleComment({
              placeholder,
              reply_user: user,
            })
          },
        })
        actions.push({
          text: this.$t('report.name'),
          method: () => {
            this.$bus.$emit('report', {
              type: 'comment',
              payload: comment.id,
              username: comment.user.name,
              reference: comment.body,
            })
          },
        })
      }
      this.$bus.$emit('actionSheet', actions)
    },
    sendComment ({ reply_user: replyUser, body }) {
      if (body && body.length === 0) { return this.$Message.error(this.$t('comment.empty')) }

      const params = {
        body,
        reply_user: replyUser,
      }
      api
        .postFeedComment(this.feedId, params)
        .then(comment => {
          comment.user = this.$store.state.CURRENTUSER
          const comments = Object.assign([], this.comments)
          comments.unshift(comment)
          if (comments.length > 5) comments.pop()
          this.$store.dispatch('feed/updateSingleFeed', {
            id: this.feedId,
            data: { comments, feed_comment_count: this.feed.feed_comment_count + 1 },
          })
          this.$Message.success(this.$t('comment.success'))
          this.$bus.$emit('commentInput:close', true)
        })
        .catch(() => {
          this.$bus.$emit('commentInput:close', true)
        })
    },
    deleteComment (commentId) {
      api.deleteFeedComment(this.feedId, commentId).then(() => {
        const comments = Object.assign([], this.feed.comments.filter(c => c.id !== commentId))
        this.$store.dispatch('feed/updateSingleFeed', {
          id: this.feedId,
          data: { comments, feed_comment_count: this.feed.feed_comment_count - 1 },
        })
        this.$Message.success(this.$t('comment.delete.success'))
      })
    },
  },
}
</script>

<style lang="less" scoped>
.c-feed-card {
  padding: 30px 20px 0;
  box-sizing: border-box;
  background-color: #fff;

  .main {
    padding-bottom: 20px;
  }

  .timeline-text {
    flex: none;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    width: 60px;
    font-size: 44px;

    .month {
      font-size: 24px;
      letter-spacing: 1px;/* no */
    }
  }

  .topics {
    display: flex;
    flex-wrap: wrap;
    padding: 20px 0 0;

    .topic-item {
      padding: 6px 16px;
      border-radius: 6px;
      background-color: rgba(145, 209, 232, 0.12);
      font-size: 24px;
      color: @primary;
      margin-right: 12px;
      margin-bottom: 12px;
      cursor: pointer;
    }
  }
}

.m-card {
  &-usr {
    font-size: 24px;
    color: #ccc;
    margin-bottom: 30px;
    h4 {
      color: #000;
      font-size: 26px;
    }
    span + span {
      margin-left: 15px;
    }
  }
  &-main {
    flex: auto;
    margin-left: 20px;
  }
  &-con {
    overflow: hidden;
    font-size: 30px;
    line-height: 42px;
    color: @text-color2;
    display: -webkit-box;
    margin-bottom: 20px;
    .needPay:after {
      content: " 付费节点，购买后方可查看原文详情 付费节点，购买后方可查看原文详情 付费节点，购买后方可查看原文详情";
      text-shadow: 0 0 10px @text-color2; /* no */
      color: rgba(255, 255, 255, 0);
      margin-left: 5px;
      // filter: DXImageTransform.Microsoft.Blur(pixelradius=2);
      zoom: 1;
    }
  }
  &-body {
    > h2 {
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .feed-body {
      width: 100%;
    }
  }
  &-foot {
    margin-left: -20px;
    margin-right: -20px;
    padding: 0 20px 0 120px;

    .liked {
      color: @error;
    }
  }
  &-tools {
    padding: 30px 0;
    color: #b3b3b3;
    font-size: 24px;
    a {
      color: inherit;
      + a {
        margin-left: 60px;
      }
    }
    span {
      margin-left: 10px;
    }
  }
  &-comments {
    margin-bottom: 30px;
    line-height: 42px;
    color: @text-color3;
    font-size: 26px;
  }
}

.m-router-link {
  a {
    color: inherit;
  }
  font-size: 26px;
  color: @text-color1;
  margin-top: -15px;
  margin-bottom: 30px;
}
</style>
