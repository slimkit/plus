<template>
  <ArticleCard
    :liked="liked"
    :loading="loading"
    :can-oprate="news.audit_status===0"
    @on-like="likeNews"
    @on-share="shareNews"
    @on-more="moreAction"
    @on-comment="commentNews"
  >
    <CommonHeader slot="head">资讯详情</CommonHeader>

    <JoLoadMore
      ref="loadmore"
      :auto-load="false"
      :show-bottom="false"
      @onRefresh="onRefresh"
    >
      <div class="m-flex-shrink1 m-flex-grow1 m-art m-main">
        <section class="m-art-head">
          <h1>{{ news.title }}</h1>
          <p>
            <i class="m-art-cate">{{ cate }}</i>
            <span>来自 {{ news.from || '原创' }}</span>
          </p>
        </section>
        <p v-if="news.subject" class="m-art-subject">{{ news.subject }}</p>
        <div class="m-art-body markdown-body" v-html="body" />
        <div class="m-box m-aln-center m-justify-bet m-art-foot">
          <div class="m-flex-grow1 m-flex-shrink1 m-box m-aln-center m-art-like-list">
            <ArticleLikeBadge
              v-if="likeCount > 0 && news.audit_status===0"
              :likers="likes"
              :total="likeCount"
            />
          </div>
          <div class="m-box-model m-aln-end m-art-info">
            <span>发布于{{ time | time2tips }}</span>
            <span>{{ news.hits || 0 | formatNum }}浏览</span>
          </div>
        </div>
        <div v-if="allowReward" class="m-box-model m-box-center m-box-center-a m-art-reward">
          <button class="m-art-rew-btn" @click="rewardNews">打 赏</button>
          <ArticleRewardBadge
            :total="reward.count"
            :amount="reward.amount"
            :rewarders="rewardList"
          />
        </div>
      </div>

      <div v-if="relationNews.length" class="m-box-model m-art-comments">
        <ul class="m-box m-aln-center m-art-comments-tabs">
          <li>相关资讯</li>
        </ul>
        <NewsCard
          v-for="newsItem in relationNews"
          :key="`relation-${newsItem.id}`"
          :news="newsItem"
        />
      </div>

      <div v-if="!pinnedCom.length && !comments.length" class="m-no-content" />
      <div v-else class="m-box-model m-art-comments">
        <ul class="m-box m-aln-center m-art-comments-tabs">
          <li>{{ commentCount | formatNum }}条评论</li>
        </ul>
        <template v-if="news.audit_status === 0">
          <CommentItem
            v-for="(comment) in pinnedCom"
            :key="`pinned-${comment.id}`"
            :comment="comment"
            :pinned="true"
            @click="replyComment(comment)"
          />
          <CommentItem
            v-for="(comment) in comments"
            :key="`comment-${comment.id}`"
            :comment="comment"
            @click="replyComment(comment)"
          />
          <div class="m-box m-aln-center m-justify-center load-more-box">
            <span v-if="noMoreCom" class="load-more-ph">---没有更多---</span>
            <span
              v-else
              class="load-more-btn"
              @click.stop="fetchNewsComments(maxComId)"
            >
              {{ fetchComing ? "加载中..." : "点击加载更多" }}
            </span>
          </div>
        </template>
      </div>
    </JoLoadMore>
  </ArticleCard>
</template>

<script>
import { mapState } from 'vuex'
import wechatShare from '@/util/wechatShare.js'
import md from '@/util/markdown.js'
import { limit } from '@/api'
import * as api from '@/api/news.js'
import { noop } from '@/util'
import ArticleCard from '@/page/article/ArticleCard.vue'
import NewsCard from '@/page/news/components/NewsCard.vue'
import CommentItem from '@/page/article/ArticleComment.vue'
import ArticleLikeBadge from '@/components/common/ArticleLikeBadge.vue'
import ArticleRewardBadge from '@/components/common/ArticleRewardBadge.vue'

