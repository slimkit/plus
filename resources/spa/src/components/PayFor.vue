<template>
  <div @touchmove.prevent>
    <Transition name="toast">
      <div
        v-if="show"
        class="m-pop-box"
        @click="cancel"
      />
    </Transition>
    <Transition name="popr">
      <div
        v-if="show"
        class="m-box-model m-justify-bet m-payfor-box"
      >
        <h2 class="m-payfor-head">
          <slot name="title">
            <span>{{ title || "购买支付" }}</span>
          </slot>
        </h2>
        <div class="m-payfor-body">
          <!-- amount.toFixed(2) -->
          <h3 class="m-payfor-amount">{{ amount }}</h3>
          <!-- 你只需要支付*积分就可查看此内容/图片/视频 -->
          <p>{{ content || `你只需要支付${amount}${currencyUnit}就可查看此${ nodeType }` }}</p>
        </div>
        <div class="m-payfor-foot">
          <button
            class="m-payfor-btn primary"
            @click="showPasswordConfirm"
          >
            {{ confirmText || "购买" }}
          </button>
          <button
            class="m-payfor-btn"
            @click="handelCancel"
          >
            {{ cancelText || "返回" }}
          </button>
        </div>

        <PasswordConfirm
          ref="password"
          @submit="handleOk"
        />
      </div>
    </Transition>
  </div>
</template>
<script>
import { noop } from '@/util'
import PasswordConfirm from '@/components/common/PasswordConfirm.vue'

export default {
  name: 'PayFor',
  components: { PasswordConfirm },
  data () {
    return {
      node: 0,
      nodeType: '图片',
      amount: 0,
      show: false,
      scrollTop: 0,
      title: '',
      cancelText: '',
      confirmText: '',
      content: '',
      checkPassword: false,
    }
  },
  computed: {
    currentCurrency () {
      const user = this.$store.state.CURRENTUSER
      return user.currency.sum || 0
    },
  },
  created: function () {
    window.addEventListener('popstate', this.cancel, false)
    this.$bus.$on('payfor', options => {
      const {
        title,
        cancelText,
        confirmText,
        amount,
        onOk,
        onCancel,
        node,
        onSuccess,
        nodeType = '',
        content = '',
        checkPassword = false,
      } = options

      this.content = content
      this.nodeType = nodeType

      node && (this.node = node)
      title && (this.title = title)
      cancelText && (this.cancelText = cancelText)
      confirmText && (this.confirmText = confirmText);
      (amount || +amount === 0) && (this.amount = amount)
      this.checkPassword = checkPassword

      typeof onOk === 'function' && (this.onOk = onOk)
      typeof onCancel === 'function' && (this.onCancel = onCancel)
      typeof onSuccess === 'function' && (this.onSuccess = onSuccess)
      this.show = true
      this.scrollable = false
    })
  },
  beforeDestroy () {
    window.removeEventListener('popstate', this.cancel, false)
  },
  methods: {
    onOk () {},
    onCancel () {},
    onSuccess () {},
    showPasswordConfirm () {
      if (this.currentCurrency < this.amount) {
        this.$Message.error(`${this.currencyUnit}不足，请充值`)
        this.cancel()
        return this.$router.push({ name: 'currencyRecharge' })
      }
      if (this.node || this.checkPassword) this.$refs.password.show()
      else this.handleOk()
    },
    handleOk (password) {
      this.onOk(password)
      this.node
        ? this.$http
          .post(`/currency/purchases/${this.node}`, { password })
          .then(({ data }) => {
            this.onSuccess(data)
            this.cancel()
          })
          .catch(({ response }) => {
            this.$Message.error(response.data)
          })
        : this.cancel()
    },
    handelCancel () {
      this.onCancel()
      this.$nextTick(this.cancel)
    },
    call () {
      this.show = true
      this.scrollable = false
    },
    cancel () {
      this.node = null
      this.show = false
      this.scrollable = true
      this.$nextTick(() => {
        this.title = ''
        this.cancelText = ''
        this.confirmText = ''

        this.onOk = noop
        this.onCancel = noop
        this.onSuccess = noop
      })
    },
  },
}
</script>

<style lang='less'>
.m-payfor-box {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  margin: auto;
  padding: 0 50px 50px;
  width: 500px;
  height: 650px;
  border-radius: 10px;
  background-color: @body-bg;
}
.m-payfor-head {
  padding: 40px 0;
  color: #333;
  text-align: center;
  font-size: 32px;
  border-bottom: 1px solid @border-color; /*no*/
}
.m-payfor-body {
  margin: 70px 0;
  font-size: 28px;
  line-height: 40px;
  text-align: center;
  color: @text-color3;
  .m-payfor-amount {
    margin-bottom: 30px;
    font-size: 60px;
    color: #fca308;
    letter-spacing: 1px; /* no */
  }
}
.m-payfor-foot {
  .m-payfor-btn {
    width: 100%;
    height: 70px;
    line-height: 70px;
    font-size: 30px;
    border-radius: 6px;
    color: @primary;
    border: 1px solid @primary; /*no*/
    background-color: transparent;
    + .m-payfor-btn {
      margin-top: 20px;
    }
    &.primary {
      color: #fff;
      background-color: @primary;
    }
  }
}
</style>
