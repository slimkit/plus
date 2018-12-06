<template>
  <div class="p-user-home">
    <PortalPanel
      ref="panel"
      :title="user.name"
      :cover="userBackground"
      :loading="loading"
      :show-footer="!isMine"
      :no-more="noMoreData"
      :back="beforeBack"
      @update="updateData"
      @more="onMoreClick"
      @loadmore="fetchUserFeed(true)"
    >
      <div slot="head" class="banner-content">
        <label v-if="isMine" class="banner-click-area">
          <input
            ref="imagefile"
            :accept="accept"
            type="file"
            class="m-rfile"
            @change="onBannerChange"
          >
        </label>
        <Avatar :user="user" size="big" />
        <h3>{{ user.name }}</h3>
        <p>
          <RouterLink
            append
            to="followers"
            tag="span"
          >
            粉丝 <span>{{ followersCount | formatNum }}</span>
          </RouterLink>
          <RouterLink
            append
            to="followings"
            tag="span"
          >
            关注 <span>{{ followingsCount | formatNum }}</span>
          </RouterLink>
        </p>
      </div>

      <div slot="info" class="user-info">
        <p v-if="verified" class="verified">
          认证：<span>{{ verified.description }}</span>
        </p>
        <p v-if="user.location">
          地址：<span>{{ user.location }}</span>
        </p>
        <p>
          简介：<span>{{ bio }}</span>
        </p>
        <p class="user-tags">
          <span
            v-for="tag in tags"
            v-if="tag.id"
            :key="`tag-${tag.id}`"
            class="tag-item"
            v-text="tag.name"
          />
        </p>
      </div>

      <div
        slot="sticky"
        v-clickoutside="hidenFilter"
        class="filter-bar"
        @click="popupBuyTS"
      >
        <span>{{ feedsCount }}条动态</span>
        <div v-if="isMine">
          <span>{{ feedTypes[screen] }}</span>
          <svg class="m-style-svg m-svg-small">
            <use xlink:href="#icon-list" />
          </svg>
        </div>
      </div>

      <template slot="main">
        <ul class="user-feeds">
          <li
            v-for="feed in feeds"
            v-if="feed.id"
            :key="`ush-${userId}-feed${feed.id}`"
          >
            <FeedCard
              :feed="feed"
              :time-line="true"
              @afterDelete="fetchUserInfo()"
            />
          </li>
        </ul>
      </template>

      <template slot="foot">
        <div class="m-flex-grow0 m-flex-shrink0 m-box m-aln-center m-justify-center" @click="rewardUser">
          <svg class="m-style-svg m-svg-def">
            <use xlink:href="#icon-profile-integral" />
          </svg>
          <span>打赏</span>
        </div>
        <div
          :class="{ primary: relation.status !== 'unFollow' }"
          class="m-flex-grow0 m-flex-shrink0 m-box m-aln-center m-justify-center"
          @click="followUserByStatus(relation.status)"
        >
          <svg class="m-style-svg m-svg-def">
            <use :xlink:href="relation.icon" />
          </svg>
          <span>{{ relation.text }}</span>
        </div>
        <!-- `/chats/${user.id}` -->
        <div class="m-flex-grow0 m-flex-shrink0 m-box m-aln-center m-justify-center" @click="startSingleChat">
          <svg class="m-style-svg m-svg-def">
            <use xlink:href="#icon-comment" />
          </svg>
          <span>聊天</span>
        </div>
      </template>
    </PortalPanel>
  </div>
</template>

<script>
import * as uploadApi from '@/api/upload'
import * as feedApi from '@/api/feeds'
import * as userApi from '@/api/user.js'
import { hashFile } from '@/util/SendImage.js'
import { startSingleChat } from '@/vendor/easemob'
import { checkImageType } from '@/util/imageCheck.js'
import wechatShare from '@/util/wechatShare.js'
import PortalPanel from '@/components/PortalPanel'
import FeedCard from '@/components/FeedCard/FeedCard.vue'