export default {
  name: 'NewsDetail',
  components: {
    ArticleCard,
    CommentItem,
    NewsCard,
    ArticleLikeBadge,
    ArticleRewardBadge,
  },
  data () {
    return {
      oldId: 0,
      news: {},
      loading: true,
      fetching: false,

      relationNews: [],
      likes: [],
      comments: [],
      rewardList: [],
      reward: {
        count: 0,
        amount: 0,
      },
      pinnedCom: [],

      fetchComing: false,
      noMoreCom: false,
      maxComId: 0,
      config: {
        appid: '',
        signature: '',
        timestamp: '',
        noncestr: '',
      },
      appList: [
        'onMenuShareQZone',
        'onMenuShareQQ',
        'onMenuShareAppMessage',
        'onMenuShareTimeline',
      ],
      share: {
        title: '',
        desc: '',
        link: '',
      },
    }
  },
  computed: {
    ...mapState(['CURRENTUSER']),
    allowReward () {
      return this.$store.state.CONFIG.site.reward.status
    },
    firstImage () {
      let images = this.news.image
      if (!Object.keys(images).length) {
        return ''
      }
      return (
        this.$http.defaults.baseURL + '/files/' + images.id + '?w=300&h=300'
      )
    },
    newsId () {
      return this.$route.params.newsId
    },
    userId () {
      return this.news.user_id || 0
    },
    isMine () {
      return this.news.user_id === this.CURRENTUSER.id
    },
    liked: {
      get () {
        return !!this.news.has_like
      },
      set (val) {
        this.news.has_like = val
      },
    },
    likeCount: {
      get () {
        return this.news.digg_count || 0
      },
      set (val) {
        this.news.digg_count = val
      },
    },
    commentCount: {
      get () {
        return this.news.comment_count || 0
      },
      set (val) {
        this.news.comment_count = val
      },
    },
    time () {
      return this.news.created_at || ''
    },
    cate () {
      const { category: { name = '未分类' } = {} } = this.news
      return name
    },
    body () {
      return md(this.news.content || '')
    },
    isWechat () {
      return this.$store.state.BROWSER.isWechat
    },
    has_collect: {
      get () {
        return this.news.has_collect
      },
      set (val) {
        this.news.has_collect = val
      },
    },
  },
  beforeMount () {
    if (this.isIosWechat) {
      this.$Message.info('reload')
      this.reload(this.$router)
    }
  },
  activated () {
    if (this.newsId) {
      if (this.newsId !== this.oldId) {
        this.fetchNews()
      } else {
        setTimeout(() => {
          this.loading = false
        }, 600)
      }
    }
  },
  deactivated () {
    this.loading = true
  },
  methods: {
    shareSuccess () {
      this.$Message.success('分享成功')
    },
    shareCancel () {
      this.$Message.success('取消分享')
    },
    fetchNews (callback = noop) {
      if (this.fetching) return
      this.fetching = true
      this.relationNews = []
      api
        .getNewsById(this.newsId)
        .then(({ data = {} }) => {
          this.loading = false
          this.fetching = false
          this.news = data
          this.oldId = this.newsId
          this.share.title = data.title
          this.share.desc = data.subject
          this.getCorrelations()
          this.fetchNewsComments()
          this.fetchNewsLikes()
          this.fetchNewsRewards()
          this.fetchRewardInfo()
          callback()
          if (this.isWechat) {
            const shareUrl =
              window.location.origin +
              process.env.BASE_URL.substr(0, process.env.BASE_URL.length - 1) +
              this.$route.fullPath
            const signUrl =
              this.$store.state.BROWSER.OS === 'IOS'
                ? window.initUrl
                : shareUrl
            wechatShare(signUrl, {
              title: data.title,
              desc: data.subject,
              link: shareUrl,
              imgUrl: this.firstImage,
            })
          }
        })
        .catch(() => {
          this.$router.back()
        })
    },
    getCorrelations () {
      api.getCorrelations(this.newsId).then(({ data }) => {
        this.relationNews = data
      })
    },
    fetchNewsComments (after = 0) {
      if (this.fetchComing) return
      this.fetchComing = true

      api
        .getNewsComments(this.newsId, { after })
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

          this.noMoreCom = comments.lenght !== limit
          this.fetchComing = false
        })
        .catch(() => {
          this.fetchComing = false
        })
    },
    async fetchNewsLikes () {
      const { data: list } = await api.getNewsLikers(this.newsId)
      this.likes = list
      this.$store.commit('SAVE_ARTICLE', { type: 'likers', list })
    },
    async fetchNewsRewards () {
      const { data: list } = await api.getNewsRewards(this.newsId, { limit: 10 })
      this.rewardList = list
      this.$store.commit('SAVE_ARTICLE', { type: 'rewarders', list })
    },
    fetchRewardInfo () {
      api.getRewardInfo(this.newsId).then(({ data = {} }) => {
        this.reward = {
          count: ~~data.count || 0,
          amount: ~~data.amount || 0,
        }
      })
    },
    rewardNews () {
      this.popupBuyTS()
    },
    likeNews () {
      // DELETE /news/{news}/likes
      const method = this.liked ? 'delete' : 'post'
      if (this.fetching) return
      this.fetching = true
      this.$http({
        method,
        url: `/news/${this.newsId}/likes`,
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
          this.fetchNewsLikes()
        })
        .finally(() => {
          this.fetching = false
        })
    },
    commentNews () {
      this.$bus.$emit('commentInput', {
        onOk: text => {
          this.sendComment({ body: text })
        },
      })
    },
    shareNews () {
      if (this.isWechat) this.$Message.success('请点击右上角微信分享😳')
      else this.$Message.success('请使用浏览器的分享功能😳')
    },
    moreAction () {
      const defaultActions = []
      if (this.has_collect) {
        defaultActions.push({
          text: '取消收藏',
          method: () => {
            api.uncollectNews(this.newsId).then(() => {
              this.$Message.success('取消成功')
              this.has_collect = false
            })
          },
        })
      } else {
        defaultActions.push({
          text: '收藏',
          method: () => {
            api.collectionNews(this.newsId).then(() => {
              this.$Message.success('收藏成功')
              this.has_collect = true
            })
          },
        })
      }

      const actions = this.isMine
        ? [
          {
            text: '申请文章置顶',
            method: () => {
              this.$bus.$emit('applyTop', {
                type: 'news',
                api: api.applyTopNews,
                payload: this.newsId,
              })
            },
          },
          {
            text: '删除',
            method: () => {
              this.$Message.info('资讯删除功能开发中，敬请期待')
            },
          },
        ]
        : [
          {
            text: '举报',
            method: () => {
              this.$bus.$emit('report', {
                type: 'news',
                payload: this.newsId,
                username: this.news.user.name,
                reference: this.news.title,
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
        const isOwner = comment.user_id === this.userId
        actions.push({
          text: isOwner ? '评论置顶' : '申请评论置顶',
          method: () => {
            this.$bus.$emit('applyTop', {
              isOwner,
              type: 'newsComment',
              api: api.applyTopNewsComment,
              payload: { newsId: this.newsId, commentId: comment.id },
              callback: this.fetchNewsComments,
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
          .post(`/news/${this.newsId}/comments`, params, {
            validateStatus: s => s === 201,
          })
          .then(() => {
            this.$Message.success('评论成功')
            this.fetchNewsComments()
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
      api.deleteNewsComment(this.newsId, commentId).then(() => {
        this.fetchNewsComments()
        this.commentCount -= 1
        this.$Message.success('删除评论成功')
      })
    },
    onRefresh () {
      this.fetchNews(() => {
        this.$refs.loadmore.afterRefresh(true)
      })
    },
    getAvatar (avatar) {
      avatar = avatar || {}
      return avatar.url || null
    },
  },
}
</script>

<style lang="less" scoped>
.m-art-head {
  padding-top: 36px;

  > h1 {
    margin-top: 0;
  }
}

.m-main {
  padding-bottom: 36px;
}
</style>
