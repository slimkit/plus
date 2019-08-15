<template>
  <ArticleCard
    ref="article"
    type="feed"
    :article="feedId"
    :liked="liked"
    :loading="loading"
    @like="likeFeed"
    @comment="$refs.comments.open()"
    @more="moreAction"
    @reward="afterReward"
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
    <JoLoadMore
      slot="main"
      ref="loadmore"
      :auto-load="false"
      :show-bottom="false"
      @onRefresh="onRefresh"
    >
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
          <ul v-if="topics.length" class="topics">
            <li
              v-for="topic in topics"
              :key="topic.id"
              class="topic-item"
              @click.capture.stop="viewTopic(topic.id)"
              v-text="topic.name"
            />
          </ul>
        </div>

        <!-- 点赞组件 -->
        <ArticleLike
          :likers="likes"
          :like-count="likeCount"
          :time="time"
          :view-count="feed.feed_view_count || 0"
        />

        <!-- 打赏组件 -->
        <ArticleReward
          v-if="allowReward"
          type="feed"
          :article="feedId"
          :is-mine="isMine"
          v-bind="reward"
          @success="amount => fetchFeedRewards(amount)"
        />
      </main>

      <!-- 评论列表 -->
      <ArticleComments
        ref="comments"
        type="feed"
        :article="feedId"
        :total.sync="commentCount"
        :fetching="fetchComing"
        @reply="replyComment"
      />
    </JoLoadMore>
  </ArticleCard>
</template>

<script>
import { mapState } from 'vuex'
import { escapeHTML } from '@/filters'
import wechatShare from '@/util/wechatShare.js'
import * as userApi from '@/api/user.js'
import * as api from '@/api/feeds.js'
import ArticleCard from '@/page/article/ArticleCard'
import ArticleLike from '@/page/article/components/ArticleLike'
import ArticleReward from '@/page/article/components/ArticleReward'
import ArticleComments from '@/page/article/components/ArticleComments'

