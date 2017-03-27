//
// The file is defined "/setting" route.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
//
import Setting from '../component/Setting';
import Base from '../component/setting/Base';
import Area from '../component/setting/Area';
import Filter from '../component/setting/Filter';
import AppTheme from '../component/setting/AppTheme';
import StoreageManage from '../component/setting/StoreageManage';

const settingRouter = {
  path: 'setting',
  component: Setting,
  children: [
    {
      path: 'base',
      component: Base,
      alias: ''
    },
    {
      path: 'area',
      component: Area
    },
    {
      path: 'filter',
      component: Filter
    },
    {
      path: 'app/theme',
      component: AppTheme
    },
    {
      path: 'storeages',
      component: StoreageManage
    }
  ]
};

export default settingRouter;
