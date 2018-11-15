<template>
  <div class="c-wallet-withdraw-detail-item" @click.stop="showDetail">
    <div class="time" v-html="created_at"/>
    <div class="title" >{{ typeText }} 账户提现</div>
    <div class="amount">
      <span v-if="detail.status === 0" class="gray">审核中</span>
      <span v-if="detail.status === 2" class="gray">审核失败</span>
      <span v-if="detail.status === 1">-{{ (detail.value / 100).toFixed(2) }}</span>
    </div>
  </div>
</template>

<script>
import { timeOffset } from "@/filters";

const typeMap = {
  alipay: "支付宝",
  wx: "微信"
};

const week = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"];
function splitYMD(date) {
  date = date || new Date();
  date = new Date(date);
  const Y = date.getFullYear();
  const M = date.getMonth() + 1;
  const D = date.getDate();
  const w = week[date.getDay()];
  const h = (date.getHours() + "").padStart(2, 0);
  const m = (date.getMinutes() + "").padStart(2, 0);
  const d = (M + "").padStart(2, 0) + "." + (D + "").padStart(2, 0);
  const t = h + ":" + m;
  return { Y, M, D, w, d, t };
}

export default {
  name: "WalletWithdrawDetailItem",
  props: {
    detail: { type: Object, required: true }
  },
  data() {
    return {};
  },
  computed: {
    created_at() {
      const now = splitYMD(new Date());
      let time = new Date(this.detail.created_at).getTime() - timeOffset;
      time = splitYMD(time);
      let D;
      if (time.Y < now.Y) {
        D = time.d;
      } else if (time.M < now.M) {
        D = time.d;
      } else if (now.D - time.D > 1) {
        D = time.w;
      } else if (now.D - time.D === 1) {
        D = "昨天";
      } else if (now.D - time.D === 0) {
        D = "今天";
      }
      return `<p>${D}</p><p>${time.d}</p>`;
    },
    typeText() {
      return typeMap[this.detail.type] || "";
    }
  },
  methods: {
    showDetail() {
      this.$emit("click");
    }
  }
};
</script>

<style lang="less" scoped>
.c-wallet-withdraw-detail-item {
  padding: 30px;
  font-size: 30px;
  line-height: 36px;
  border-bottom: 1px solid #ededed;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #fff;

  .time {
    color: #b3b3b3;
    font-size: 24px;
    text-align: center;
    line-height: 1;
  }

  .title {
    margin: 0 30px;
    width: 100%;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  .amount {
    width: 6em;
    text-align: right;

    > span {
      color: #ff9400;

      &.gray {
        color: #b3b3b3;
      }
    }
  }
}
</style>
