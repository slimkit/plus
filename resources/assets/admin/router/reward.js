/**
 * 打赏路由
 */
import Main from '../component/reward/Main';
// 打赏统计
import Home from '../component/reward/Home';
// 打赏清单
import List from '../component/reward/List';

export default {
  path: 'reward',
  component: Main,
    children: [
        { path: '', component: Home },
        { path: 'list', component: List },
    ],
};
