<template>
  <div class="p-currency-recharge">

    <common-header class="header">
      充值{{ currencyUnit }}
      <router-link slot="right" to="/currency/detail" >
        充值记录
      </router-link>
    </common-header>

    <section class="m-currency-panel">
      <h3>充值比率</h3>
      <p>1.0元=100.0{{ currencyUnit }}</p>
    </section>

    <main class="m-box-model m-aln-center m-justify-center">
      <div class="m-box-model m-lim-width m-main">
        <div v-if="items.length" class="m-pinned-amount-btns m-bb1">
          <p class="m-pinned-amount-label">选择充值金额</p>
          <div class="buttons">
            <button
              v-for="item in items"
              :key="item"
              :class="{ active: ~~amount === ~~item && !customAmount }"
              class="m-pinned-amount-btn"
              @click="chooseDefaultAmount(item)" >
              {{ Number(item/100).toFixed(2) }}
            </button>
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
              oninput="value=value.slice(0,8)" >
            <span>元</span>
          </div>
        </div>
      </div>

      <div class="m-entry" @click="selectRechargeType">
        <span class="m-text-box m-flex-grow1">选择充值方式</span>
        <div class="m-box m-aln-end paid-type">{{ rechargeTypeText }}</div>
        <svg class="m-style-svg m-svg-def m-entry-append">
          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-arrow-right" />
        </svg>
      </div>

      <div class="plr20 m-lim-width submit-btn-wrap" style="margin-top: 0.6rem" >
        <button
          :disabled="disabled || loading"
          class="m-long-btn m-signin-btn"
          @click="beforeSubmit" >
          <circle-loading v-if="loading"/>
          <span v-else>确定</span>
        </button>
      </div>

      <footer>
        <p @click="popupRule">
          <svg class="m-style-svg m-svg-small">
            <use xlink:href="#icon-wallet-warning" />
          </svg>
          用户充值协议
        </p>
      </footer>

      <popup-dialog ref="dialog" title="用户充值协议" >
        <p v-html="rule" />
      </popup-dialog>

    </main>
  </div>
</template>

<script>
import { mapState } from "vuex";
import PopupDialog from "@/components/PopupDialog.vue";

const supportTypes = [
  { key: "alipay_wap", title: "支付宝支付", type: "AlipayWapOrder" },
  // 尚未实现 { key: "wx_wap", title: "微信支付", type: "WechatWapOrder" },
  { key: "balance", title: "钱包余额支付", type: "balance" }
];

export default {
  name: "CurrencyRecharge",
  components: { PopupDialog },
  data() {
    return {
      customAmount: null,
      amount: 0,
      rechargeType: "",
      loading: false
    };
  },
  computed: {
    ...mapState({
      currency: "currency"
    }),
    recharge() {
      return this.currency.recharge;
    },
    items() {
      return this.currency.recharge.items;
    },
    rule() {
      const rule = this.currency.recharge.rule || "";
      return rule.replace(/\n/g, "<br>");
    },
    rechargeTypeText() {
      const type = supportTypes.filter(t => t.type === this.form.type).pop();
      return type && type.title;
    },
    form() {
      return {
        amount: this.customAmount * 100 || this.amount,
        type: this.rechargeType
      };
    },
    disabled() {
      return !this.form.amount || !this.rechargeType;
    }
  },
  mounted() {
    if (!this.items.length) this.$store.dispatch("currency/getCurrencyInfo");
  },
  methods: {
    chooseDefaultAmount(amount) {
      this.customAmount && (this.customAmount = null);
      this.amount = amount;
    },
    selectRechargeType() {
      const actions = [];
      actions.push({
        text: "钱包余额支付",
        method: () => selectType("balance")
      });
      supportTypes.forEach(item => {
        if (this.recharge.type.includes(item.key)) {
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
    beforeSubmit() {
      if (this.loading) return;
      const { amount, type } = this.form;
      if (amount < this.recharge.min)
        return this.$Message.error(`最低只能充值${this.recharge.min / 100}元`);

      if (amount > this.recharge.max)
        return this.$Message.error(`最多只能充值${this.recharge.max / 100}元`);

      if (type === "balance") {
        this.rechargeWithBanlance(amount);
      } else {
        this.rechargeWithPay(type, amount);
      }
    },
    async rechargeWithBanlance(amount) {
      this.loading = true;
      const result = await this.$store.dispatch(
        "currency/currency2wallet",
        amount
      );
      this.loading = false;
      if (!result.errors) {
        this.$Message.success("充值成功！");
        this.goBack();
        this.$store.dispatch("fetchUserInfo");
      } else {
        this.$Message.error(result.errors);
      }
    },
    async rechargeWithPay(type, amount) {
      this.loading = true;
      const url = await this.$store.dispatch("currency/requestRecharge", {
        amount,
        redirect: `${window.location.origin}${process.env.BASE_URL}/currency`, // 支付成功后回调地址
        type
      });
      this.loading = false;
      location.href = url;
    },
    popupRule() {
      this.$refs.dialog.show();
    }
  }
};
</script>

<style lang="less" scoped>
@import "./currency.less";

.p-currency-recharge {
  .m-currency-panel p {
    font-size: 60px;
  }
  .m-pinned-amount-btns {
    padding-bottom: 0;
    .buttons {
      display: flex;
      flex-wrap: wrap;

      > button {
        margin: 0 20px 30px;
        width: calc(~"33% - 40px");
      }
    }
  }
  .paid-type {
    font-size: 30px;
    color: #999;
  }
  .submit-btn-wrap {
    margin-bottom: 90px;
  }
  .m-entry {
    line-height: 1;
    width: 100%;
    padding: 0 20px;
    background-color: #fff;
    margin-top: 20px;
  }
}
</style>
