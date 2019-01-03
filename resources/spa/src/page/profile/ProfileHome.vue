<template>
  <div class="p-profile">
    <CommonHeader>
      {{ $t('profile.name') }}
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
            <p class="m-pr-bio m-text-cut-2">{{ user.bio || $t('profile.default_bio') }}</p>
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
            <BadgeIcon :count="new_followers">
              <a>{{ ~~(extra.followers_count) | formatNum }}</a>
            </BadgeIcon>
            <p>{{ $t('fans') }}</p>
          </RouterLink>
          <RouterLink
            :to="`/users/${user.id}/followings`"
            tag="div"
            class="follower-item"
          >
            <BadgeIcon count="0">
              <a>{{ ~~(extra.followings_count) | formatNum }}</a>
            </BadgeIcon>
            <p>{{ $t('follow.name') }}</p>
          </RouterLink>
        </div>
      </div>

      <div class="m-box-model m-pr-entrys">
        <ul class="m-box-model m-entry-group">
          <ProfileItem
            :label="$t('profile.home.name')"
            icon="#icon-profile-home"
            :to="`/users/${user.id}`"
          />

          <ProfileItem
            :label="$t('profile.news.name')"
            icon="#icon-profile-plane"
            to="/profile/news/released"
          />
        </ul>

        <ul class="m-box-model m-entry-group">
          <ProfileItem
            :label="$t('wallet.name')"
            icon="#icon-profile-wallet"
            @click="popupBuyTS"
          >
            {{ new_balance }}
          </ProfileItem>

          <ProfileItem
            :label="$t('currency.name')"
            icon="#icon-profile-integral"
            @click="popupBuyTS"
          >
            {{ sum }}
          </ProfileItem>

          <ProfileItem
            :label="$t('profile.collect.name')"
            icon="#icon-profile-collect"
            to="/profile/collection/feeds"
          />
        </ul>

        <ul class="m-box-model m-entry-group">
          <ProfileItem
            :label="$t('certificate.name')"
            icon="#icon-profile-approve"
            @click="selectCertType"
          >
            {{ verifiedText }}
          </ProfileItem>

          <ProfileItem
            :label="$t('setting.name')"
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
import { mapState } from 'vuex'
import { resetUserCount } from '@/api/message.js'
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
      new_followers: state => state.MESSAGE.NEW_UNREAD_COUNT.following || 0,
      new_mutual: state => state.MESSAGE.NEW_UNREAD_COUNT.mutual || 0,
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
      const text = this.$t('certificate.status') // ['待审核', '通过审核', '被驳回', '未认证']
      if (to && [0, 1, 2].includes(to.status)) return (this.verifiedText = text[to.status])
      this.verifiedText = text[3]
    },
  },
  mounted () {
    this.$store.dispatch('fetchUserInfo')
    this.$store.dispatch('FETCH_USER_VERIFY')
  },
  beforeRouteLeave (to, from, next) {
    const { params: { type } } = to
    const resetType =
      type === 'followers' ? 'following' : type === 'mutual' ? 'mutual' : ''
    resetType && resetUserCount(resetType)
    next()
  },
  methods: {
    selectCertType () {
      if (_.isEmpty(this.verified)) {
        const actions = [
          { text: this.$t('certificate.user.name'), method: () => this.certificate('user') },
          { text: this.$t('certificate.org.name'), method: () => this.certificate('org') },
        ]
        this.$bus.$emit('actionSheet', actions)
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

      /deep/ .v-badge-count {
        top: -12px;
        right: -44px;
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
