<template>
  <Transition
    enter-active-class="animated bounceInRight"
    leave-active-class="animated bounceOutLeft"
  >
    <div class="m-box-model m-pos-f p-signin-dynamic">
      <CommonHeader>
        {{ $t('auth.one_key') }}
        <RouterLink slot="right" to="/signup">{{ $t('auth.register.name') }}</RouterLink>
      </CommonHeader>

      <main class="m-box-model m-flex-grow1">
        <div class="m-form-row m-main">
          <label for="account">{{ $t('auth.label.phone') }}</label>
          <div class="m-input">
            <input
              id="account"
              v-model="account"
              maxlength="11"
              type="tel"
              :placeholder="$t('auth.placeholder.phone', [11])"
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
          <label for="password">{{ $t('auth.label.code') }}</label>
          <div class="m-input">
            <input
              id="password"
              v-model="code"
              maxlength="6"
              type="number"
              :placeholder="$t('auth.placeholder.code', [4, 6])"
              @keyup.enter="signinByAccount"
            >
          </div>
          <span :class="[{ disabledCode }, 'get-code']" @click="beforeGetCode">{{ codeText }}</span>
        </div>
        <div class="m-box m-aln-center m-text-box m-form-err-box">
          <span>{{ err | plusMessageFirst }}</span>
        </div>
        <div class="m-form-row" style="border: 0">
          <button
            :disabled="disabled"
            class="m-long-btn m-signin-btn"
            @click="isRegister ? signupByCode() : signinByCode()"
          >
            <CircleLoading v-if="loading" />
            <span v-else>{{ $t('auth.login') }}</span>
          </button>
        </div>

        <div class="dynamic-signin">
          <a @click="goBack">{{ $t('auth.use_account') }}</a>
        </div>
      </main>
    </div>
  </Transition>
</template>

<script>
import { signinByAccount } from '@/api/user.js'
import { generateString } from '@/util'

export default {
  name: 'Signin',
  data () {
    return {
      err: '',
      account: '',
      code: '',
      loading: false,
      codeLoading: false,
      countdown: 0,
      isRegister: false,
    }
  },
  computed: {
    disabled () {
      return (
        this.account.length !== 11 ||
        this.code.length < 4 ||
        this.code.length > 6 ||
        this.loading
      )
    },
    disabledCode () {
      return this.account.length !== 11 || this.countdown || this.codeLoading
    },
    codeText () {
      return this.countdown > 0 ? this.$t('auth.resend', [this.countdown]) : this.$t('auth.get_code')
    },
  },
  methods: {
    countDown () {
      const t = setInterval(() => {
        if (--this.countdown <= 0) {
          this.countdown = 0
          clearInterval(t)
        }
      }, 1000)
    },
    beforeGetCode () {
      if (this.disabledCode) return
      this.codeLoading = true
      this.$http
        .get(`/users/${this.account}`, {
          validateStatus: s => s === 200 || s === 404,
        })
        .then(({ data: user = {} }) => {
          if (user.id) this.isRegister = false
          else this.isRegister = true
          this.getCode()
        })
        .catch(err => {
          this.codeLoading = false
          return Promise.reject(err)
        })
    },
    getCode () {
      let param = { phone: this.account }
      const url = this.isRegister ? '/verifycodes/register' : '/verifycodes'
      this.$http
        .post(url, param, { validateStatus: s => s === 202 })
        .then(() => {
          this.countdown = 60
          this.countDown()
        })
        .finally(() => {
          this.codeLoading = false
        })
    },
    signinByCode () {
      this.loading = true

      signinByAccount({
        login: this.account,
        verifiable_code: this.code,
      }).then(state => {
        this.loading = false
        state &&
          this.$nextTick(() => {
            this.$router.push(this.$route.query.redirect || '/feeds?type=hot')
          })
      })
    },
    signupByCode () {
      this.loading = true
      const params = {
        name: `${this.$t('auth.user')}` + generateString(6),
        phone: this.account,
        verifiable_type: 'sms',
        verifiable_code: this.code,
      }
      this.$http
        .post('/users', params)
        .then(({ data: { token } }) => {
          if (token) {
            this.$Message.success(this.$t('auth.register.success'))
            this.$lstore.setData('H5_ACCESS_TOKEN', `Bearer ${token}`)
            this.$store.dispatch('fetchUserInfo')
            this.$nextTick(() => {
              this.$router.push('/')
            })
          }
        })
        .catch(err => {
          this.$Message.error(err.response.data)
        })
        .finally(() => {
          this.loading = false
        })
    },
  },
}
</script>

<style lang="less" scoped>
.p-signin-dynamic {
  background-color: #f4f5f6;

  .dynamic-signin {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-top: 80px;
  }

  .get-code {
    color: @primary;

    &.disabledCode {
      color: @gray;
    }
  }
}
</style>
