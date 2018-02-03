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
import Tags from '../component/setting/Tags';
import TagCategories from '../component/setting/TagCategories';
import AddTag from '../component/setting/AddTag';
import UpdateTag from '../component/setting/UpdateTag';
import Site from '../component/setting/Site';

// Pages
import Sensitive from '../components/pages/Sensitive';
import FileSetting from '../components/modules/file/Setting'

import BackEndSetting from '../component/setting/BackEndSetting';
import IosIap from '../components/pages/IosIap';

const settingRouter = {
  path: 'setting',
  component: Setting,
  children: [
    { path: '', component: Base },
    { path: 'area', component: Area },
    { path: 'hots', component: Hots },
    { path: 'mail', component: Mail },
    { path: 'sendmail', component: SendMail },
    { path: 'tags', component: Tags },
    { path: 'tag-categories', component: TagCategories },
    { path: 'addtag', component: AddTag },
    { path: 'updatetag/:tag_id', component: UpdateTag },
    { path: 'server', component: Server },
    { path: 'site', component: Site },
    { path: 'sensitives', component: Sensitive },
    { path: 'upload-setting', component: FileSetting },
    { path: 'back-end-setting', component: BackEndSetting },
    { path: 'ios-iap', component: IosIap },
  ]
};

export default settingRouter;
