<template>
  <div class="p-profile">
    <CommonHeader>
      我
      <template slot="left"><span /></template>
    </CommonHeader>

    <main class="m-box-model">
      <div class="m-box-model m-main">
        <RouterLink
          tag="section"
          class="m-box m-aln-center profile-info"
          to="/info"
        >
          <Avatar :user="user" size="big" />
          <div class="m-text-box m-flex-grow1 m-flex-shrink1 m-flex-base0 m-pr-user-info">
            <h4 class="m-pr-username">{{ user.name }}</h4>
            <p class="m-pr-bio m-text-cut-2">{{ user.bio || "这家伙很懒,什么也没有留下" }}</p>
          </div>
          <svg class="m-style-svg m-svg-def m-entry-append">
            <use xlink:href="#icon-arrow-right" />
          </svg>
        </RouterLink>

        <div class="m-box m-aln-center m-justify-aro m-bt1 followers">
          <RouterLink
            :to="`/users/${user.id}/followers`"
            tag="div"
            class="follower-item"
          >
            <BadgeIcon :count="newFans">
              <a>{{ ~~(extra.followers_count) | formatNum }}</a>
            </BadgeIcon>
            <p>粉丝</p>
          </RouterLink>
          <RouterLink
            :to="`/users/${user.id}/followings`"
            tag="div"
            class="follower-item"
          >
            <BadgeIcon count="0">
              <a>{{ ~~(extra.followings_count) | formatNum }}</a>
            </BadgeIcon>
            <p>关注</p>
          </RouterLink>
        </div>
      </div>

      <div class="m-box-model m-pr-entrys">
        <ul class="m-box-model m-entry-group">
          <ProfileItem
            label="个人主页"
            icon="#icon-profile-home"
            :to="`/users/${user.id}`"
          />

          <ProfileItem
            label="我的投稿"
            icon="#icon-profile-plane"
            to="/profile/news/released"
          />
        </ul>

        <ul class="m-box-model m-entry-group">
          <ProfileItem
            label="钱包"
            icon="#icon-profile-wallet"
            @click="popupBuyTS"
          >
            {{ new_balance }}
          </ProfileItem>

          <ProfileItem
            :label="currencyUnit"
            icon="#icon-profile-integral"
            @click="popupBuyTS"
          >
            {{ sum }}
          </ProfileItem>

          <ProfileItem
            label="收藏"
            icon="#icon-profile-collect"
            to="/profile/collection/feeds"
          />
        </ul>

        <ul class="m-box-model m-entry-group">
          <ProfileItem
            label="认证"
            icon="#icon-profile-approve"
            @click="selectCertType"
          >
            {{ verifiedText }}
          </ProfileItem>

          <ProfileItem
            label="设置"
            icon="#icon-profile-setting"
            to="/setting"
          />
        </ul>
      </div>
    </main>
    <FootGuide />
  </div>
</template>

<script>
import _ from 'lodash'
import { mapState, mapActions } from 'vuex'
import ProfileItem from './components/ProfileItem'

export default {
  name: 'Profile',
  components: { ProfileItem },
  data () {
    return {
      verifiedText: '',
    }
  },
  computed: {
    ...mapState({
      newFans: state => state.message.user.following || 0,
      newMutual: state => state.message.user.mutual || 0,
      user: state => state.CURRENTUSER,
      verified: state => state.USER_VERIFY,
    }),
    extra () {
      return this.user.extra || {}
    },
    new_wallet () {
      return this.user.new_wallet || { balance: 0 }
    },
    new_balance () {
      return (this.new_wallet.balance / 100).toFixed(2)
    },
    currency () {
      return this.user.currency || { sum: 0 }
    },
    sum () {
      return this.currency.sum
    },
  },
  watch: {
    verified (to) {
      if (to && to.status) to.status = Number(to.status)
      if (to && to.status === 0) {
        this.verifiedText = '待审核'
      } else if (to && to.status === 1) {
        this.verifiedText = '通过审核'
      } else if (to && to.status === 2) {
        this.verifiedText = '被驳回'
      } else {
        this.verifiedText = '未认证'
      }
    },
  },
  mounted () {
    this.$store.dispatch('fetchUserInfo')
    this.$store.dispatch('FETCH_USER_VERIFY')
    this.getUnreadCount()
  },
  methods: {
    ...mapActions('message', {
      getUnreadCount: 'getUnreadCount',
    }),
    selectCertType () {
      if (_.isEmpty(this.verified)) {
        const actions = [
          { text: '个人认证', method: () => this.certificate('user') },
          { text: '企业认证', method: () => this.certificate('org') },
        ]
        this.$bus.$emit('actionSheet', actions, '取消')
      } else if (this.verified.status === 2) {
        // 被驳回则补充填写表单
        const type = this.verified.certification_name || 'user'
        this.certificate(type)
      } else {
        this.$router.push({ path: '/profile/certification' })
      }
    },
    /**
     * 认证
     * @param {string} type 认证类型 (user|org)
     */
    certificate (type) {
      this.$router.push({ path: '/profile/certificate', query: { type } })
    },
  },
}
</script>

<style lang="less" scoped>
.m-pr-user-info {
  margin-left: 30px;
  margin-right: 30px;
  line-height: 40px;
  .m-pr-username {
    font-size: 32px;
    color: @text-color1;
  }
  .m-pr-bio {
    overflow: hidden;
    max-height: 40 * 2px;
    font-size: 28px;
    color: @text-color3;
    text-overflow: ellipsis;
  }
}
.m-pr-entrys {
  margin-top: 30px;
  margin-bottom: 30px;

  .m-entry {
    padding: 0 20px;
  }

  .m-entry-extra {
    margin: 0;

    + .m-entry-append {
      margin-left: 10px;
    }
  }
}

.p-profile {
  .profile-info {
    padding: 30px;
  }
  .followers {
    padding: 40px 20px;

    .follower-item {
      flex: auto;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      font-size: 28px;
      border-left: 1px solid @border-color; /*no*/

      &:first-child {
        border-left: none;
      }

      a {
        font-size: 32px;
      }

      p {
        margin-top: 15px;
      }
    }
  }
  .m-entry-prepend {
    color: @primary;
    width: 36px;
    height: 36px;
  }
  .m-entry-append {
    color: #bfbfbf;
    width: 24px;
    height: 24px;
  }
}
</style>
