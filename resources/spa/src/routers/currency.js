/**
 * 积分模块 (和 wallet 使用一致的模块合并到打包)
 */
const Currency = () =>
  import(/* webpackChunkName: 'wallet' */ "@/page/currency/Currency.vue");
const CurrencyRecharge = () =>
  import(/* webpackChunkName: 'wallet' */ "@/page/currency/CurrencyRecharge.vue");
const CurrencyWithdraw = () =>
  import(/* webpackChunkName: 'wallet' */ "@/page/currency/CurrencyWithdraw.vue");
const CurrencyDetail = () =>
  import(/* webpackChunkName: 'wallet' */ "@/page/currency/CurrencyDetail.vue");
const CurrencyJournalDetail = () =>
  import(/* webpackChunkName: 'wallet' */ "@/page/currency/CurrencyJournalDetail.vue");

export default [
  {
    path: "/currency",
    component: Currency,
    meta: {
      title: "", // 积分名称为动态名称，在组件内替换
      requiresAuth: true
    }
  },
  {
    name: "currencyRecharge",
    path: "/currency/recharge",
    component: CurrencyRecharge,
    meta: {
      title: "充值"
    }
  },
  {
    path: "/currency/withdraw",
    component: CurrencyWithdraw,
    meta: {
      title: "提现"
    }
  },
  {
    path: "/currency/detail",
    component: CurrencyDetail,
    meta: {
      title: "明细"
    }
  },
  {
    path: "/currency/journal-detail",
    component: CurrencyJournalDetail,
    meta: {
      title: "明细"
    }
  }
];
