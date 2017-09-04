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
import FilterWordCategories from '../component/setting/FilterWordCategories';
import AddFilterWordCategory from '../component/setting/AddFilterWordCategory';
import UpdateFilterWordCategory from '../component/setting/UpdateFilterWordCategory';
import FilterWordTypes from '../component/setting/FilterWordTypes';
import SensitiveWords from '../component/setting/SensitiveWords';
import AddSensitiveWord from '../component/setting/AddSensitiveWord';
import UpdateSensitiveWord from '../component/setting/UpdateSensitiveWord';

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
      path: 'tags',
      component: Tags
    },
    {
      path: 'tag-categories',
      component: TagCategories
    },
    {
      path: 'addtag',
      component: AddTag
    },
    {
      path: 'updatetag/:tag_id',
      component: UpdateTag
    },
    {
      path: 'server',
      component: Server
    },
    {
      path: 'filter-word-categories',
      component: FilterWordCategories,
    },
    {
      path: 'filter-word-categories/add',
      component: AddFilterWordCategory,
    },
    {
      path: 'filter-word-categories/:id',
      component: UpdateFilterWordCategory,
    },
    {
      path: 'filter-word-types',
      component: FilterWordTypes,
    },
    {
      path: 'sensitive-words',
      component: SensitiveWords
    },
    {
      path: 'sensitive-words/add',
      component: AddSensitiveWord,
    },
    {
      path: 'sensitive-words/:id',
      component: UpdateSensitiveWord,
    }
  ]
};

export default settingRouter;
