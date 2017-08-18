//
// The file is defined "/setting" route.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
//
import Setting from '../component/Setting';
import Base from '../component/setting/Base';
import Area from '../component/setting/Area';
import Hots from '../component/setting/Hots';
import Mail from '../component/setting/Mail';
import SendMail from '../component/setting/SendMail';
import Server from '../component/setting/Server';

const settingRouter = {
  path: 'setting',
  component: Setting,
  children: [
    {
      path: '',
      component: Base,
    },
    {
      path: 'area',
      component: Area
    },
    {
      path: 'hots',
      component: Hots
    },
    {
      path: 'mail',
      component: Mail
    },
    {
      path: 'sendmail',
      component: SendMail
    },
    {
      path: 'server',
      component: Server
    }  
  ]
};

export default settingRouter;
