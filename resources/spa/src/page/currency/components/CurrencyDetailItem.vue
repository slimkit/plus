<template>
  <div
    :class="isIncome"
    class="c-currency-detail-item">
    <div class="info">
      <div class="title" >{{ detail[type] }}</div>
      <div class="time">{{ detail.updated_at | time2tips }}</div>
    </div>
    <div class="amount">
      {{ detail.type > 0 ? '+' : '-' }}{{ detail.amount }}
    </div>
  </div>
</template>

<script>
export default {
  name: "CurrencyDetailItem",
  props: {
    detail: { type: Object, required: true },
    nostyle: { type: Boolean, default: false },
    type: { type: String, default: "title" }
  },
  computed: {
    isIncome() {
      if (this.nostyle) return "no-style";
      return this.detail.type > 0 ? "in" : "out";
    }
  }
};
</script>

<style lang="less" scoped>
.c-currency-detail-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #fff;
  border-bottom: 1px solid #ededed;
  color: #b3b3b3;
  padding: 30px;
  font-size: 30px;

  .info {
    max-width: calc(~"100% - 4em");

    .time {
      color: #b3b3b3;
      font-size: 24px;
    }
    .title {
      font-size: 30px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      margin-bottom: 10px;
    }
    .amount {
      font-size: 32px;
    }
  }

  &.no-style {
    color: #333;

    .amount {
      color: #ff9400;
    }
  }

  &.in {
    .title {
      color: #333;
    }

    .amount {
      color: #ff9400;
    }
  }
}
</style>
