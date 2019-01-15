<template>
  <div class="p-forgot forgot signup">
    <CommonHeader>
      {{ $t('auth.forgot.find') }}
      <template slot="right">
        <a v-show="countdown === 0" @click.prevent="changeType">
          {{ label2 }}
        </a>
      </template>
    </CommonHeader>

    <main>
      <div v-if="verifiable_type === 'sms'" class="m-form-row m-main">
        <label for="phone">{{ $t('auth.label.phone') }}</label>
        <div class="m-input">
          <input
            id="phone"
            v-model="phone"
            type="number"
            autocomplete="off"
            pattern="[0-9]*"
            oninput="value=value.slice(0, 11)"
            :placeholder="$t('auth.placeholder.phone', [11])"
          >
        </div>
        <span
          :class="{ disabled: phone.length < 11 || countdown > 0 }"
          class="m-flex-grow0 m-flex-shrink0 signup-form--row-append c_59b6d7"
          @click="getCode"
        >
          {{ codeText }}
        </span>
      </div>
      <div v-if="verifiable_type === 'mail'" class="m-form-row m-main">
        <label for="mail">{{ $t('auth.label.email') }}</label>
        <div class="m-input">
          <input
            id="mail"
            v-model="email"
            type="mail"
            autocomplete="off"
            :placeholder="$t('auth.placeholder.email')"
          >
        </div>
        <span
          :class="{ disabled: email.length < 11 || countdown > 0 }"
          class="signup-form--row-append c_59b6d7"
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
            v-model.trim="verifiable_code"
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
            type="password"
            maxlength="16"
            :placeholder="$t('auth.placeholder.password', [6])"
          >
        </div>
        <svg class="m-style-svg m-svg-def" @click="eye=!eye">
          <use :xlink:href="`#eye-${eye ? 'open' : 'close' }`" />
        </svg>
      </div>
      <div class="m-box m-aln-center m-text-box m-form-err-box">
        <span>{{ error | plusMessageFirst }}</span>
      </div>

      <div class="m-form-row" style="border: 0">
        <button
          :disabled="loading||disabled"
          class="m-long-btn m-signin-btn"
          @click="handleOk"
        >
          <CircleLoading v-if="loading" />
          <span v-else>{{ $t('modify') }}</span>
        </button>
      </div>
    </main>
  </div>
</template>

<script>
const SMS = 'sms' // 手机
const EMAIL = 'mail' // 邮箱
const phoneReg = /^1[345678]\d{9}$/
const emailReg = /^\w+((-\w+)|(\.\w+))*@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/

export default {
  name: 'Forgot',
  data () {
    return {
      phone: '',
      email: '',
      password: '',
      verifiable_code: '',
      verifiable_type: SMS,

      eye: false,
      countdown: 0,
      loading: false,
      error: '',
    }
  },
  computed: {
    disabled: {
      get () {
        return (
          this.verifiable_code.length < 4 ||
          (this.phone.length < 11 && this.email.length < 4) ||
          this.password.length < 6
        )
      },

      set (val) {
        if (val) {
          this.phone = ''
          this.email = ''
          this.password = ''
          this.verifiable_code = ''
        }
      },
    },
    canGetCode () {
      return (
        (this.phone.length === 11 || this.email.length > 4) &&
        this.countdown === 0
      )
    },
    codeText () {
      if (this.countdown <= 0) return this.$t('auth.get_code')
      return this.$t('auth.resend', [this.countdown])
    },
    label2 () {
      const type = this.verifiable_type !== SMS ? 'phone' : 'email'
      return this.$t(`auth.forgot.type.${type}`)
    },
  },
  methods: {
    handleOk () {
      const {
        phone,
        email,
        password,
        verifiable_code: verifiableCode,
        verifiable_type: verifiableType,
      } = this.$data
      // 手机号
      if (verifiableType === SMS && !phoneReg.test(phone)) {
        this.$Message.error({ phone: this.$t('auth.error.phone') })
        return
      }

      // 邮箱
      if (verifiableType !== SMS && !emailReg.test(email)) {
        this.$Message.error({ email: this.$t('auth.error.email') })
        return
      }

      // 密码长度
      if (password.length < 6) {
        this.$Message.error({ password: this.$t('auth.error.password_min', [6]) })
        return
      }
      if (password.length > 16) {
        this.$Message.error({ password: this.$t('auth.error.password_max', [16]) })
        return
      }

      let param = {
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
        .put('/user/retrieve-password', param)
        .then(() => {
          this.$Message.success(this.$t('auth.forgot.success'))
          this.$lstore.removeData('H5_CUR_USER')
          this.$lstore.removeData('H5_ACCESS_TOKEN')
          this.$store.dispatch('SIGN_OUT')
          this.$router.push('/signin')
          this.loading = false
        })
        .catch(() => {
          this.loading = false
          this.disabled = true
        })
    },
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
      let param = {
        phone,
        email,
      }
      this.verifiable_type === SMS ? delete param.email : delete param.phone
      this.$http
        .post('verifycodes', param, {
          validateStatus: status => status === 202,
        })
        .then(() => {
          this.countdown = 60
          this.countDown()
          this.error = ''
        })
        .catch(
          ({
            response: { status = null, data: { errors = {} } = {} } = {},
          }) => {
            if (status === 500) {
              this.error = { message: this.$t('network.error.e500') }
              return
            }
            if (status === 422) {
              this.error = errors
            }
            setTimeout(() => {
              this.error = ''
            }, 3000)
          }
        )
    },
    changeType () {
      switch (this.verifiable_type) {
        case SMS:
          this.verifiable_type = EMAIL
          break
        case EMAIL:
          this.verifiable_type = SMS
          break
      }
    },
  },
}
</script>

<style lang="less" scoped>
.signup-form--row-append.disabled,
.signup-form--row-append[disabled] {
  color: #d3d3d3;
}

.p-forgot .m-form-row .m-input {
  padding: 0 30px 0 0;
}
.p-forgot .m-form-row label {
  flex: none;
  width: 5em;
}
</style>
