<template>
  <div class="p-wallet-info">

    <common-header>账单详情</common-header>

    <header class="wallet-header">
      <p v-if="detail.state === 0">审核中</p>
      <p v-else>交易{{ detail.state === 1 ? '成功' : '失败' }}</p>
      <h2>{{ detail.type > 0 ? '+' : '-' }}{{ detail.amount / 100 | postfix(2) }}</h2>
    </header>

    <main>
      <!-- <div v-if="detail.owner_id" class="item">
        <label>{{ detail.type > 0 ? '收款人' : '付款人' }}</label>
        <span class="user-avatar">
          <avatar :user="user"/>
          {{ user.name }}
        </span>
      </div> -->
      <div class="item">
        <label>交易说明</label>
        <span> {{ detail.body || detail.title }} </span>
      </div>
      <div v-if="detail.target_type==='s'" class="item">
        <label>交易账户</label>
        <span> {{ detail.title }} </span>
      </div>
      <div class="item">
        <label>交易时间</label>
        <span> {{ detail.created_at }} </span>
      </div>
    </main>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "WalletInfo",
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
      return this.$store.getters["wallet/getWalletById"](this.id);
    },
    user() {
      return this.$store.state.CURRENTUSER;
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

      .user-avatar {
        display: flex;
        align-items: center;
        height: 80%;
      }
    }
  }
}
</style>
