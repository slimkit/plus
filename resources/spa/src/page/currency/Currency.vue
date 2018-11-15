<template lang="html">
  <div class="p-currency">

    <common-header class="header">
      我的{{ currencyUnit }}
      <router-link slot="left" to="/profile">
        <svg class="m-style-svg m-svg-def">
          <use xlink:href="#icon-back" />
        </svg>
      </router-link>
      <router-link
        slot="right"
        to="/currency/journal-detail">
        明细
      </router-link>
    </common-header>

    <section class="m-currency-panel">
      <h3>当前{{ currencyUnit }}</h3>
      <p>{{ user.currency.sum || 0 }}</p>
    </section>

    <ul class="m-box-model m-entry-group padding">
      <router-link
        to="/currency/recharge"
        tag="li"
        class="m-entry">
        <svg class="m-style-svg m-svg-def m-entry-prepend">
          <use xlink:href="#icon-currency-recharge" />
        </svg>
        <span class="m-text-box m-flex-grow1">充值{{ currencyUnit }}</span>
        <svg class="m-style-svg m-svg-def m-entry-append">
          <use xlink:href="#icon-arrow-right" />
        </svg>
      </router-link>
      <router-link
        to="/currency/withdraw"
        tag="li"
        class="m-entry">
        <svg class="m-style-svg m-svg-def m-entry-prepend">
          <use xlink:href="#icon-profile-wallet" />
        </svg>
        <span class="m-text-box m-flex-grow1">提取{{ currencyUnit }}</span>
        <svg class="m-style-svg m-svg-def m-entry-append">
          <use xlink:href="#icon-arrow-right" />
        </svg>
      </router-link>
    </ul>

    <footer>
      <p @click="showRule">
        <svg class="m-style-svg m-svg-small">
          <use xlink:href="#icon-wallet-warning" />
        </svg>
        {{ currencyUnit }}规则
      </p>
    </footer>

    <popup-dialog
      ref="dialog"
      :title="`${currencyUnit}规则`">
      <p v-html="rule"/>
    </popup-dialog>

  </div>
</template>

<script>
import { mapState } from "vuex";
import PopupDialog from "@/components/PopupDialog.vue";

export default {
  name: "Currency",
  components: { PopupDialog },
  data() {
    return {
      fromPageTitle: ""
    };
  },
  computed: {
    ...mapState({
      user: "CURRENTUSER",
      currency: "currency"
    }),
    rule() {
      return this.currency.rule.replace(/\n/g, "<br>");
    }
  },
  created() {
    this.fromPageTitle = document.title;
    document.title = this.currencyUnit;
  },
  mounted() {
    if (!this.currency.recharge.length)
      this.$store.dispatch("currency/getCurrencyInfo");

    const amount = this.$route.query.total_amount;
    if (amount) {
      this.$store.dispatch("fetchUserInfo");
      this.$Message.success(
        `共消耗${amount}元, 获得 ${amount * 100} ${this.currencyUnit}!`
      );
    }
  },
  destroyed() {
    document.title = this.fromPageTitle;
  },
  methods: {
    showRule() {
      this.$refs.dialog.show();
    }
  }
};
</script>

<style lang="less" scoped>
@import "./currency.less";
</style>
