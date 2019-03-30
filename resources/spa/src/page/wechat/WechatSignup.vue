<template>
  <Transition
    enter-active-class="animated bounceInRight"
    leave-active-class="animated bounceOutLeft"
  >
    <div class="p-signup">
      <header class="m-box m-aln-center m-head-top m-pos-f m-main m-bb1">
        <div class="m-box m-aln-center m-flex-grow1 m-flex-base0" />
        <div class="m-box m-aln-center m-justify-center m-flex-grow1 m-flex-base0 m-head-top-title">
          <span>完善资料</span>
        </div>
        <div class="m-box m-aln-center m-justify-end m-flex-grow1 m-flex-base0">
          <RouterLink to="/wechat/bind">
            <a>已有账号</a>
          </RouterLink>
        </div>
      </header>

      <main style="padding-top: 0.9rem">
        <div class="m-form-row m-main">
          <label for="nickname">用户名</label>
          <div class="m-input">
            <input
              id="nickname"
              v-model="nickname"
              type="text"
              placeholder="请输入用户名"
              @focus="onFocus"
              @blur="checkName(nickname)"
            >
          </div>
          <svg
            v-show="nickname.length > 0"
            class="m-style-svg m-svg-def"
            @click="nickname = ''"
          >
            <use
              xmlns:xlink="http://www.w3.org/1999/xlink"
              xlink:href="#icon-clean"
            />
          </svg>
        </div>
        <div class="m-box m-aln-center m-text-box m-form-err-box">
          <span>{{ err | plusMessageFirst }}</span>
        </div>
        <div
          v-if="displayBtn"
          class="m-form-row"
          style="border: 0"
        >
          <button
            :disabled="err||loading"
            class="m-long-btn m-signin-btn"
            @click="signupByWechat"
          >
            <CircleLoading v-if="loading" />
            <span v-else>提交</span>
          </button>
        </div>
      </main>
    </div>
  </Transition>
</template>

<script>
const UNAME_REG = /^[a-zA-Z_\u4E00-\u9FA5\uF900-\uFA2D][a-zA-Z0-9_\u4E00-\u9FA5\uF900-\uFA2D]*$/

export default {
  name: 'WechatSignup',
  data () {
    return {
      loading: false,

      err: '',
      nickname: '',
      accessToken: '',
      displayBtn: false,
    }
  },
  created () {
    this.nickname = this.$lstore.getData('H5_WECHAT_NICKNAME', true) || ''
    this.accessToken = this.$lstore.getData('H5_WECHAT_MP_ASTOKEN', true) || ''
    this.checkName(this.nickname)
  },

  methods: {
    onFocus () {
      this.err = null
    },
    checkName (name) {
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

      function isNum (val) {
        if (val === '' || val === null) return false
        return !isNaN(val)
      }
      if (!name) {
        this.err = { error: '用户名不能为空' }
        return false
      }

      // 判断首字符是否为数字
      if (isNum(name[0])) {
        this.err = { error: '用户名不能以数字开头' }
        return false
      }

      if (strLength(name) > 48 || strLength(name) < 4) {
        this.err = { error: '用户名不能少于2个中文或4个英文' }
        return false
      }

      // 判断特殊字符及空格
      if (!UNAME_REG.test(name)) {
        this.err = { error: '用户名不能包含特殊符号以及空格' }
        return false
      }

      this.err = null
      this.displayBtn = true
      return true
    },

    signupByWechat () {
      if (this.loading) return
      this.loading = true
      this.$http
        .patch('socialite/wechat', {
          validateStatus: s => s === 201,
          name: this.nickname,
          access_token: this.accessToken,
        })
        .then(({ data: { user = {}, token = '' } = {} }) => {
          // 保存用户信息 并跳转
          this.$store.commit('SAVE_CURRENTUSER', { ...user, token })
          this.$nextTick(() => {
            this.$lstore.setData('H5_ACCESS_TOKEN', `Bearer ${token}`)
            this.$router.push('/feeds?type=hot')
            this.$store.dispatch('GET_UNREAD_COUNT')
            this.$store.dispatch('GET_NEW_UNREAD_COUNT')
            this.$store.commit('SAVE_USER', user)
          })
        })
        .catch(
          ({
            response: {
              data = { message: '注册失败, 请检查表单内容是否正确' },
            } = {},
          }) => {
            this.err = data
            this.loading = false
          }
        )
    },
  },
}
</script>