export default {
  name: 'FeedDetail',
  components: {
    ArticleCard,
    ArticleLike,
    ArticleReward,
    ArticleComments,
  },
  data () {
    return {
      feed: {},
      oldId: NaN,
      loading: true,
      fetching: false,

      rewardCount: 0,
      rewardAmount: 0,
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
    reward () {
      return {
        count: this.rewardCount,
        amount: this.rewardAmount,
        list: this.rewardList,
      }
    },
    feedId () {
      return Number(this.$route.params.feedId)
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
    topics () {
      return this.feed.topics || []
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
        this.feed.feed_comment_count += val
      },
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
            text: this.$t('follow.name'),
            status: 'unFollow',
            icon: `#icon-unFollow`,
          },
          follow: {
            text: this.$t('follow.already'),
            status: 'follow',
            icon: `#icon-follow`,
          },
          eachFollow: {
            text: this.$t('follow.each'),
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
      this.feed = {}
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
            `<a class="m-art-links" href="${link}" target="__blank">#${this.$t('article.link_text')}#</a>`
        )
        : ''
    },
    fetchFeed () {
      if (this.fetching) return
      this.fetching = true
      this.loading = false
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
          this.$refs.loadmore.afterRefresh()
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
        })
    },
    fetchUserInfo () {
      userApi.getUserInfoById(this.feed.user_id, true)
        .then(user => {
          this.user = Object.assign({}, this.user, user)
        })
    },
    fetchFeedComments () {
      this.$refs.comments.fetch()
    },
    fetchFeedRewards (inc) {
      if (inc) {
        this.rewardCount += 1
        this.rewardAmount += inc
      } else {
        const { count = 0, amount = 0 } = this.feed.reward
        this.rewardCount = Number(count)
        this.rewardAmount = Number(amount)
      }
      api.getFeedRewards(this.feedId, { limit: 10 })
        .then(({ data: list }) => {
          this.rewardList = list
          this.$store.commit('SAVE_ARTICLE', { type: 'rewarders', list })
        })
    },
    async fetchFeedLikers () {
      const { data: list } = await api.getFeedLikers(this.feedId, { limit: 5 })
      this.feed.likes = list
      this.$store.commit('SAVE_ARTICLE', { type: 'likers', list })
    },
    viewTopic (topicId) {
      this.$router.push({ name: 'TopicDetail', params: { topicId } })
    },
    afterReward (amount) {
      this.fetchRewards()
      this.rewardCount += 1
      this.rewardAmount += amount
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
    moreAction () {
      const defaultActions = [
        {
          text: this.$t(this.has_collect ? 'collect.cancel' : 'collect.name'),
          method: () => {
            // POST /feeds/:feed/collections
            // DELETE /feeds/:feed/uncollect
            let url
            let txt
            let method
            if (this.has_collect) {
              txt = this.$t('collect.name')
              method = 'delete'
              url = `/feeds/${this.feedId}/uncollect`
            } else {
              txt = this.$t('collect.already')
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
            text: this.$t('feed.apply_top'),
            method: () => {
              this.$bus.$emit('applyTop', {
                type: 'feed',
                api: api.applyTopFeed,
                payload: this.feedId,
              })
            },
          },
          {
            text: this.$t('feed.delete'),
            method: () => {
              setTimeout(() => {
                const actionSheet = [
                  {
                    text: this.$t('delete.name'),
                    style: { color: '#f4504d' },
                    method: () => {
                      api.deleteFeed(this.feedId).then(() => {
                        this.$Message.success(this.$t('feed.delete_success'))
                        this.goBack()
                      })
                    },
                  },
                ]
                this.$bus.$emit('actionSheet', actionSheet, this.$t('cancel'), this.$t('delete.confirm'))
              }, 200)
            },
          },
        ]
        : [
          {
            text: this.$t('report.name'),
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
      this.$bus.$emit('actionSheet', [...defaultActions, ...actions])
    },
    replyComment (comment) {
      const actions = []
      // 是否是自己的评论
      if (comment.user_id === this.CURRENTUSER.id) {
        // 是否是自己文章的评论
        const isOwner = comment.user_id === this.user.id
        actions.push({
          text: this.$t(isOwner ? 'comment.top.name' : 'comment.top.apply'),
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
          text: this.$t('comment.delete.name'),
          method: () => this.$refs.comments.delete(comment.id),
        })
      } else {
        actions.push({
          text: this.$t('reply.name'),
          method: () => this.$refs.comments.open(comment.user),
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
    followUserByStatus (status) {
      if (!status || this.fetchFollow) return
      this.fetchFollow = true

      userApi.followUserByStatus({
        id: this.user.id,
        status,
      }).then(follower => {
        this.relation = follower
        this.fetchFollow = false
        this.user.extra.followers_count = follower ? this.user.extra.followers_count + 1 : this.user.extra.followers_count - 1
        this.$store.commit('SAVE_USER', this.user)
      })
    },
    onRefresh () {
      this.$refs.loadmore.beforeRefresh()
      this.fetchFeed()
    },
    onFileClick (paidNode) {
      if (!paidNode || paidNode.paid !== false || paidNode.type === 'download') return

      if (this.$lstore.hasData('H5_ACCESS_TOKEN')) {
        this.$bus.$emit('payfor', {
          nodeType: this.$t('article.content'),
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
  margin-left: -20px;
  background: #000;
}
.m-art {
  padding-top: 0.1rem;
  padding-bottom: 0.1rem;
}
.username {
  font-size: 0.32rem;
  margin-left: 0.1rem;
  text-align: center;
}

.topics {
  display: flex;
  flex-wrap: wrap;
  padding: 20px 0 0;

  .topic-item {
    padding: 0 16px;
    border-radius: 6px;
    background-color: rgba(145, 209, 232, 0.12);
    font-size: 24px;
    color: @primary;
    margin-right: 12px;
    margin-bottom: 12px;
    cursor: pointer;
  }
}
</style>
