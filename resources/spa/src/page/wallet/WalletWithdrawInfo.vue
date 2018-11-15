<template>
  <div class="p-wallet-info">

    <common-header>账单详情</common-header>

    <header class="wallet-header">
      <p>{{ statusText }}</p>
      <h2>-{{ detail.value / 100 | postfix(2) }}</h2>
    </header>

    <main>
      <div class="item">
        <label>交易说明</label>
        <span> 提现 </span>
      </div>
      <div class="item">
        <label>交易账户</label>
        <span> {{ detail.type === "alipay" ? "支付宝": '' }} </span>
      </div>
      <div class="item">
        <label>交易时间</label>
        <span> {{ detail.created_at | addTimeOffset }} </span>
      </div>
    </main>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "WalletWithdrawInfo",
  filters: {
    postfix(val, pos) {
      if (!val) return "0.00";
      return val.toFixed(pos);
    }
  },
  data() {
    return {
      // detail: {}
    };
  },
  computed: {
    ...mapState({ wallet: "wallet" }),
    id() {
      return Number(this.$route.params.id);
    },
    detail() {
      return this.$store.getters["wallet/getCashesById"](this.id);
    },
    user() {
      return this.$store.state.CURRENTUSER;
    },
    statusText() {
      if (this.detail.status === 0) return "审核中";
      if (this.detail.status === 2) return "审核失败";
      return "交易成功";
    }
  },
  mounted() {
    if (!this.wallet.list.length)
      this.$store.dispatch("wallet/getWalletOrders");
  },
  methods: {}
};
</script>

<style lang="less" scoped>
.p-wallet-info {
  .wallet-header {
    height: 300px;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    background-color: #fff;
    padding: 40px 20px;

    > p {
      font-size: 28px;
      color: #999;
    }

    > h2 {
      font-size: 100px;
    }
  }
  main {
    margin-top: 20px;
    background: #fff;

    > .item {
      height: 100px;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      border-top: 1px solid #ededed;

      &:first-child {
        border-top: none;
      }

      label {
        width: 6em;
        font-size: 30px;
        color: #999;
        padding: 0 1em;
      }
    }
  }
}
</style>
