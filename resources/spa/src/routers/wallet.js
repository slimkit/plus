/**
 * 钱包模块
 */
const Wallet = () =>
  import(/* webpackChunkName: 'wallet' */ "@/page/wallet/Wallet.vue");
const WalletRecharge = () =>
  import(/* webpackChunkName: 'wallet' */ "@/page/wallet/WalletRecharge.vue");
const WalletWithdraw = () =>
  import(/* webpackChunkName: 'wallet' */ "@/page/wallet/WalletWithdraw.vue");
const WalletWithdrawDetail = () =>
  import(/* webpackChunkName: 'wallet' */ "@/page/wallet/WalletWithdrawDetail.vue");
const WalletWithdrawInfo = () =>
  import(/* webpackChunkName: 'wallet' */ "@/page/wallet/WalletWithdrawInfo.vue");
const WalletDetail = () =>
  import(/* webpackChunkName: 'wallet' */ "@/page/wallet/WalletDetail.vue");
const WalletInfo = () =>
  import(/* webpackChunkName: 'wallet' */ "@/page/wallet/WalletInfo.vue");

export default [
  {
    path: "/wallet",
    component: Wallet,
    meta: {
      title: "钱包",
      requiresAuth: true
    }
  },
  {
    path: "/wallet/recharge",
    component: WalletRecharge,
    meta: {
      title: "充值"
    }
  },
  {
    path: "/wallet/withdraw",
    component: WalletWithdraw,
    meta: {
      title: "提现"
    }
  },
  {
    path: "/wallet/withdraw/detail",
    component: WalletWithdrawDetail,
    meta: {
      title: "提现明细"
    }
  },
  {
    path: "/wallet/withdraw/detail/:id",
    component: WalletWithdrawInfo,
    meta: {
      title: "提现明细"
    }
  },
  {
    path: "/wallet/detail",
    component: WalletDetail,
    meta: {
      title: "明细"
    }
  },
  {
    path: "/wallet/detail/:id",
    component: WalletInfo,
    meta: {
      title: "明细详情"
    }
  }
];
