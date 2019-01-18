<template>
  <ArticleCard
    :liked="liked"
    :loading="loading"
    @on-like="likeFeed"
    @on-share="shareFeed"
    @on-more="moreAction"
    @on-comment="commentFeed"
  >
    <CommonHeader slot="head">
      <Avatar :user="user" size="tiny" />
      <span class="m-text-cut m-flex-none username">
        {{ user.name }}
      </span>
      <template
        v-if="!isMine"
        slot="right"
        :class="{ primary: relation.status !== 'unFollow' }"
      >
        <svg class="m-style-svg m-svg-def" @click="followUserByStatus(relation.status)">
          <use :xlink:href="relation.icon" />
        </svg>
      </template>
    </CommonHeader>

    <!-- 内容 -->
    <LoadMore ref="loadmore" :on-refresh="onRefresh">
      <main class="m-flex-shrink1 m-flex-grow1 m-art m-main">
        <div class="m-art-body">
          <h2 v-if="title">{{ title }}</h2>
          <video
            v-if="!!video"
            :poster="cover_file"
            class="feed-detail-video"
            controls
            autoplay
          >
            <source :src="video_file" type="video/mp4">
          </video>
          <AsyncFile
            v-for="img in images"
            v-if="img.file"
            :key="img.file"
            :file="img.file"
          >
            <img
              v-if="props.src"
              slot-scope="props"
              :src="props.src"
              @click="onFileClick(img)"
            >
          </AsyncFile>
          <p class="m-text-box m-text-pre" v-html="formatBody(feedContent)" />
        </div>

        <div class="m-box m-aln-center m-justify-bet m-art-foot">
          <div class="m-flex-grow1 m-flex-shrink1 m-art-like-list">
            <ArticleLikeBadge
              v-if="likeCount > 0"
              :likers="likes"
              :total="likeCount"
            />
          </div>
          <div class="m-box-model m-aln-end m-art-info">
            <span v-if="time">发布于{{ time | time2tips }}</span>
            <span>{{ feed.feed_view_count || 0 | formatNum }}浏览</span>
          </div>
        </div>
        <div v-if="allowReward" class="m-box-model m-box-center m-box-center-a m-art-reward">
          <button class="m-art-rew-btn" @click="rewardFeed">打 赏</button>
          <ArticleRewardBadge
            :total="reward.count"
            :amount="reward.amount"
            :rewarders="rewardList"
          />
        </div>
      </main>

      <!-- 评论列表 -->
      <div v-if="!pinnedCom.length && !comments.length" class="m-no-content" />
      <div
        v-else
        id="comment_list"
        class="m-box-model m-art-comments"
      >
        <ul class="m-box m-aln-center m-art-comments-tabs">
          <li>{{ commentCount | formatNum }}条评论</li>
        </ul>
        <CommentItem
          v-for="(comment) in pinnedCom"
          :key="`pinned-comment-${comment.id}`"
          :pinned="true"
          :comment="comment"
          @click="replyComment(comment)"
        />
        <CommentItem
          v-for="(comment) in comments"
          :key="comment.id"
          :comment="comment"
          @click="replyComment(comment)"
        />
        <div class="m-box m-aln-center m-justify-center load-more-box">
          <span v-if="noMoreCom" class="load-more-ph">---没有更多---</span>
          <span
            v-else
            class="load-more-btn"
            @click.stop="fetchFeedComments(maxComId)"
          >
            {{ fetchComing ? "加载中..." : "点击加载更多" }}
          </span>
        </div>
      </div>
    </LoadMore>
  </ArticleCard>
</template>

<script>
import { mapState } from 'vuex'
import wechatShare from '@/util/wechatShare.js'
import { limit } from '@/api'
import { followUserByStatus, getUserInfoById } from '@/api/user.js'
import * as api from '@/api/feeds.js'
import ArticleCard from '@/page/article/ArticleCard.vue'
import CommentItem from '@/page/article/ArticleComment.vue'
import ArticleLikeBadge from '@/components/common/ArticleLikeBadge.vue'
import ArticleRewardBadge from '@/components/common/ArticleRewardBadge.vue'
import { escapeHTML } from '@/filters'

