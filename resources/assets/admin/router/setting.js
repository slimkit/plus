//
// The file is defined "/setting" route.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
//
import Setting from '../component/Setting';
import Base from '../component/setting/Base';

const settingRouter = {
  path: 'setting',
  component: Setting,
  children: [
    {
      path: '',
      component: Base
    }
  ]
};

export default settingRouter;
