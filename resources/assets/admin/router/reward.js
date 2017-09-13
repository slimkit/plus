// 打赏路由

import Main from '../component/reward/Main';
import Home from '../component/reward/Home';

export default {
  path: 'reward',
  component: Main,
    children: [
        { path: '', component: Home },
    ],
};
