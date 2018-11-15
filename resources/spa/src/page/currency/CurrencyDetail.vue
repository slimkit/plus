<template lang="html">
  <div class="p-currency-detail">

    <common-header class="header">
      <nav class="type-switch-bar">
        <span
          :class="{active: currAction === 'recharge'}"
          @click="currAction = 'recharge'">充值记录</span>
        <span
          :class="{active: currAction === 'cash'}"
          @click="currAction = 'cash'">提取纪录</span>
      </nav>
    </common-header>

    <load-more
      ref="loadmore"
      :on-refresh="onRefresh"
      :on-load-more="onLoadMore"
      class="m-currency-list">
      <currency-detail-item
        v-for="item in list"
        v-if="item.id"
        :key="item.id"
        :detail="item"
        type="body"/>
    </load-more>
  </div>
</template>

<script>
import _ from "lodash";
import CurrencyDetailItem from "./components/CurrencyDetailItem.vue";

export default {
  name: "CurrencyDetail",
  components: { CurrencyDetailItem },
  data() {
    return {
      options: [
        { value: "all", label: "全部" },
        { value: "expenses", label: "支出" },
        { value: "income", label: "收入" }
      ],
      list: [],
      currInfo: null
    };
  },
  computed: {
    after() {
      const len = this.list.length;
      return len ? this.list[len - 1].id : 0;
    },
    currAction: {
      get() {
        return this.$route.query.action || "recharge";
      },
      set(val) {
        this.$router.replace({
          path: this.$route.path,
          query: { action: val }
        });
      }
    }
  },
  watch: {
    currAction() {
      this.list = [];
      this.$refs.loadmore.beforeRefresh();
    }
  },
  methods: {
    onRefresh() {
      this.$store
        .dispatch("currency/getCurrencyOrders", { action: this.currAction })
        .then(data => {
          if (data.length > 0)
            this.list = _.unionBy([...data, ...this.list], "id");

          this.$refs.loadmore.topEnd(data.length >= 15);
        });
    },
    onLoadMore() {
      this.$store
        .dispatch("currency/getCurrencyOrders", {
          action: this.currAction,
          after: this.after
        })
        .then(data => {
          if (data.length > 0) {
            this.list = [...this.list, ...data];
          }
          this.$refs.loadmore.bottomEnd(data.length < 15);
        });
    }
  }
};
</script>

<style lang="less" scoped>
.p-currency-detail {
  .header {
    overflow: initial;
  }

  .type-switch-bar {
    display: flex;
    justify-content: space-around;
    align-items: center;
    height: 90px;

    > span {
      display: inline-block;
      height: 100%;
      padding: 22px 12px;
      color: #999;
      transition: 0.3s;

      &.active {
        color: #333;
        border-bottom: 2px solid @primary;
      }
    }
  }
}
</style>
