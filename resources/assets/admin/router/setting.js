//
// The file is defined "/setting" route.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
//
import Setting from '../component/Setting';
import Base from '../component/setting/Base';
import Area from '../component/setting/Area';
import StoreageManage from '../component/setting/StoreageManage';

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
      path: 'storeages',
      component: StoreageManage
    }
  ]
};

export default settingRouter;
