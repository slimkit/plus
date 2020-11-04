<template>
    <ArticleCard
    ref="article"
    type="news"
    :article="newsId"
    :liked="liked"
    :loading="loading"
    :can-oprate="news.audit_status===0"
    @like="likeNews"
    @more="moreAction"
    @comment="$refs.comments.open()"
    >
        <JoLoadMore
        slot="main"
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
                        <span class="from">{{ $t('news.from') }} {{ news.from || $t('news.original') }}</span>
                    </p>
                </section>
                <p v-if="news.subject" class="m-art-subject">
                    <span>[{{ $t('news.post.label.subject') }}]</span>
                    {{ news.subject }}
                </p>
                <div class="m-art-body markdown-body" v-html="body"/>

                <!-- 点赞组件 -->
                <ArticleLike
                        :likers="likes"
                        :like-count="likeCount"
                        :time="time"
                        :view-count="news.hits"
                />

                <!-- 打赏组件 -->
                <ArticleReward
                        v-if="allowReward && !isMine"
                        v-bind="reward"
                        :article="newsId"
                        :is-mine="isMine"
                        type="news"
                        @success="fetchNewsRewards"
                />
            </div>

            <div v-if="relationNews.length && isPublic" class="m-box-model m-art-comments">
                <ul class="m-box m-aln-center m-art-comments-tabs">
                    <li>{{ $t('news.relation') }}</li>
                </ul>
                <NewsCard
                        v-for="newsItem in relationNews"
                        :key="`relation-${newsItem.id}`"
                        :news="newsItem"
                />
            </div>

            <!-- 评论列表 -->
            <ArticleComments
                    ref="comments"
                    type="news"
                    :article="newsId"
                    :total.sync="commentCount"
                    :fetching="fetchComing"
                    @reply="replyComment"
            />
        </JoLoadMore>
    </ArticleCard>
</template>

<script>
import { mapState } from 'vuex'
import wechatShare from '@/util/wechatShare.js'
import md from '@/util/markdown.js'
import * as api from '@/api/news.js'
import ArticleCard from '@/page/article/ArticleCard'
import NewsCard from '@/page/news/components/NewsCard'
import ArticleLike from '@/page/article/components/ArticleLike'
import ArticleReward from '@/page/article/components/ArticleReward'
import ArticleComments from '@/page/article/components/ArticleComments'

