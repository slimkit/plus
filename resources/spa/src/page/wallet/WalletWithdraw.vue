<template lang="html">
  <div class="p-wallet-withdraw">

    <common-header>
      提现
      <router-link
        slot="right"
        class="withdraw-detail"
        to="/wallet/withdraw/detail">
        提现明细
      </router-link>
    </common-header>

    <main class="m-box-model m-aln-center m-justify-center">
      <div class="m-box-model m-lim-width m-main">
        <div class="m-box m-aln-center m-justify-bet m-bb1 m-bt1 m-pinned-row plr20 m-pinned-amount-customize">
          <span>提现金额</span>
          <div class="m-box m-aln-center">
            <input
              v-model.number="amount"
              type="number"
              class="m-text-r"
              pattern="[0-9]*"
              placeholder="输入金额"
              oninput="value=value.slice(0,8)">
            <span>元</span>
          </div>
        </div>
      </div>

      <div
        class="m-entry"
        @click="selectWithdrawType">
        <span class="m-text-box m-flex-grow1">选择提现方式</span>
        <div class="m-box m-aln-end paid-type">{{ withdrawTypeText }}</div>
        <svg class="m-style-svg m-svg-def">
          <use xlink:href="#icon-arrow-right" />
        </svg>
      </div>

      <div class="m-box-model m-lim-width m-main">
        <div class="m-box m-aln-center m-justify-bet m-bb1 m-bt1 m-pinned-row plr20 m-pinned-amount-customize">
          <span>提现账户</span>
          <div class="m-box m-aln-center">
            <input
              v-model="account"
              type="text"
              class="m-text-r"
              placeholder="输入提现账户">
          </div>
        </div>
      </div>

      <div
        class="plr20 m-lim-width"
        style="margin-top: 0.6rem">
        <button
          :disabled="disabled || loading"
          class="m-long-btn m-signin-btn"
          @click="handleOk">
          <circle-loading v-if="loading" />
          <span v-else>确定</span>
        </button>
      </div>
    </main>
  </div>
</template>

<script>
import { mapState } from 'vuex'

const supportType = {
  alipay_wap: { title: '支付宝提现', type: 'alipay' },
  // 未实现 wx_wap: { title: "微信提现", type: "wx" }
}

export default {
  name: 'WalletWithdraw',
  data () {
    return {
      amount: '',
      account: '',
      selectedType: '',
      loading: false,
    }
  },
  computed: {
    ...mapState({
      wallet: 'wallet',
    }),
    disabled () {
      const { value, account, type } = this.form
      return !value || !account || !type
    },
    form () {
      const selectedType = supportType[this.selectedType] || {}
      return {
        value: this.amount * 100,
        type: selectedType.type,
        account: this.account,
      }
    },
    withdrawTypeText () {
      const selectedType = supportType[this.selectedType] || {}
      return selectedType.title
    },
    walletType () {
      return this.wallet.type || []
    },
  },
  mounted () {
    if (!this.walletType.length) this.$store.dispatch('wallet/getWalletInfo')
  },
  methods: {
    async handleOk () {
      if (this.loading) return
      this.loading = true
      const { message } = await this.$store
        .dispatch('wallet/requestWithdraw', this.form)
        .catch(() => {
          this.loading = false
        })
      this.$Message.success(message)
      this.$router.replace('/wallet/withdraw/detail')
    },
    selectWithdrawType () {
      const actions = []
      for (let key in supportType) {
        const type = supportType[key]
        if (this.walletType.includes(key)) {
          actions.push({
            text: type.title,
            method: () => {
              this.selectedType = key
            },
          })
        }
      }

      this.$bus.$emit(
        'actionSheet',
        actions,
        '取消',
        actions.length ? undefined : '当前未支持任何提现方式'
      )
    },
  },
}
</script>

<style lang="less" scoped>
.m-entry {
  width: 100%;
  padding: 0 20px;
  background-color: #fff;
}

.paid-type {
  font-size: 30px;
  color: #999;
}

.withdraw-detail {
  width: 5em;
  text-align: right;
}
</style>
