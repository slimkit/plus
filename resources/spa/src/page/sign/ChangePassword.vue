<template>
  <div class="p-change-password">
    <CommonHeader>
      {{ $t('auth.change_password.name') }}
      <template slot="right">
        <span
          :class="{disabled: disabled || loading}"
          class="submit-btn"
          @click="handleOk"
        >
          {{ $t('change') }}
        </span>
      </template>
    </CommonHeader>

    <main>
      <div class="m-form-row m-main">
        <label for="old-password">{{ $t('auth.change_password.label.old') }}</label>
        <div class="m-input">
          <input
            id="old-password"
            v-model="oldPassword"
            type="password"
            maxlength="16"
            autocomplete="off"
            :placeholder="$t('auth.change_password.placeholder.old', [6])"
          >
        </div>
      </div>
      <div class="m-form-row m-main">
        <label for="new-password">{{ $t('auth.change_password.label.new') }}</label>
        <div class="m-input">
          <input
            id="new-password"
            v-model="newPassword"
            type="password"
            maxlength="16"
            autocomplete="off"
            :placeholder="$t('auth.change_password.placeholder.new', [6])"
          >
        </div>
      </div>
      <div class="m-form-row m-main">
        <label for="re-password">{{ $t('auth.change_password.label.confirm') }}</label>
        <div class="m-input">
          <input
            id="re-password"
            v-model="rePassword"
            type="password"
            maxlength="16"
            autocomplete="off"
            :placeholder="$t('auth.change_password.placeholder.confirm')"
          >
        </div>
      </div>

      <div class="m-box m-aln-center m-text-box m-form-err-box">
        <span>{{ error | plusMessageFirst }}</span>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  name: 'Forgot',
  data () {
    return {
      oldPassword: '',
      newPassword: '',
      rePassword: '',
      loading: false,
      error: '',
    }
  },
  computed: {
    disabled: {
      get () {
        const rules = [this.oldPassword, this.newPassword, this.rePassword]
        return rules.some(val => val.length < 6)
      },

      set (val) {
        if (val) {
          this.oldPassword = ''
          this.newPassword = ''
          this.rePassword = ''
        }
      },
    },
  },
  methods: {
    handleOk () {
      if (this.disabled) return
      const { oldPassword, newPassword, rePassword } = this.$data

      // 密码长度
      if (newPassword.length < 6) {
        this.$Message.error({ newPassword: this.$t('auth.change_password.message.length', [6, 16]) })
        return
      }

      // 重复新密码
      if (rePassword !== newPassword) {
        this.$Message.error({ rePassword: this.$t('auth.change_password.message.confirm') })
        return
      }

      let param = {
        old_password: oldPassword,
        password: newPassword,
        password_confirmation: rePassword,
        validateStatus: s => s === 204,
      }
      this.loading = true
      this.$http
        .put('/user/password', param)
        .then(() => {
          this.$Message.success(this.$t('auth.change_password.message.success'))
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
  },
}
</script>

<style lang="less" scoped>
.p-change-password {
  .submit-btn {
    color: @primary;

    &.disabled {
      color: @gray;
    }
  }

  .m-form-row {
    .m-input {
      padding-right: 30px;
    }

    label {
      width: 180px;
    }
  }
}
</style>