export default {
  name: 'NewsDetail',
  components: {
    ArticleCard,
    NewsCard,
    ArticleReward,
    ArticleComments,
    ArticleLike,
  },
  data () {
    return {
      oldId: 0,
      news: {},
      loading: true,
      fetching: false,

      relationNews: [],
      likes: [],
      rewardCount: 0,
      rewardAmount: 0,
      rewardList: [],

      fetchComing: false,
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
    ...mapState({
      currentUser: 'CURRENTUSER',
    }),
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
      return Number(this.$route.params.newsId)
    },
    userId () {
      return this.news.user_id || 0
    },
    isMine () {
      return this.news.user_id === this.currentUser.id
    },
    isPublic () {
      return this.news.audit_status === 0
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
        this.news.comment_count += val
      },
    },
    time () {
      return this.news.created_at || ''
    },
    cate () {
      const { category: { name = this.$t('article.uncategorized') } = {} } = this.news
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
  watch: {
    newsId (newId, oldId) {
      if (newId && newId !== oldId) {
        this.loading = true
        document.scrollingElement.scrollTop = 0
        this.fetchNews()
      }
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
    fetchNews () {
      if (this.fetching) return
      this.fetching = true
      this.relationNews = []
      api
        .getNewsById(this.newsId)
        .then(({ data = {} }) => {
          this.news = data
          if (!this.isMine && !this.isPublic) {
            this.$Message.error(this.$t('news.not_found'))
            return this.goBack()
          }
          this.loading = false
          this.fetching = false
          this.$refs.loadmore.afterRefresh()
          this.oldId = this.newsId
          this.share.title = data.title
          this.share.desc = data.subject
          this.getCorrelations()
          this.fetchNewsComments()
          this.fetchNewsLikes()
          this.fetchNewsRewards()
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
    },
    fetchNewsComments () {
      this.$refs.comments.fetch()
    },
    getCorrelations () {
      api.getCorrelations(this.newsId).then(({ data }) => {
        this.relationNews = data
      })
    },
    async fetchNewsLikes () {
      const { data: list } = await api.getNewsLikers(this.newsId)
      this.likes = list
      this.$store.commit('SAVE_ARTICLE', { type: 'likers', list })
    },
    fetchNewsRewards () {
      // 获取总金额
      api.getRewardInfo(this.newsId)
        .then(({ data: { count = 0, amount = 0 } }) => {
          this.rewardCount = Number(count)
          this.rewardAmount = Number(amount)
        })
      // 获取打赏者
      api.getNewsRewards(this.newsId, { limit: 10 })
        .then(({ data: list = [] }) => {
          this.rewardList = list
          // 保存部分信息用于预加载打赏列表
          this.$store.commit('SAVE_ARTICLE', { type: 'rewarders', list })
        })
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
    moreAction () {
      const defaultActions = []
      if (this.has_collect) {
        defaultActions.push({
          text: this.$t('collect.cancel'),
          method: () => {
            api.uncollectNews(this.newsId).then(() => {
              this.$Message.success(this.$t('collect.cancel'))
              this.has_collect = false
            })
          },
        })
      } else {
        defaultActions.push({
          text: this.$t('collect.name'),
          method: () => {
            api.collectionNews(this.newsId).then(() => {
              this.$Message.success(this.$t('collect.success'))
              this.has_collect = true
            })
          },
        })
      }

      const actions = this.isMine
        ? [
          {
            text: this.$t('top.apply'),
            method: () => {
              this.$bus.$emit('applyTop', {
                type: 'news',
                api: api.applyTopNews,
                payload: this.newsId,
              })
            },
          },
        ]
        : [
          {
            text: this.$t('report.name'),
            method: () => {
              this.$bus.$emit('report', {
                type: 'news',
                payload: this.newsId,
                username: this.news.author,
                reference: this.news.title,
              })
            },
          },
        ]
      this.$bus.$emit('actionSheet', [...defaultActions, ...actions])
    },
    replyComment (comment) {
      const actions = []
      // 是否是自己的评论
      if (comment.user_id === this.currentUser.id) {
        // 是否是自己文章的评论
        const isOwner = comment.user_id === this.userId
        actions.push({
          text: isOwner ? this.$t('comment.top.name') : this.$t('comment.top.apply'),
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
    onRefresh () {
      this.$refs.loadmore.beforeRefresh()
      this.fetchNews()
    },
    getAvatar (avatar) {
      avatar = avatar || {}
      return avatar.url || null
    },
  },
}
</script>

<style lang="less">
    .markdown-body strong {
        font-weight: bolder;
    }

    .markdown-body p {
        margin-top: 0;
        margin-bottom: 16px;
    }

    .markdown-body .hljs-center {
        text-align: center;
    }

    .markdown-body .hljs-right {
        text-align: right;
    }

    .markdown-body .hljs-left {
        text-align: left;
    }

    .m-art-head {
        padding: 36px 20px 0;

        h1 {
            margin-bottom: 36px;
            color: @primary;
            font-size: 50px;
            letter-spacing: 1px; /*no*/
        }

        p {
            font-size: 24px;
            color: @text-color4;
        }
    }

    .m-art-cate {
        @scale: 0.95;

        padding: 4px;
        font-style: normal;
        display: inline-block;
        font-size: 20px;
        height: 30px/@scale;
        margin-right: 10px;
        color: @primary;
        line-height: (30px / @scale - 8);
        border: 1px solid currentColor; /*no*/
        -webkit-transform-origin-x: 0;
        -webkit-transform: scale(@scale);
        transform: scale(@scale);
    }

    .m-art-subject {
        margin: 50px 20px 20px;
        padding: 30px;
        font-size: 26px;
        line-height: 36px;
        background-color: #f4f5f6;
        color: #999;
        border-left: 5px solid #e3e3e3;

        span {
            color: #666;
        }
    }
</style>
