<template>
  <div class="p-wechat-signin">
    <header class="m-box m-aln-center m-head-top m-pos-f m-main m-bb1">
      <RouterLink
        tag="div"
        to="/"
        class="m-box m-aln-center m-flex-grow1 m-flex-base0"
      >
        <svg class="m-style-svg m-svg-def">
          <use xlink:href="#icon-back" />
        </svg>
      </RouterLink>
      <div class="m-box m-aln-center m-justify-center m-flex-grow1 m-flex-base0 m-head-top-title">
        <span>绑定账号</span>
      </div>
      <div class="m-box m-aln-center m-justify-end m-flex-grow1 m-flex-base0" />
    </header>
    <!-- loading -->
    <div v-if="loading" class="m-spinner m-pos-f" />

    <div v-else>
      <Transition name="toast">
        <div class="m-pop-box" />
      </Transition>
      <Transition name="pop">
        <div class="m-lim-width m-pos-f m-wechat-box">
          <RouterLink tag="button" to="/wechat/signup">
            <a>注册新用户</a>
          </RouterLink>
          <RouterLink tag="button" to="/wechat/bind">
            <a>绑定已有用户</a>
          </RouterLink>
        </div>
      </Transition>
    </div>
  </div>
</template>

<script>
export default {
  name: 'WechatSignin',
  data () {
    return {
      loading: true,
      accessToken: '',
      WechatUname: '',
    }
  },

  watch: {
    // 根据获取到access_token检查用户是否已经被楚泽
    showBind (val) {
      this.showBind = val
      if (val) {
        this.showRegister = !val
      }
    },
    showRegister (val) {
      this.showRegister = val
      if (val) {
        this.showBind = !val
      }
    },
  },
  mounted () {
    const { code } = this.$route.query
    this.resolveUser(code)
  },
  methods: {
    goDefault () {
      this.showRegister = false
      this.showBind = false
    },
    action (action) {
      this[action] = true
    },
    async resolveUser (code) {
      let openId = this.$lstore.getData('H5_WECHAT_MP_OPENID')
      let accessToken = this.$lstore.getData('H5_WECHAT_MP_ASTOKEN', true)

      if (!accessToken || !openId) {
        const { data } = await this.$http.get(`/socialite/getAccess/${code}`, { validateStatus: s => s === 200 })
        const { access_token: newAccessToken, openid: newOpenId } = data
        accessToken = newAccessToken
        openId = newOpenId

        this.$lstore.setData('H5_WECHAT_MP_OPENID', openId)
        this.$lstore.setData('H5_WECHAT_MP_ASTOKEN', accessToken, true)
      }
      this.accessToken = accessToken
      this.$http
        .post(
          'socialite/wechat',
          { access_token: this.accessToken },
          { validateStatus: s => s === 201 || s === 404 }
        )
        .then(({ status, data: { token = '', user = {} } = {} }) => {
          if (status !== 201) {
            this.loading = false
            this.getWechatUserInfo(accessToken, openId)
            return
          }
          // 保存用户信息 并跳转
          this.$router.push(this.$route.query.redirect || '/feeds?type=hot')
          this.$nextTick(() => {
            this.$store.commit('SAVE_USER', user)
            this.$store.dispatch('GET_UNREAD_COUNT')
            this.$store.dispatch('GET_NEW_UNREAD_COUNT')
            this.$lstore.setData('H5_ACCESS_TOKEN', `Bearer ${token}`)
            this.$store.commit('SAVE_CURRENTUSER', user)
          })
        })
    },

    getWechatUserInfo (accessToken, openid) {
      this.$http
        .post(
          'socialite/getWechatUser',
          { openid, access_token: accessToken },
          { validateStatus: s => s === 200 }
        )
        .then(({ data: { nickname = '' } = {} }) => {
          this.WechatUname = nickname
          this.$lstore.setData('H5_WECHAT_NICKNAME', nickname)
        })
    },
  },
}
</script>

<style lang="less">
.m-wechat-box {
  overflow: hidden;
  width: 70%;
  height: 95 * 2px;
  border-radius: 12px;
  margin: auto;
  button {
    background-color: #fff;
    width: 100%;
    height: 95px;
    + button {
      border-top: 1px solid @border-color; /* no */
    }
  }
}
</style>
