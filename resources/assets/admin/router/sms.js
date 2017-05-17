//
// The file is defined "/sms" route.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
//

import Main from '../component/sms/Main';
import Home from '../component/sms/Home';
import Driver from '../component/sms/Driver';
import Alidayu from '../component/sms/Alidayu';

const smsRouter = {
  path: 'sms',
  component: Main,
  children: [
    { path: '', component: Home },
    { path: 'driver', component: Driver },
    { path: 'alidayu', component: Alidayu },
  ],
};

export default smsRouter;
