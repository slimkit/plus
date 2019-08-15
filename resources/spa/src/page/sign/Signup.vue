<template>
  <Transition
    enter-active-class="animated bounceInRight"
    leave-active-class="animated bounceOutLeft"
  >
    <div class="p-signup">
      <CommonHeader>
        {{ $t(`auth.register.label.${type}`) }}
        <a
          v-if="allowType === 'all'"
          slot="right"
          @click.prevent="changeType"
        >
          {{ type === 'phone' ? 'auth.label.email' : 'auth.label.phone' | t }}
        </a>
      </CommonHeader>

      <main>
        <div class="m-form-row m-main">
          <label for="username">{{ $t('auth.label.username') }}</label>
          <div class="m-input">
            <input
              id="username"
              v-model.trim="name"
              type="text"
              :placeholder="$t('auth.placeholder.username')"
              maxlength="8"
            >
          </div>
          <svg
            v-show="name.length > 0"
            class="m-style-svg m-svg-def"
            @click="name = ''"
          >
            <use xlink:href="#icon-clean" />
          </svg>
        </div>
        <div
          v-if="currentType === 'sms'"
          class="m-form-row m-main"
        >
          <label for="phone">{{ $t('auth.label.phone') }}</label>
          <div class="m-input">
            <input
              id="phone"
              v-model="phone"
              type="number"
              pattern="[0-9]*"
              oninput="value=value.slice(0, 11)"
              :placeholder="$t('auth.placeholder.phone', [11])"
            >
            <!-- maxlength="11" -->
          </div>
          <span
            :class="{ disabled: phone.length < 11 || countdown > 0 }"
            class="code-text"
            @click="getCode"
          >
            {{ codeText }}
          </span>
        </div>
        <div
          v-if="currentType === 'mail'"
          class="m-form-row m-main"
        >
          <label for="email">{{ $t('auth.label.email') }}</label>
          <div class="m-input">
            <input
              id="email"
              v-model.trim="email"
              type="email"
              :placeholder="$t('auth.placeholder.email')"
            >
          </div>
          <span
            :class="{ disabled: email.length < 4 || countdown > 0 }"
            class="code-text"
            @click="getCode"
          >
            {{ codeText }}
          </span>
        </div>
        <div class="m-form-row m-main">
          <label for="code">{{ $t('auth.label.code') }}</label>
          <div class="m-input">
            <input
              id="code"
              v-model="verifiable_code"
              type="number"
              pattern="[0-9]*"
              oninput="value=value.slice(0, 6)"
              :placeholder="$t('auth.placeholder.code', [4, 6])"
            >
          </div>
          <svg
            v-show="verifiable_code.length > 0"
            class="m-style-svg m-svg-def"
            @click="verifiable_code = ''"
          >
            <use xlink:href="#icon-clean" />
          </svg>
        </div>

        <div class="m-form-row m-main">
          <label for="password">{{ $t('auth.label.password') }}</label>
          <div class="m-input">
            <input
              v-if="eye"
              id="password"
              v-model="password"
              type="text"
              maxlength="16"
              :placeholder="$t('auth.placeholder.password', [6])"
            >
            <input
              v-else
              id="password"
              v-model="password"
              maxlength="16"
              type="password"
              :placeholder="$t('auth.placeholder.password', [6])"
            >
          </div>
          <svg
            class="m-style-svg m-svg-def"
            @click="eye = !eye"
          >
            <use :xlink:href="eye ? '#eye-open' : '#eye-close'" />
          </svg>
        </div>
        <div class="m-box m-aln-center m-text-box m-form-err-box">
          <span>{{ error | plusMessageFirst }}</span>
        </div>
        <div
          class="m-form-row"
          style="border: 0"
        >
          <button
            :disabled="loading||disabled"
            class="m-long-btn m-signin-btn"
            @click="signUp"
          >
            <CircleLoading v-if="loading" />
            <span v-else>{{ $t('auth.register.name') }}</span>
          </button>
        </div>
      </main>
      <footer>
        <template v-if="showProtocol">
          <RouterLink to="/signup/protocol" class="register-protocol">
            <!-- eslint-disable-next-line vue/component-name-in-template-casing -->
            <i18n path="auth.register.agree">
              <span slot="protocol">{{ siteName }}用户注册协议</span>
            </i18n>
          </RouterLink>
        </template>
      </footer>
    </div>
  </Transition>
</template>

<script>
import { mapState } from 'vuex'

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
const prefixCls = 'signup'
const SMS = 'sms' // 手机
const EMAIL = 'mail' // 邮箱

// 手机号码规则
const phoneReg = /^1[3-9]\d{9}$/
// 邮箱验证
const emailReg = /^\w+((-\w+)|(\.\w+))*@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/
// 用户名验证
const usernameReg = /^[a-zA-Z_\u4E00-\u9FA5\uF900-\uFA2D][a-zA-Z0-9_\u4E00-\u9FA5\uF900-\uFA2D]*$/
// 验证码
// const codeReg = /^[0-9]{4,6}$/;

