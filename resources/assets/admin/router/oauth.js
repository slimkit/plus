//
// The file is defined "/oauth" route.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
//
import Main from '../component/oauth/Main';
import Client from '../component/oauth/Client';
import ApiClient from '../component/oauth/ApiClient';
export default {
  path: '/oauth',
  component: Main,
  children: [
    { path: '', component: Client },
    { path: 'api-client', component: ApiClient }
  ]
};