export default {
  name: 'UserHome',
  directives: {
    clickoutside: {
      bind (el, binding) {
        function documentHandler (e) {
          if (el.contains(e.target)) {
            return false
          }
          if (binding.expression) {
            binding.value(e)
          }
        }
        el.__vueClickOutside__ = documentHandler
        document.addEventListener('click', documentHandler)
      },
      unbind (el) {
        document.removeEventListener('click', el.__vueClickOutside__)
        delete el.__vueClickOutside__
      },
    },
  },
  components: {
    FeedCard,
    PortalPanel,
  },
  data () {
    return {
      preUID: 0,
      loading: false,

      accept: {
        type: [Array, String],
        default () {
          return [
            'image/gif',
            'image/jpeg',
            'image/webp',
            'image/jpg',
            'image/png',
            'image/bmp',
          ]
        },
      },

      typeFilter: null,
      showFilter: false,
      screen: 'all',

      feeds: [],
      feedTypes: {
        all: '全部动态',
        paid: '付费动态',
        pinned: '置顶动态',
      },
      noMoreData: false,
      fetchFeeding: false,

      tags: [],
      appList: [
        'onMenuShareQZone',
        'onMenuShareQQ',
        'onMenuShareAppMessage',
        'onMenuShareTimeline',
      ],
      config: {
        appid: '',
        signature: '',
        timestamp: '',
        noncestr: '',
      },
      fetchFollow: false,
    }
  },
  computed: {
    isWechat () {
      return this.$store.state.BROWSER.isWechat
    },
    currentUser () {
      return this.$store.state.CURRENTUSER
    },
    userId () {
      return ~~this.$route.params.userId
    },
    user: {
      get () {
        return this.$store.getters.getUserById(this.userId, true) || {}
      },
      set (val) {
        this.$store.commit('SAVE_USER', Object.assign(this.user, val))
      },
    },
    bio () {
      return this.user.bio || '这家伙很懒,什么也没留下'
    },
    extra () {
      return this.user.extra || {}
    },
    isMine () {
      return this.userId === this.currentUser.id
    },
    followersCount () {
      return this.extra.followers_count || 0
    },
    followingsCount () {
      return this.extra.followings_count || 0
    },
    feedsCount () {
      return this.extra.feeds_count || 0
    },
    userBackground () {
      return this.user.bg && this.user.bg.url
    },
    verified () {
      return this.user.verified
    },
    after () {
      const len = this.feeds.length
      return len > 0 ? this.feeds[len - 1].id : ''
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
        const relation = follower && following ? 'eachFollow' : follower ? 'follow' : 'unFollow'
        return relations[relation]
      },

      set (val) {
        this.user.follower = val
      },
    },
  },
  watch: {
    screen (val) {
      val && this.updateData()
    },
  },
  beforeMount () {
    if (this.isIosWechat) {
      this.reload(this.$router)
    }
  },
  activated () {
    if (this.preUID !== this.userId) {
      this.loading = true
      this.feeds = []
      this.tags = []
      this.updateData()
    } else {
      setTimeout(() => {
        this.loading = false
      }, 300)
    }

    if (this.isWechat) {
      // 微信分享
      const shareUrl =
        window.location.origin +
        process.env.BASE_URL.substr(0, process.env.BASE_URL.length - 1) +
        this.$route.fullPath
      const signUrl =
        this.$store.state.BROWSER.OS === 'IOS' ? window.initUrl : shareUrl
      const avatar = this.user.avatar || {}
      wechatShare(signUrl, {
        title: this.user.name,
        desc: this.user.bio,
        link: shareUrl,
        imgUrl: avatar.url || '',
      })
    }

    this.preUID = this.userId
  },
  deactivated () {
    this.loading = true
    this.showFilter = false
  },
  methods: {
    beforeBack () {
      if (this.$route.query.from === 'checkin') this.$bus.$emit('check-in')
      this.goBack()
    },
    startSingleChat () {
      startSingleChat(this.user).then(res => {
        this.$nextTick(() => {
          this.$router.push(`/chats/${res}`)
        })
      })
    },
    rewardUser () {
      this.popupBuyTS()
    },
    followUserByStatus (status) {
      if (!status || this.fetchFollow) return
      this.fetchFollow = true

      userApi
        .followUserByStatus({
          id: this.user.id,
          status,
        })
        .then(follower => {
          this.relation = follower
          this.fetchFollow = false
        })
    },
    hidenFilter () {
      this.showFilter = false
    },
    fetchUserInfo () {
      userApi.getUserInfoById(this.userId, true)
        .then(user => {
          this.user = Object.assign(this.user, user)
        })
        .finally(() => {
          this.loading = false
        })
    },
    fetchUserTags () {
      userApi.getUserTags(this.userId)
        .then(({ data }) => {
          this.tags = data
        })
    },
    fetchUserFeed (loadmore) {
      if (this.fetchFeeding) return
      this.fetchFeeding = true

      const params = {
        limit: 15,
        type: 'users',
        user: this.userId,
      }
      if (loadmore) params.after = this.after
      if (this.isMine && this.screen !== 'all') params.screen = this.screen
      feedApi.getFeeds(params)
        .then(({ data: { feeds = [] } }) => {
          this.feeds = loadmore ? [...this.feeds, ...feeds] : feeds
          this.noMoreData = feeds.length < params.limit
        })
        .finally(() => {
          this.$refs.panel.afterUpdate()
          this.fetchFeeding = false
        })
    },
    updateData () {
      this.$refs.panel.onUpdate()
      this.fetchUserInfo()
      this.fetchUserFeed()
      this.fetchUserTags()
    },
    onBannerChange () {
      const $input = this.$refs.imagefile
      const file = $input.files[0]

      checkImageType([file])
        .then(() => {
          this.uploadFile(file)
            .then(async node => {
              await this.$http.patch('/user', { bg: node })
              this.$Message.success('更新个人背景成功！')
              this.fetchUserInfo()
            })
            .catch(({ response: { data } = {} }) => {
              this.$Message.error(data.message)
            })
        })
        .catch(() => {
          this.$Message.info('请上传正确格式的图片文件')
          $input.value = ''
        })
    },
    async uploadFile (file) {
      // 如果需要新文件存储方式上传
      const hash = await hashFile(file)
      const params = {
        filename: file.name,
        hash,
        size: file.size,
        mime_type: file.type || 'image/png',
        storage: { channel: 'public' },
      }
      const result = await uploadApi.createUploadTask(params)
      return uploadApi
        .uploadImage({
          method: result.method,
          url: result.uri,
          headers: result.headers,
          blob: file,
        })
        .then(data => {
          return Promise.resolve(data.node)
        })
        .catch(err => {
          this.$Message.error('文件上传失败，请检查文件系统配置')
          return Promise.reject(err)
        })
    },
    onMoreClick () {
      const actions = []
      actions.push({
        text: '举报',
        method: () => {
          this.$bus.$emit('report', {
            type: 'user',
            payload: this.userId,
            username: this.user.name,
            reference: this.user.bio,
          })
        },
      })
      this.$bus.$emit('actionSheet', actions)
    },
  },
}
</script>

