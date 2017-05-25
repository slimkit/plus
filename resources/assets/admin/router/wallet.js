//
// The file is defined "/wallet" route.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
//

import Main from '../component/wallet/Main';
import Report from '../component/wallet/Report'
import Accounts from '../component/wallet/Accounts';
import Cash from '../component/wallet/Cash';
import CashSetting from '../component/wallet/CashSetting';
import PayOption from '../component/wallet/PayOption';
import PayRule from '../component/wallet/PayRule';
import Alipay from '../component/wallet/Alipay';
import ApplePay from '../component/wallet/ApplePay';
import WeChatPay from '../component/wallet/WeChatPay';
import PayRatio from '../component/wallet/PayRatio';

const walletRouter = {
  path: 'wallet',
  component: Main,
  children: [
    { path: '', component: Report },
    { path: 'accounts', component: Accounts },
    { path: 'cash', component: Cash },
    { path: 'cash/setting', component: CashSetting },
    { path: 'pay/option', component: PayOption },
    { path: 'pay/rule', component: PayRule },
    { path: 'pay/ratio', component: PayRatio },
    { path: 'pay/alipay', component: Alipay },
    { path: 'pay/apple', component: ApplePay },
    { path: 'pay/wechat', component: WeChatPay }
  ]
};

export default walletRouter;
