<template>
  <Transition
    enter-active-class="animated bounceInRight"
    leave-active-class="animated bounceOutLeft"
  >
    <div class="m-box-model m-pos-f p-signin">
      <CommonHeader>
        {{ $t('auth.login') }}
        <span slot="left" />
        <RouterLink slot="right" to="/signup">
          {{ $t('auth.register.name') }}
        </RouterLink>
      </CommonHeader>

      <main class="m-box-model m-flex-grow1">
        <div class="m-form-row m-main">
          <label for="account">{{ $t('auth.account') }}</label>
          <div class="m-input">
            <input
              id="account"
              v-model="account"
              type="text"
              :placeholder="$t('auth.placeholder.account')"
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
          <label for="password">{{ $t('auth.password') }}</label>
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
              @keyup.enter="signinByAccount"
            >
          </div>
          <svg class="m-style-svg m-svg-def" @click="eye = !eye">
            <use :xlink:href="eye ? '#icon-eye-open' : '#icon-eye-close'" />
          </svg>
        </div>
        <div class="m-box m-aln-center m-text-box m-form-err-box">
          <span>{{ err | plusMessageFirst }}</span>
        </div>
        <div class="m-form-row" style="border: 0">
          <button
            :disabled="disabled"
            class="m-long-btn m-signin-btn"
            @click="signinByAccount"
          >
            <CircleLoading v-if="loading" />
            <span v-else>{{ $t('auth.login') }}</span>
          </button>
        </div>
        <div class="m-box m-aln-center m-justify-bet other-link">
          <RouterLink tag="span" to="/feeds?type=hot">
            <a>{{ $t('auth.guest') }}</a>
          </RouterLink>
          <RouterLink tag="span" to="/forgot">
            <a>{{ $t('auth.forgot.name') }}</a>
          </RouterLink>
        </div>
        <div class="dynamic-signin">
          <RouterLink to="/signin/dynamic">{{ $t('auth.use_phone') }}</RouterLink>
        </div>
      </main>

      <!-- TODO: 其他三方登录方式 -->
      <footer
        v-if="isWechat"
        class="m-box-model m-trhsignin"
      >
        <div class="m-box m-aln-center m-justify-aro m-trhsignin-list">
          <!-- <div class="m-box m-fd-col m-aln-center m-tr-item">
            <div class="m-tr-item-icon">
              <svg class="m-style-svg m-svg-def">
                <use xlink:href="#icon-login-qq"></use>
              </svg>
            </div>
            <span>QQ</span>
          </div> -->
          <div class="m-box m-fd-col m-aln-center m-tr-item" @click="signinByWechat">
            <div class="m-tr-item-icon">
              <svg class="m-style-svg m-svg-def">
                <use xlink:href="#icon-login-wechat" />
              </svg>
            </div>
            <span>{{ $t('auth.wechat') }}</span>
          </div>
        </div>
      </footer>
    </div>
  </Transition>
</template>

<script>
import { signinByAccount } from '@/api/user.js'
import { signinByWechat } from '@/util/wechat.js'

export default {
  name: 'Signin',
  data () {
    return {
      err: '',
      eye: false,
      account: '',
      password: '',
      loading: false,
    }
  },
  computed: {
    disabled () {
      return (
        this.account.length === 0 || this.password.length === 0 || this.loading
      )
    },
    isWechat () {
      return this.$store.state.BROWSER.isWechat
    },
  },
  methods: {
    signinByWechat,
    signinByAccount () {
      this.err = ''
      if (this.account.length === 0) {
        this.err = this.$t('auth.error.account')
        return false
      }

      if (this.password.length < 6) {
        this.err = this.$t('auth.error.password_min', { min: 6 })
        return false
      }

      this.loading = true

      signinByAccount({
        login: this.account,
        password: this.password,
      }).then(state => {
        this.loading = false
        state &&
          this.$nextTick(() => {
            this.$store.dispatch('message/getAllUnreadCount')
            this.$router.push(this.$route.query.redirect || '/feeds?type=hot')
          })
      })
    },
  },
}
</script>

<style lang="less" scoped>
.p-signin {
  background-color: #f4f5f6;

  .other-link {
    margin-top: 25px;
    margin-bottom: 25px;
    padding: 0 30px;
    font-size: 26px;

    a {
      color: @text-color3;
    }
  }

  .dynamic-signin {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-top: 80px;
  }

  .m-trhsignin {
    position: relative;
    text-align: center;
    padding: 60px 80px;

    &:before {
      position: absolute;
      top: 0;
      left: 80px;
      right: 80px;
      content: "";
      display: block;
      height: 0;
      border-top: 1px solid #ccc; /*no*/
    }

    &:after {
      color: #ccc;
      content: "社交账号登陆";
      display: inline-block;
      position: absolute;
      top: 0;
      left: 50%;
      transform: translate(-50%, -50%);
      padding: 0 20px;
      background-color: #f4f5f6;
    }

    .m-tr-item {
      font-size: 24px;
      line-height: 26px;
      color: @text-color3;

      &-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        width: 80px;
        height: 80px;
        border-radius: 40px;
        background-color: #ffffff;

        .m-svg-def {
          width: 38px;
          height: 38px;
        }
      }
    }
  }
}
</style>