export default {
  name: 'Signup',
  data () {
    return {
      prefixCls,
      eye: false,
      error: '',
      loading: false,

      siteName: process.env.VUE_APP_NAME,

      name: '',
      email: '',
      phone: '',
      countdown: 0,
      password: '',
      verifiable_type: SMS,
      verifiable_code: '',
    }
  },
  computed: {
    ...mapState(['CONFIG']),
    settings () {
      return this.CONFIG.registerSettings || {}
    },
    allowType () {
      // mobile-only | mail-only | all
      return this.settings.method || 'all'
    },
    currentType: {
      get () {
        if (this.allowType === 'all') return this.verifiable_type || SMS
        return this.allowType === 'mail-only' ? EMAIL : SMS
      },
      set (val) {
        this.verifiable_type = val
      },
    },
    showProtocol () {
      return this.settings.showTerms || false
    },
    codeText () {
      return this.countdown > 0 ? this.$t('auth.resend', [this.countdown]) : this.$t('auth.get_code')
    },
    canGetCode () {
      return (
        (this.phone.length === 11 || this.email.length > 4) &&
        this.countdown === 0
      )
    },
    disabled () {
      const {
        name,
        phone,
        email,
        password,
        verifiable_code: verifiableCode,
        verifiable_type: verifiableType,
      } = this.$data

      const res = [name, password, verifiableCode, verifiableType].every(
        i => i !== ''
      )

      if (!res) return true

      if (verifiableCode.length < 4 || verifiableCode.length > 6) return true

      return this.verifiable_type === 'sms'
        ? phone.length !== 11
        : email.length <= 4
    },
    type () {
      return this.currentType === SMS ? 'phone' : 'email'
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
    getCode () {
      if (!this.canGetCode) return
      const phone = this.phone
      const email = this.email
      let params = this.verifiable_type === SMS ? { phone } : { email }
      this.$http
        .post('verifycodes/register', params, {
          validateStatus: status => status === 202,
        })
        .then(() => {
          this.countdown = 60
          this.countDown()
          this.error = ''
        })
        .catch(err => {
          this.$Message.error(err.response.data)
        })
    },
    signUp () {
      const {
        name,
        phone,
        email,
        password,
        verifiable_code: verifiableCode,
        verifiable_type: verifiableType,
      } = this.$data

      // 判断首字符是否为数字
      if (!isNaN(name[0])) { return this.$Message.error({ name: this.$t('auth.error.username_number') }) }

      // 判断特殊字符及空格
      if (!usernameReg.test(name)) { return this.$Message.error({ name: this.$t('auth.error.username_special_char') }) }

      // 判断字节数
      if (strLength(name) > 48 || strLength(name) < 4) { this.$Message.error({ name: this.$t('auth.placeholder.username') }) }

      // 手机号
      if (verifiableType === SMS && !phoneReg.test(phone)) { return this.$Message.error({ phone: this.$t('auth.error.phone') }) }

      // 邮箱
      if (verifiableType !== SMS && !emailReg.test(email)) { return this.$Message.error({ email: this.$t('auth.error.email') }) }

      // 密码长度
      if (password.length < 6) { return this.$Message.error({ password: this.$t('auth.error.password_min', [6]) }) }

      let param = {
        name,
        phone,
        email,
        verifiable_code: verifiableCode,
        password,
        verifiable_type: verifiableType,
        validateStatus: s => s === 201,
      }
      this.loading = true
      verifiableType === SMS ? delete param.email : delete param.phone
      this.$http
        .post('users', param)
        .then(({ data: { token } = {} }) => {
          if (token) {
            this.$Message.success(this.$t('auth.register.success'))
            this.$router.push('/signin')
          }
        })
        .finally(() => {
          this.loading = false
          this.disable = true
        })
    },
    changeType () {
      switch (this.currentType) {
        case SMS:
          this.currentType = EMAIL
          break
        case EMAIL:
          this.currentType = SMS
          break
      }
    },
    popProtocol () {},
  },
}
</script>

<style lang="less" scoped>
.p-signup {
  display: flex;
  flex-direction: column;
  height: 100%;

  > * {
    width: 100%;
  }

  .m-form-row {
    label {
      flex: none;
      width: 5em;
    }
    .m-input {
      padding: 0 30px 0 0;
    }

    .code-text {
      color: @primary;

      &.disabled,
      &[disabled] {
        color: #ccc;
      }
    }

    &-append {
      flex: 0 0 170px;
      width: 170px;
      text-align: right;
      svg {
        width: 38px;
        height: 38px;
        fill: #b3b3b3;
      }
    }
  }

  footer {
    margin-top: 40px;
    padding: 0.2rem;
    text-align: center;

    .register-protocol {
      font-size: 0.3rem;
      color: #666;
    }
  }
}
</style>
