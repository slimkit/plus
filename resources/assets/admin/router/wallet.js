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

const walletRouter = {
  path: 'wallet',
  component: Main,
  children: [
    { path: '', component: Report },
    { path: 'accounts', component: Accounts },
    { path: 'cash', component: Cash },
    { path: 'cash/setting', component: CashSetting }
  ]
};

export default walletRouter;
