<template lang="html">
  <div class="p-wallet wallet">

    <common-header class="header">
      钱包
      <router-link slot="left" to="/profile">
        <svg class="m-style-svg m-svg-def">
          <use xlink:href="#icon-back" />
        </svg>
      </router-link>
      <router-link
        slot="right"
        :to="{ path: 'detail' }"
        append>
        明细
      </router-link>
    </common-header>

    <section class="m-wallet-panel">
      <h3>账户余额(元)</h3>
      <p>{{ balance }}</p>
    </section>

    <ul class="m-box-model m-entry-group padding">
      <router-link
        :to="{path: 'recharge'}"
        append
        tag="li"
        class="m-entry">
        <svg class="m-style-svg m-svg-def m-entry-prepend">
          <use xlink:href="#icon-currency-recharge" />
        </svg>
        <span class="m-text-box m-flex-grow1">充值</span>
        <svg class="m-style-svg m-svg-def m-entry-append">
          <use xlink:href="#icon-arrow-right" />
        </svg>
      </router-link>
      <router-link
        :to="{path: &quot;withdraw&quot;}"
        append
        tag="li"
        class="m-entry">
        <svg class="m-style-svg m-svg-def m-entry-prepend">
          <use xlink:href="#icon-profile-wallet" />
        </svg>
        <span class="m-text-box m-flex-grow1">提现</span>
        <svg class="m-style-svg m-svg-def m-entry-append">
          <use xlink:href="#icon-arrow-right" />
        </svg>
      </router-link>
    </ul>

    <ul class="m-box-model m-entry-group padding">
      <router-link
        :to="{path: '/currency/recharge'}"
        tag="li"
        class="m-entry">
        <svg class="m-style-svg m-svg-def m-entry-prepend">
          <use xlink:href="#icon-currency-recharge" />
        </svg>
        <span class="m-text-box m-flex-grow1">{{ currencyUnit }}充值</span>
        <svg class="m-style-svg m-svg-def m-entry-append">
          <use xlink:href="#icon-arrow-right" />
        </svg>
      </router-link>
    </ul>

    <footer>
      <p @click="popupRule">
        <svg class="m-style-svg m-svg-small">
          <use xlink:href="#icon-wallet-warning" />
        </svg>
        充值提现规则
      </p>
    </footer>

    <popup-dialog
      ref="dialog"
      title="充值提现规则">
      <p v-html="rule"/>
    </popup-dialog>

  </div>
</template>

<script>
import PopupDialog from "@/components/PopupDialog.vue";

export default {
  name: "Wallet",
  components: { PopupDialog },
  data() {
    return {};
  },
  computed: {
    goldName() {
      const {
        site: { gold_name: { name = "金币" } = {} } = {}
      } = this.$store.state.CONFIG;
      return name;
    },
    user() {
      return this.$store.state.CURRENTUSER;
    },
    new_wallet() {
      return this.user.new_wallet || { balance: 0 };
    },
    balance() {
      const raito = this.$store.state.wallet.ratio || 100;
      return (this.new_wallet.balance / raito).toFixed(2);
    },
    rule() {
      const rule = this.$store.state.wallet.rule || "";
      return rule.replace(/\n/g, "<br>");
    }
  },
  mounted() {
    this.$store.dispatch("wallet/getWalletInfo");

    const amount = this.$route.query.total_amount;
    if (amount) {
      this.$store.dispatch("fetchUserInfo");
      this.$Message.success(
        `共消耗${amount}元, 获得 ${amount * 100} ${this.currencyUnit}!`
      );
    }
  },
  methods: {
    popupRule() {
      this.$refs.dialog.show();
    }
  }
};
</script>

<style lang="less" scoped>
@panel-color: @primary;

.header {
  background-color: @panel-color;
  color: #fff;
  border-bottom: none;
  a {
    color: inherit;
  }
}

.p-wallet {
  .entry__group:first-of-type {
    margin-top: 0;
  }
}
.m-wallet-panel {
  padding: 60px 30px;
  color: #fff;
  font-size: 28px;
  background-color: @panel-color;
  h3 {
    opacity: 0.7;
  }
  p {
    margin-top: 80px;
    font-size: 100px;
    letter-spacing: 2px;
  }
}
footer {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 1rem;

  p {
    font-size: 26px;
    color: #999;
  }
}
</style>