export default {
  name: 'FeedDetail',
  components: {
    ArticleCard,
    CommentItem,
    ArticleLikeBadge,
    ArticleRewardBadge,
  },
  data () {
    return {
      feed: {},
      loading: true,
      fetching: false,

      comments: [],
      pinnedCom: [],
      rewardList: [],

      fetchComing: false,
      noMoreCom: false,
      maxComId: 0,
      user: {},
    }
  },
  computed: {
    ...mapState(['CURRENTUSER']),
    allowReward () {
      return this.$store.state.CONFIG.site.reward.status
    },
    feedId () {
      return this.$route.params.feedId
    },
    video () {
      return this.feed.video
    },
    video_file () {
      return this.video
        ? `${this.$http.defaults.baseURL}/files/${this.video.video_id}`
        : false
    },
    title () {
      return this.feed.title
    },
    cover_file () {
      return this.video
        ? `${this.$http.defaults.baseURL}/files/${this.video.video_id}`
        : false
    },
    isMine () {
      return this.feed.user_id === this.CURRENTUSER.id
    },
    likes: {
      get () {
        return this.feed.likes || []
      },
      set (val) {
        this.feed.likes = val
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
    reward () {
      return this.feed.reward || {}
    },
    images () {
      return this.feed.images || []
    },
    time () {
      return this.feed.created_at || ''
    },
    feedContent () {
      return this.feed.feed_content || ''
    },
    isWechat () {
      return this.$store.state.BROWSER.isWechat
    },
    has_collect: {
      get () {
        return this.feed.has_collect
      },
      set (val) {
        this.feed.has_collect = val
      },
    },
    relation: {
      get () {
        const relations = {
          unFollow: {
            text: '关注',
            status: 'unFollow',
            icon: `#icon-unFollow`,
          },
          follow: {
            text: '已关注',
            status: 'follow',
            icon: `#icon-follow`,
          },
          eachFollow: {
            text: '互相关注',
            status: 'eachFollow',
            icon: `#icon-eachFollow`,
          },
        }
        const { follower, following } = this.user
        const relation = follower && following
          ? 'eachFollow'
          : follower
            ? 'follow'
            : 'unFollow'
        return relations[relation]
      },

      set (val) {
        this.user.follower = val
      },
    },
  },
  beforeMount () {
    if (this.isIosWechat) {
      this.reload(this.$router)
    }
  },
  activated () {
    if (this.feedId) {
      this.comments = []
      this.feed = {}
      this.rewardList = []
      this.fetchFeed()
    }
  },
  deactivated () {
    this.loading = true
    this.share = {
      title: '',
      desc: '',
      link: '',
    }
    this.config = {
      appid: '',
      timestamp: 0,
      noncestr: '',
      signature: '',
    }
  },
  methods: {
    formatBody (str) {
      // XSS filter
      str = escapeHTML(str)

      const reg = /(https?|http|ftp|file):\/\/[-A-Za-z0-9+&@#/%?=~_|!:,.;]+[-A-Za-z0-9+&@#/%=~_|]/g
      return str
        ? str.replace(
          reg,
          link =>
            `<a class="m-art-links" href="${link}" target="__blank">#网页链接#</a>`
        )
        : ''
    },
    fetchFeed (callback) {
      if (this.fetching) return
      this.fetching = true
      const shareUrl =
        window.location.origin +
        process.env.BASE_URL.substr(0, process.env.BASE_URL.length - 1) +
        this.$route.fullPath
      const signUrl =
        this.$store.state.BROWSER.OS === 'IOS' ? window.initUrl : shareUrl
      this.$http
        .get(`/feeds/${this.feedId}`)
        .then(({ data = {} }) => {
          this.feed = data
          this.fetching = false
          this.fetchUserInfo()
          this.fetchFeedComments()
          this.fetchFeedRewards()
          this.fetchFeedLikers()
          this.isWechat &&
            wechatShare(signUrl, {
              title: `${data.user.name}的动态`,
              desc: `${data.feed_content}`,
              link: shareUrl,
              imgUrl:
                data.images.length > 0
                  ? `${this.$http.defaults.baseURL}/files/${
                    data.images[0].file
                  }`
                  : '',
            })
          if (callback && typeof callback === 'function') {
            callback()
          }
        })
        .catch(() => {
          this.goBack()
          if (callback && typeof callback === 'function') {
            callback()
          }
        })
    },
    fetchUserInfo () {
      getUserInfoById(this.feed.user_id, true).then(user => {
        this.user = Object.assign({}, this.user, user)
        this.loading = false
      })
    },
    fetchFeedComments (after = 0) {
      if (this.fetchComing) return
      this.fetchComing = true
      api
        .getFeedComments(this.feedId, { after })
        .then(({ data: { pinneds = [], comments = [] } }) => {
          if (!after) {
            this.pinnedCom = pinneds
            // 过滤第一页中的置顶评论
            const pinnedIds = pinneds.map(p => p.id)
            this.comments = comments.filter(c => pinnedIds.indexOf(c.id) < 0)
          } else {
            this.comments = [...this.comments, ...comments]
          }

          if (comments.length) {
            this.maxComId = comments[comments.length - 1].id
          }

          this.noMoreCom = comments.length !== limit
          this.$nextTick(() => {
            this.fetchComing = false
            this.loading = false
          })
        })
        .catch(() => {
          this.loading = false
          this.fetchComing = false
        })
    },
    async fetchFeedRewards () {
      const { data: list } = await api.getRewards(this.feedId, { limit: 10 })
      this.rewardList = list
      this.$store.commit('SAVE_ARTICLE', { type: 'likers', list })
    },
    async fetchFeedLikers () {
      const { data: list } = await api.getFeedLikers(this.feedId, { limit: 5 })
      this.feed.likes = list
      this.$store.commit('SAVE_ARTICLE', { type: 'likers', list })
    },
    rewardFeed () {
      this.popupBuyTS()
    },
    likeFeed () {
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
            if (this.likes.length < 5) {
              this.likes.push({
                user: this.CURRENTUSER,
                id: new Date().getTime(),
                user_id: this.CURRENTUSER.id,
              })
            }
          } else {
            this.liked = false
            this.likeCount -= 1
            this.likes = this.likes.filter(like => {
              return like.user_id !== this.CURRENTUSER.id
            })
          }
        })
        .finally(() => {
          this.fetching = false
        })
    },
    commentFeed () {
      this.$bus.$emit('commentInput', {
        onOk: text => {
          this.sendComment({ body: text })
        },
      })
    },
    shareFeed () {
      if (this.isWechat) this.$Message.success('请点击右上角微信分享😳')
      else this.$Message.success('请使用浏览器的分享功能😳')
    },
    moreAction () {
      const defaultActions = [
        {
          text: this.has_collect ? '取消收藏' : '收藏',
          method: () => {
            // POST /feeds/:feed/collections
            // DELETE /feeds/:feed/uncollect
            let url
            let txt
            let method
            if (this.has_collect) {
              txt = '取消收藏'
              method = 'delete'
              url = `/feeds/${this.feedId}/uncollect`
            } else {
              txt = '已加入我的收藏'
              method = 'post'
              url = `/feeds/${this.feedId}/collections`
            }

            this.$http({
              url,
              method,
              validateStatus: s => s === 204 || s === 201,
            }).then(() => {
              this.$Message.success(txt)
              this.has_collect = !this.has_collect
            })
          },
        },
      ]

      const actions = this.isMine
        ? [
          {
            text: '申请动态置顶',
            method: () => {
              this.$bus.$emit('applyTop', {
                type: 'feed',
                api: api.applyTopFeed,
                payload: this.feedId,
              })
            },
          },
          {
            text: '删除动态',
            method: () => {
              setTimeout(() => {
                const actionSheet = [
                  {
                    text: '删除',
                    style: { color: '#f4504d' },
                    method: () => {
                      api.deleteFeed(this.feedId).then(() => {
                        this.$Message.success('删除动态成功')
                        this.goBack()
                      })
                    },
                  },
                ]
                this.$bus.$emit(
                  'actionSheet',
                  actionSheet,
                  '取消',
                  '确认删除?'
                )
              }, 200)
            },
          },
        ]
        : [
          {
            text: '举报',
            method: () => {
              this.$bus.$emit('report', {
                type: 'feed',
                payload: this.feedId,
                username: this.user.name,
                reference: this.feed.feed_content,
              })
            },
          },
        ]
      this.$bus.$emit('actionSheet', [...defaultActions, ...actions], '取消')
    },
    replyComment (comment) {
      const actions = []
      // 是否是自己的评论
      if (comment.user_id === this.CURRENTUSER.id) {
        // 是否是自己文章的评论
        const isOwner = comment.user_id === this.user.id
        actions.push({
          text: isOwner ? '评论置顶' : '申请评论置顶',
          method: () => {
            this.$bus.$emit('applyTop', {
              isOwner,
              type: 'feedComment',
              api: api.applyTopFeedComment,
              payload: { feedId: this.feedId, commentId: comment.id },
              callback: this.fetchFeedComments,
            })
          },
        })
        actions.push({
          text: '删除评论',
          method: () => this.deleteComment(comment.id),
        })
      } else {
        actions.push({
          text: '回复',
          method: () => {
            this.$bus.$emit('commentInput', {
              placeholder: `回复： ${comment.user.name}`,
              onOk: text => {
                this.sendComment({ reply_user: comment.user_id, body: text })
              },
            })
          },
        })
        actions.push({
          text: '举报',
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
      const params = {}
      if (body && body.length > 0) {
        params.body = body
        replyUser && (params['reply_user'] = replyUser)
        this.$http
          .post(`/feeds/${this.feedId}/comments`, params, {
            validateStatus: s => s === 201,
          })
          .then(({ data: { comment } = { comment: {} } }) => {
            this.$Message.success('评论成功')
            this.comments.unshift(comment)
            this.commentCount += 1
            this.$bus.$emit('commentInput:close', true)
          })
          .catch(() => {
            this.$Message.error('评论失败')
            this.$bus.$emit('commentInput:close', true)
          })
      } else {
        this.$Message.error('评论内容不能为空')
      }
    },
    deleteComment (commentId) {
      api.deleteFeedComment(this.feedId, commentId).then(() => {
        this.fetchFeedComments()
        this.commentCount -= 1
        this.$Message.success('删除评论成功')
      })
    },
    followUserByStatus (status) {
      if (!status || this.fetchFollow) return
      this.fetchFollow = true

      followUserByStatus({
        id: this.user.id,
        status,
      }).then(follower => {
        this.relation = follower
        this.fetchFollow = false
      })
    },
    onRefresh () {
      this.fetchFeed(() => {
        this.$refs.loadmore.topEnd()
      })
    },
    onFileClick (paidNode) {
      if (!paidNode || paidNode.paid !== false || paidNode.type === 'download') return

      if (this.$lstore.hasData('H5_ACCESS_TOKEN')) {
        this.$bus.$emit('payfor', {
          nodeType: '内容',
          node: paidNode.paid_node,
          amount: paidNode.amount,
          onSuccess: data => {
            this.$Message.success(data)
            this.feed.images = null
            this.fetchFeed()
          },
        })
      } else {
        this.$nextTick(() => {
          const path = this.$route.fullPath
          this.$router.push({
            path: '/signin',
            query: { redirect: path },
          })
        })
      }
    },
  },
}
</script>

<style lang="less" scoped>
.feed-detail-video {
  height: 100vw;
  width: 100vw;
  // object-fit: cover;
  margin-left: -20px;
  background: #000;
}
.m-art {
  padding-top: 0.1rem;
  padding-bottom: 0.1rem;
}
.m-art-head {
  .m-avatar-box-def {
    width: 52px;
    height: 52px;
  }
}
.username {
  font-size: 0.32rem;
  margin-left: 0.1rem;
  text-align: center;
}

.load-more-box {
  height: auto;

  .load-more-ph {
    height: 100px;
    line-height: 100px;
  }
}
</style>
