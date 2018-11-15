<template lang="html">
  <div class="wallet-detail p-wallet-detail">

    <common-header class="header">
      <diy-select
        v-model="currAction"
        :options="options"
        placeholder="明细"/>
    </common-header>

    <load-more
      ref="loadmore"
      :on-refresh="onRefresh"
      :on-load-more="onLoadMore"
      class="m-wallet-list">
      <wallet-detail-item
        v-for="item in list"
        v-if="item.id"
        :key="item.id"
        :detail="item"
        @click="showDetail(item)" />
    </load-more>
  </div>
</template>

<script>
import walletInfo from "./WalletInfo";
import walletDetailItem from "./components/WalletDetailItem.vue";

export default {
  name: "WalletDetail",
  components: {
    walletInfo,
    walletDetailItem
  },
  data() {
    return {
      options: [
        { value: "all", label: "全部" },
        { value: "expenses", label: "支出" },
        { value: "income", label: "收入" }
      ],
      currAction: "",
      list: [],
      currInfo: null
    };
  },
  computed: {
    after() {
      const last = this.list.slice(-1)[0];
      return last ? last.id : 0;
    }
  },
  watch: {
    currAction() {
      this.list = [];
      this.$refs.loadmore.beforeRefresh();
    }
  },
  methods: {
    showDetail(val) {
      this.$router.push({
        path: `/wallet/detail/${val.id}`,
        meta: { data: val }
      });
    },
    async onRefresh() {
      const data = await this.$store.dispatch("wallet/getWalletOrders", {
        action: this.currAction
      });

      if (data.length > 0) this.list = data;

      this.$refs.loadmore.topEnd(data.length < 15);
    },
    async onLoadMore() {
      const data = await this.$store.dispatch("wallet/getWalletOrders", {
        action: this.currAction,
        after: this.after
      });
      if (data.length > 0) this.list = [...this.list, ...data];
      this.$refs.loadmore.bottomEnd(data.length < 15);
    }
  }
};
</script>

<style lang="less" scoped>
.p-wallet-detail {
  .header {
    overflow: initial;
  }
}
</style>
