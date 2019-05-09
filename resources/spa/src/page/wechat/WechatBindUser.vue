<template>
  <Transition
    enter-active-class="animated bounceInRight"
    leave-active-class="animated bounceOutLeft"
  >
    <div class="p-signin m-pos-f">
      <header class="m-box m-aln-center m-head-top m-pos-f m-main m-bb1">
        <div class="m-box m-aln-center m-flex-grow1 m-flex-base0" />
        <div class="m-box m-aln-center m-justify-center m-flex-grow1 m-flex-base0 m-head-top-title">
          <span>已有账号</span>
        </div>
        <div class="m-box m-aln-center m-justify-end m-flex-grow1 m-flex-base0">
          <RouterLink to="/wechat/signup">
            <a>新账号</a>
          </RouterLink>
        </div>
      </header>
      <main style="padding-top: 0.9rem">
        <div class="m-form-row m-main">
          <label for="account">账户</label>
          <div class="m-input">
            <input
              id="account"
              v-model="account"
              type="text"
              placeholder="用户名/手机号/邮箱"
              @focus="onFocus"
            >
          </div>
          <svg
            v-show="account.length > 0"
            class="m-style-svg m-svg-def"
            @click="account = ''"
          >
            <use xlink:href="#icon-clean" />
          </svg>
        </div>
        <div class="m-form-row m-main">
          <label for="password">密码</label>
          <div class="m-input">
            <input
              v-if="eye"
              id="password"
              v-model="password"
              type="text"
              placeholder="输入6位以上登录密码"
              @focus="onFocus"
            >
            <input
              v-else
              id="password"
              v-model="password"
              type="password"
              placeholder="输入6位以上登录密码"
              @focus="onFocus"
            >
          </div>
          <svg class="m-style-svg m-svg-def" @click="eye=!eye">
            <use :xlink:href="`#eye-${eye?'open':'close'}`" />
          </svg>
        </div>
        <div class="m-box m-aln-center m-text-box m-form-err-box">
          <Transition enter-active-class="animated shake">
            @focus="onFocus"
            <span v-if="err">{{ (err | plusMessageFirst) }}</span>
          </Transition>
        </div>
        <div class="m-form-row" style="border: 0">
          <button
            :disabled="disabled"
            class="m-long-btn m-signin-btn"
            @click="bindUser"
          >
            <CircleLoading v-if="loading" />
            <span v-else>绑定账号</span>
          </button>
        </div>
      </main>
    </div>
  </Transition>
</template>

<script>
function strLength (str) {
  let totalLength = 0
  let i = 0
  let charCode
  for (; i < str.length; i++) {
    charCode = str.charCodeAt(i)
    if (charCode < 0x007f) {
      totalLength = totalLength + 1
    } else if (charCode >= 0x0080 && charCode <= 0x07ff) {
      totalLength += 2
    } else if (charCode >= 0x0800 && charCode <= 0xffff) {
      totalLength += 3
    }
  }
  return totalLength
}

export default {
  name: 'WechatBindUser',
  data: () => ({
    err: '',
    eye: false,
    account: '',
    password: '',
    loading: false,
    accessToken: '',
  }),
  computed: {
    disabled () {
      const { account, password } = this.$data
      return !(
        [account, password].every(i => i !== '') && strLength(account) > 3
      )
    },
  },
  created () {
    this.accessToken = this.$lstore.getData('H5_WECHAT_MP_ASTOKEN', true)
  },
  methods: {
    onFocus () {
      this.err = ''
    },
    bindUser () {
      this.err = ''
      if (this.loading) return

      const {
        account: login,
        password,
        accessToken,
      } = this.$data
      if (!login) {
        this.err = { error: '账号不能为空' }
        return
      }

      if (!password) {
        this.err = { error: '密码不能为空' }
        return
      }

      if (!accessToken) {
        this.err = { error: '未获取到微信授权' }
        return
      }

      let param = {
        login,
        access_token: accessToken,
        password,
      }
      this.loading = true
      this.$http
        .put('socialite/wechat', param, { validateStatus: s => s === 201 })
        .then(({ data: { token = '', user = {} } = {} }) => {
          this.$store.commit('SAVE_CURRENTUSER', { ...user, token })
          this.$nextTick(() => {
            this.$router.push(this.$route.query.redirect || '/feeds?type=hot')
            this.$store.dispatch('GET_UNREAD_COUNT')
            this.$store.dispatch('GET_NEW_UNREAD_COUNT')
            this.$store.commit('SAVE_USER', user)
          })
        })
        .finally(() => {
          this.loading = false
        })
    },
  },
}
</script>
