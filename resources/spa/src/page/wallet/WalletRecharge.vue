<template>
  <div class="m-box-model p-wallet-recharge m-pos-f">

    <common-header>充值</common-header>

    <main class="m-box-model m-aln-center m-justify-center">
      <div class="m-box-model m-lim-width m-main">
        <div v-if="rechargeItems.length" class="m-pinned-amount-btns m-bb1">
          <p class="m-pinned-amount-label">选择充值金额</p>
          <div class="m-box m-aln-center ">
            <button
              v-for="item in rechargeItems"
              :key="item"
              :style="{ width: `${1 / rechargeItems.length * 100}%` }"
              :class="{ active: ~~amount === ~~item && !customAmount }"
              class="m-pinned-amount-btn"
              @click="chooseDefaultAmount(item)">{{ item.toFixed(2) }}</button>
          </div>
        </div>
        <div class="m-box m-aln-center m-justify-bet m-pinned-row plr20 m-pinned-amount-customize">
          <span>自定义金额</span>
          <div class="m-box m-aln-center">
            <input
              v-model.number="customAmount"
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
        @click="selectRechargeType">
        <span class="m-text-box m-flex-grow1">选择充值方式</span>
        <div class="m-box m-aln-end paid-type">{{ rechargeTypeText }}</div>
        <svg class="m-style-svg m-svg-def m-entry-append">
          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-arrow-right"/>
        </svg>
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
import { mapGetters, mapState } from "vuex";

const supportTypes = [
  { key: "alipay_wap", title: "支付宝支付", type: "AlipayWapOrder" }
  // 尚未实现 { key: "wx_wap", title: "微信支付" }
];

export default {
  name: "WalletRecharge",
  data() {
    return {
      customAmount: null,
      amount: 0,
      rechargeType: "",
      loading: false
    };
  },
  computed: {
    ...mapState({ wallet: state => state.wallet }),
    ...mapGetters({ rechargeItems: "wallet/rechargeItems" }),
    rechargeTypeText() {
      const type = supportTypes.filter(t => t.type === this.form.type).pop();
      return type && type.title;
    },
    form() {
      return {
        amount: this.customAmount * 100 || this.amount * 100,
        type: this.rechargeType
      };
    },
    disabled() {
      return this.form.amount <= 0 || !this.rechargeType;
    }
  },
  mounted() {
    if (!this.rechargeItems.length)
      this.$store.dispatch("wallet/getWalletInfo");
  },
  methods: {
    chooseDefaultAmount(amount) {
      this.customAmount && (this.customAmount = null);
      this.amount = amount;
    },
    selectRechargeType() {
      const actions = [];
      supportTypes.forEach(item => {
        if (this.wallet.type.includes(item.key)) {
          actions.push({
            text: item.title,
            method: () => selectType(item.type)
          });
        }
      });
      this.$bus.$emit(
        "actionSheet",
        actions,
        "取消",
        actions.length ? undefined : "当前未支持任何充值方式"
      );

      const selectType = type => {
        this.rechargeType = type;
      };
    },
    async handleOk() {
      if (this.loading) return;
      const { amount, type } = this.form;
      this.loading = true;
      // 获取第三方支付地址,跳转过去
      const url = await this.$store.dispatch("wallet/requestRecharge", {
        amount,
        redirect: `${window.location.origin}${process.env.BASE_URL}/wallet`, // 支付成功后回调地址
        type
      });
      location.href = url;
    }
  }
};
</script>

<style lang="less" scoped>
.p-wallet-recharge {
  .paid-type {
    font-size: 30px;
    color: #999;
  }
  .submit-btn-wrap {
    margin-bottom: 90px;
  }
  .m-entry {
    width: 100%;
    padding: 0 20px;
    background-color: #fff;
    margin-top: 20px;
  }
}
</style>