<style lang="less" scoped>
.p-user-home {
  .banner-content {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
    max-width: 768px;
    margin: 0 auto;
    z-index: 10;

    &::after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      z-index: -1;
      margin: auto;
      opacity: 0.7;
      background-image: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0.95),
        rgba(0, 0, 0, 0) 40%,
        rgba(0, 0, 0, 0) 50%,
        rgba(0, 0, 0, 0.95)
      );
    }

    .banner-click-area {
      display: block;
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 1;
    }
  }

  .user-info {
    padding: 30px 20px;
    line-height: 36px;
    background-color: #fff;
    font-size: 26px;
    color: @text-color3;
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1); /*no*/
    word-wrap: break-word;
    word-break: break-all;

    p + p {
      margin-top: 10px;
    }

    .verified {
      color: @warning;
    }

    .user-tags {
      margin-top: 10px;

      .tag-item {
        margin-top: 10px;
        margin-right: 10px;
        display: inline-block;
        padding: 5px 20px;
        font-size: 24px;
        background-color: rgba(102, 102, 102, 0.1);
        border-radius: 100px;
        color: #666;
      }
    }
  }

  .user-feeds {
    li + li {
      margin-top: 10px;
    }
  }

  .filter-bar {
    display: flex;
    justify-content: space-between;
    padding: 20px 30px;

    .m-style-svg {
      margin-left: 20px;
    }
  }
}

.m-user-home-foot {
  > div {
    width: 1/3 * 100%;
    + div {
      border-left: 1px solid @border-color; /*no*/
    }
  }
  .m-svg-def {
    width: 32px;
    height: 32px;
    margin: 0 10px;
  }
}

.m-head-top {
  border-bottom: 0;
  padding: 0 20px;

  &.bg-transp {
    color: #fff;
    transition: background 0.3s ease;
    background-color: transparent;
  }
  &.show-title {
    background-image: none;
    background-color: #fff;
    border-bottom: 1px solid @border-color; /*no*/
    color: #000;
    .m-trans-y {
      transform: none;
    }
  }
  .m-trans-y {
    transform: translateY(100%);
    transition: transform 0.3s ease;
  }
}

</style>
